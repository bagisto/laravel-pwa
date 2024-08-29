<?php

namespace Webkul\PWA\Helpers;

use Webkul\Category\Repositories\CategoryRepository;

class AdminHelper
{
    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return \Webkul\Category\Contracts\Category
     */
    public function storePwaStatusInCategory($category)
    {
        if (core()->getConfigData('pwa.settings.general.status')) {
            $data = request()->all();

            if (! $category instanceof \Webkul\Category\Contracts\Category) {
                $category = $this->categoryRepository->findOrFail($category);
            }

            $category->category_product_in_pwa = $data['category_product_in_pwa'] ?? 0;

            $category->save();
        }

        return $category;
    }
}
