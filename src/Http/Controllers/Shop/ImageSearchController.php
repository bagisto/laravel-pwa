<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Product\Repositories\SearchRepository;

/**
 * Review controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ImageSearchController extends Controller
{
    /**
     * SearchRepository object
     *
     * @var \Webkul\Core\Repositories\SearchRepository
    */
    protected $searchRepository;

    /**
     * Controller instance
     *
     * @param Webkul\Product\Repositories\SearchRepository $searchRepository
     */
    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    /**
     * Upload image for product search with machine learning
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $url = $this->searchRepository->uploadSearchImage(request()->all());

        return $url; 
    }
}