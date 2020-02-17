<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Sales\Repositories\InvoiceRepository;
use PDF;

/**
 * Invoice controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>@vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Repository object
     *
     * @var array
     */
    protected $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\InvoiceRepository     $invoiceRepository
     * @return void
     */
    public function __construct(
        InvoiceRepository $invoiceRepository
    )   {

        $this->middleware('customer');

        $this->_config = request('_config');

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        $pdf = PDF::loadView('shop::customers.account.orders.pdf', compact('invoice'))->setPaper('a4');
        
        return $pdf->output();
    }
}
