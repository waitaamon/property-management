<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Invoices\Invoice;
use App\Models\Payments\Payment;
use Illuminate\Support\Facades\Storage;

class PrintController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['type' => ['required', 'string', 'in:payment,invoice,delivery-note'], 'id' => ['required', 'integer']]);

        $filePath = $this->getPrintableDocumentFilePath();

        return response(chunk_split(base64_encode(file_get_contents($filePath))));
    }

    protected function getPrintableDocumentFilePath(): string
    {
        $model = $this->getPrintableModel();

        $pdf = $this->generatePrintablePdf($model);

        $name = 'exports/' . strtolower($model->code) . '.pdf';

        $disk = Storage::disk('public');

        $disk->put($name, $pdf);

        return $disk->path($name);
    }

    protected function generatePrintablePdf($model)
    {
        $setting = Setting::query()->first();

        $view = 'prints.' . request('type');

        return app('dompdf.wrapper')
            ->loadView($view, ['payload' => $model, 'setting' => $setting])
            ->output();
    }

    protected function getPrintableModel()
    {
        return match (request('type')) {
            'payment' => Payment::with('bankAccount', 'tenant', 'user')->findOrFail(request('id')),
            'invoice' => Invoice::with('user', 'tenant', 'invoiceable')->findOrFail(request('id')),
        };
    }
}
