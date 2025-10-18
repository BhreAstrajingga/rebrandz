<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;

class CompanyProfileController extends Controller
{
    public function profilePayload(Request $request): JsonResponse
    {
        $profile = $this->resolveProfile($request, false);

        return response()->json($profile);
    }

    public function view(Request $request): ViewContract
    {
        /** CATATAN
         * gunakan profilePayload untuk mendapatkan data
         * buat blade view untuk menampilkan data,
         * khusus field custom_columns, harus di pecah dulu karena data type json (array):
         * {"Domisili": {"type": "text", "value": {"4f3141af-9c27-4df6-ba59-ac82c8a79eb4": "Bogor"}}, "NIB File": {"type": "file", "value": "profile-files/nib-file-474.pdf"}, "Domisili File": {"type": "file", "value": "profile-files/domisili-file-474.pdf"}}
         * khusus jika type:file, maka dia adalah attachment file, ia berbentuk unduhan
         *
         */
        $profile = $this->resolveProfile($request, false);

        $customColumnsRaw = $profile->custom_columns;
        $customColumnsParsed = [];

        if (! empty($customColumnsRaw)) {
            $decoded = is_array($customColumnsRaw)
                ? $customColumnsRaw
                : json_decode((string) $customColumnsRaw, true);

            if (is_array($decoded)) {
                foreach ($decoded as $label => $meta) {
                    $type = isset($meta['type']) ? (string) $meta['type'] : 'text';
                    $value = $meta['value'] ?? null;

                    if (is_array($value)) {
                        $value = implode(', ', array_values($value));
                    }

                    $customColumnsParsed[] = [
                        'label' => (string) $label,
                        'type' => $type,
                        'value' => $value,
                    ];
                }
            }
        }

        return view('admin.company-profile', [
            'profile' => $profile,
            'company' => $profile->company,
            'customColumns' => $customColumnsParsed,
            'companyAddress' => $this->formatAddressForDisplay($profile->company->address ?? null),
        ]);
    }

    public function downloadAttachment(Request $request): Response
    {
        // Validate alias only for downloads (no token usage)
        $alias = (string) $request->alias;
        if ($alias === '') {
            abort(404);
        }
        $profile = CompanyProfile::query()
            ->where('company_alias', $alias)
            ->first();
        if (! $profile) {
            abort(404);
        }

        $path = (string) $request->get('path');

        if ($path === '') {
            abort(404);
        }

        // External absolute URL: fetch and force-download
        if (preg_match('/^https?:\/\//i', $path)) {
            $filename = basename(parse_url($path, PHP_URL_PATH) ?: 'download');
            $response = App::environment('production')
                ? Http::get($path)
                : Http::withoutVerifying()->get($path);

            if (! $response->successful()) {
                abort(404);
            }

            $contentType = $response->header('Content-Type') ?? 'application/octet-stream';

            return response($response->body(), 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        // Public storage path under /storage
        if (str_starts_with($path, 'storage/')) {
            $full = public_path($path);
            if (! is_file($full)) {
                abort(404);
            }

            return response()->download($full);
        }

        if (str_starts_with($path, '/storage/')) {
            $full = public_path(ltrim($path, '/'));
            if (! is_file($full)) {
                abort(404);
            }

            return response()->download($full);
        }

        // App-managed storage (private by default)
        if (str_starts_with($path, 'profile-files/')) {
            if (Storage::exists($path)) {
                return Storage::download($path);
            }

            abort(404);
        }

        abort(404);
    }

    /**
     * Resolve the profile or abort with 404 if invalid.
     */
    protected function resolveProfile(Request $request, bool $consume = false): CompanyProfile
    {
        $alias = (string) $request->alias;
        $tempToken = (string) $request->tempToken;

        if ($alias === '' || $tempToken === '') {
            abort(404);
        }

        if ($consume) {
            $profile = CompanyProfile::consumeTempTokenByAlias($alias, $tempToken);
            if (! $profile) {
                abort(404);
            }
        } else {
            $profile = CompanyProfile::with([
                'company' => function ($q) {
                    $q->select('id', 'name', 'address', 'phone', 'email', 'signature_name');
                },
            ])
                ->where('company_alias', $alias)
                ->first();

            if (! $profile || $tempToken !== (string) $profile->temp_token) {
                abort(404);
            }
        }

        return $profile;
    }

    /**
     * Normalize company address for consistent UI rendering.
     */
    protected function formatAddressForDisplay(?string $address): string
    {
        if ($address === null) {
            return '-';
        }

        $value = (string) $address;

        $value = str_ireplace(['&nbsp;'], ' ', $value);
        $value = preg_replace('/<\s*br\s*\/?>/i', "\n", $value) ?? $value;
        $value = str_ireplace(['</p>'], "\n", $value);
        $value = str_ireplace(['<p>'], '', $value);

        $value = strip_tags($value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);

        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = preg_replace("/\n{3,}/", "\n\n", $value) ?? $value;
        $value = preg_replace('/[\t ]{2,}/', ' ', $value) ?? $value;
        $value = trim($value);

        if ($value === '') {
            return '-';
        }

        $html = nl2br(e($value), false);

        return $html;
    }

    public function consume(Request $request): Response
    {
        // Rotate token only when explicitly requested by the page JS
        $this->resolveProfile($request, true);

        return response()->noContent();
    }
}
