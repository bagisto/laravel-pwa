<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Sales\Repositories\InvoiceRepository;
use PDF;

/**
 * Invoice controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceController extends Controller
{
    /**
     * Contains current guard.
     *
     * @var array
     */
    protected $guard;

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
     * Repository object.
     *
     * @var \Webkul\Core\Eloquent\Repository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\InvoiceRepository     $invoiceRepository
     * @return void
     */
    public function __construct(
        InvoiceRepository $invoiceRepository
    ) {

        $this->middleware('customer');

        $this->_config = request('_config');

        $this->invoiceRepository = $invoiceRepository;

        $this->guard = request()->has('token') ? 'api' : 'customer';

        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {

            auth()->setDefaultDriver($this->guard);

            $this->middleware('auth:' . $this->guard);
        }

        $this->repository = app($this->_config['repository']);
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

    /**
     * Returns a listing of the invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->repository->scopeQuery(function($query) {
            $query = $query->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')->select('invoices.*', 'orders.customer_id');

            if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {
                $query = $query->where('customer_id', auth()->user()->id);
            }

            foreach (request()->except(['page', 'limit', 'pagination', 'sort', 'order', 'token']) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if ($sort = request()->input('sort')) {
                $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            $results = $query->paginate(request()->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->_config['resource']::collection($results);
    }

    /**
     * Returns an individual invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {
            $query = $this->repository->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')
                          ->select('invoices.*', 'orders.customer_id')
                          ->where('customer_id', auth()->user()->id)
                          ->findOrFail($id);
        } else {
            $query = $this->repository->findOrFail($id);
        }

        return new $this->_config['resource']($query);
    }
}
