<?php

namespace Webkul\PWA\Helpers;

use Webkul\Category\Repositories\CategoryRepository;

class AdminHelper
{
    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    /**
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return \Webkul\Category\Contracts\Category
     */
    public function storeCategoryIcon($category)
    {
        if (core()->getConfigData('pwa.settings.general.status')) {
            $data = request()->all();

            if (! $category instanceof \Webkul\Category\Contracts\Category) {
                $category = $this->categoryRepository->findOrFail($category);
            }

            $category->category_product_in_pwa = ($data['add_in_pwa'] ?? 0) == "1" ? 1 : 0;
            $category->save();
        }

        return $category;
    }
}
