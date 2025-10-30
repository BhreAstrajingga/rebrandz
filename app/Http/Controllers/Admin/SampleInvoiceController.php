<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class SampleInvoiceController extends Controller
{
    public function download(Request $request)
    {
        $payload = $request->query('invoice');
        $today = now();

        if (! $payload) {
            abort(400, 'Missing invoice payload.');
        }

        $invoice = json_decode(base64_decode($payload), true);

        if (! $invoice) {
            abort(400, 'Invalid invoice payload.');
        }

        $html = view('sampleinvoice.template', ['invoice' => $invoice])->render();
        $filename = 'Invoice-' . $invoice['invoice_number'] . '.pdf';
        $pdfPath = storage_path('app/tmp/' . $filename);

        // pastikan folder tmp ada
        if (! is_dir(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }

        Browsershot::html($html)
            ->format('A4')
            // ->landscape(false)
            ->margins(0, 0, 0, 0)
            ->showBackground()
            ->showBrowserHeaderAndFooter()
            ->footerHtml('<div style="
            font-family: Courier, monospace;
            color: #969696ff;
            font-size: 10px;
            width: 100%;
            text-align: center;
            padding-top: 5px;
        ">
            Generated at '. $today .'<br>Page <span class="pageNumber"></span> / <span class="totalPages"></span>
        </div>')
            ->savePdf($pdfPath);

        return response()->download($pdfPath, $filename)->deleteFileAfterSend(true);
    }
}
