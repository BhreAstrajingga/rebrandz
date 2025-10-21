<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InfoRateController extends Controller
{
    /**
     * Scrape BRIefx Info Rate page and return structured rates.
     *
     * @return array{
     *     success: bool,
     *     source_url: string,
     *     retrieved_at: string,
     *     tt_counter_timestamp?: string|null,
     *     e_rate_timestamp?: string|null,
     *     count?: int,
     *     rates?: array<int, array{
     *         currency: string,
     *         tt_counter: array{buy_raw: string, sell_raw: string, buy: float|null, sell: float|null},
     *         e_rate: array{buy_raw: string, sell_raw: string, buy: float|null, sell: float|null},
     *     }>,
     *     error?: string,
     *     status?: int
     * }
     */
    public function getRates(): array
    {
        $sourceUrl = 'https://briefx.bri.co.id/Information/InfoRate';

        try {
            $html = $this->fetchSourceHtml($sourceUrl);
            if ($html === null) {
                return [
                    'success' => false,
                    'source_url' => $sourceUrl,
                    'retrieved_at' => now()->toIso8601String(),
                    'error' => 'Unable to retrieve source HTML',
                ];
            }

            $xpath = $this->createXPath($html);
            if ($xpath === null) {
                return [
                    'success' => false,
                    'source_url' => $sourceUrl,
                    'retrieved_at' => now()->toIso8601String(),
                    'error' => 'Unable to parse HTML',
                ];
            }

            [$ttTimestamp, $eRateTimestamp] = $this->parseHeaderTimestamps($xpath);
            $rows = $this->parseRateRows($xpath);

            return [
                'success' => true,
                'source_url' => $sourceUrl,
                'retrieved_at' => now()->toIso8601String(),
                'tt_counter_timestamp' => $ttTimestamp,
                'e_rate_timestamp' => $eRateTimestamp,
                'count' => count($rows),
                'rates' => $rows,
            ];
        } catch (\Throwable $e) {
            Log::error('InfoRate scrape exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'source_url' => $sourceUrl,
                'retrieved_at' => now()->toIso8601String(),
                'error' => 'Exception: '.$e->getMessage(),
            ];
        }
    }

    private function cleanText(?string $value): string
    {
        $value = $value ?? '';
        $value = trim(str_replace(["\n", "\r", "\t", "\u{00A0}"], ' ', $value));
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    private function toNumberOrNull(?string $value): ?float
    {
        $value = $this->cleanText($value ?? '');
        if ($value === '' || strcasecmp($value, 'N/A') === 0) {
            return null;
        }

        $normalized = str_replace(',', '', $value);
        $normalized = preg_replace('/[^0-9.\-]/', '', $normalized ?? '');
        if ($normalized === '' || $normalized === '-' || $normalized === '.') {
            return null;
        }

        return (float) $normalized;
    }

    private function extractTimestamp(string $headerText, string $label): ?string
    {
        $text = $this->cleanText($headerText);
        if ($text === '') {
            return null;
        }

        $text = preg_replace('/^'.preg_quote($label, '/').'/i', '', $text);
        $text = trim((string) $text);
        if ($text === '') {
            return null;
        }

        return $text;
    }

    private function fetchSourceHtml(string $url): ?string
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'.
                ' AppleWebKit/537.36 (KHTML, like Gecko)'.
                ' Chrome/126.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.7',
        ])->timeout(15)->retry(2, 250)->get($url);

        if (! $response->ok()) {
            Log::warning('InfoRate scrape failed: non-200', [
                'status' => $response->status(),
            ]);

            return null;
        }

        return $response->body();
    }

    private function createXPath(string $html): ?\DOMXPath
    {
        $dom = new \DOMDocument;
        $internalErrors = libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        if (! $loaded) {
            Log::warning('InfoRate scrape failed: unable to parse HTML');

            return null;
        }

        return new \DOMXPath($dom);
    }

    private function parseHeaderTimestamps(\DOMXPath $xpath): array
    {
        $ttTimestamp = null;
        $eRateTimestamp = null;

        $headerThNodes = $xpath->query('//table//thead/tr[1]/th');
        if ($headerThNodes instanceof \DOMNodeList && $headerThNodes->length >= 3) {
            $ttText = trim($headerThNodes->item(1)?->textContent ?? '');
            $erText = trim($headerThNodes->item(2)?->textContent ?? '');

            $ttTimestamp = $this->extractTimestamp($ttText, 'TT Counter');
            $eRateTimestamp = $this->extractTimestamp($erText, 'e-Rate');
        }

        return [$ttTimestamp, $eRateTimestamp];
    }

    private function parseRateRows(\DOMXPath $xpath): array
    {
        $rows = [];
        $rowNodes = $xpath->query('//table//tbody/tr');
        if ($rowNodes instanceof \DOMNodeList) {
            foreach ($rowNodes as $tr) {
                $cells = $xpath->query('.//td', $tr);
                if (! ($cells instanceof \DOMNodeList) || $cells->length < 5) {
                    continue;
                }

                $currency = $this->cleanText($cells->item(0)?->textContent ?? '');
                $ttBuyRaw = $this->cleanText($cells->item(1)?->textContent ?? '');
                $ttSellRaw = $this->cleanText($cells->item(2)?->textContent ?? '');
                $eBuyRaw = $this->cleanText($cells->item(3)?->textContent ?? '');
                $eSellRaw = $this->cleanText($cells->item(4)?->textContent ?? '');

                $rows[] = [
                    'currency' => $currency,
                    'tt_counter' => [
                        'buy_raw' => $ttBuyRaw,
                        'sell_raw' => $ttSellRaw,
                        'buy' => $this->toNumberOrNull($ttBuyRaw),
                        'sell' => $this->toNumberOrNull($ttSellRaw),
                    ],
                    'e_rate' => [
                        'buy_raw' => $eBuyRaw,
                        'sell_raw' => $eSellRaw,
                        'buy' => $this->toNumberOrNull($eBuyRaw),
                        'sell' => $this->toNumberOrNull($eSellRaw),
                    ],
                ];
            }
        }

        return $rows;
    }
}
