<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Barryvdh\DomPDF\Facade\Pdf;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\PWA\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    /**
     * Controller instance
     *
     * @param  Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     */
    public function __construct(
        protected InvoiceRepository $invoiceRepository
    ) {}

    /**
     * Print and download the for the specified resource.
     *
     * @param int $id invoice id.
     */
    public function print($id)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        $pdf =  PDF::loadHTML($this->adjustArabicAndPersianContent(view('shop::customers.account.orders.pdf', compact('invoice'))->render()))
            ->setPaper('a4');

        return $pdf->output();
    }

    /**
     * Adjust arabic and persian content.
     *
     * @return string
     */
    protected function adjustArabicAndPersianContent(string $html)
    {
        $arabic = new \ArPHP\I18N\Arabic();

        $p = $arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $html = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
}
