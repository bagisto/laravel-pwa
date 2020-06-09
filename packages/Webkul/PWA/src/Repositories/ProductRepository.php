<?php

namespace Webkul\PWA\Repositories;

use Illuminate\Container\Container as App;
use DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\SAASCustomizer\Models\Product\ProductFlat;

/**
 * Product Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository             $attribute
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository       $attributeOption
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        AttributeOptionRepository $attributeOption,
        App $app)
    {
        $this->attribute = $attribute;

        $this->attributeOption = $attributeOption;

        parent::__construct($app);
    }

    /**->where('product_flat.visible_individually', 1)
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        $company = \Company::getCurrent();
        
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($params, $categoryId, $company) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = DB::table('product_flat');

                $qb = $qb->newQuery()->from('product_flat')->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS final_price'))

                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key');

                if ($categoryId) {
                    $qb->where('product_categories.category_id', $categoryId);
                }

                if (is_null(request()->input('status'))) {
                    $qb->where('product_flat.status', 1);
                }

                if (is_null(request()->input('visible_individually'))) {
                    $qb->where('product_flat.visible_individually', 1);
                }

                $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                    $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                        ->where('flat_variants.channel', $channel)
                        ->where('flat_variants.locale', $locale);
                });

                if (isset($params['search'])) {
                    $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
                }

                if (isset($params['sort'])) {
                    $attribute = $this->attribute->findOneByField('code', $params['sort']);

                    if ($params['sort'] == 'price') {
                        if ($attribute->code == 'price') {
                            $qb->orderBy('final_price', $params['order']);
                        } else {
                            $qb->orderBy($attribute->code, $params['order']);
                        }
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }

                $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');
 
                $qb = $qb->where(function($query1) use($qb, $company) {
                    $aliases = [
                            'products' => 'filter_',
                            'variants' => 'variant_filter_'
                        ];

                    foreach($aliases as $table => $alias) {
                        $query1 = $query1->orWhere(function($query2) use($qb, $table, $alias, $company) {

                            foreach ($this->attribute->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                                $aliasTemp = $alias . $attribute->code;

                                $qb = $qb->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                                $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                                $temp = explode(',', request()->get($attribute->code));

                                if ($attribute->type != 'price') {
                                    $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                    $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                        foreach($temp as $code => $filterValue) {
                                            $columns = $aliasTemp . '.' . $column;
                                            $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                        }
                                    });

                                    $query2 = $query2->where($aliasTemp.'.company_id', $company->id);
                                } else {
                                    $query2 = $query2->where($aliasTemp . '.' . $column, '>=', core()->convertToBasePrice(current($temp)))
                                            ->where($aliasTemp . '.' . $column, '<=', core()->convertToBasePrice(end($temp)))
                                            ->where($aliasTemp . '.attribute_id', $attribute->id);
                                }
                            }
                        });
                    }
                });

                return $qb->groupBy('product_flat.id');
            })->paginate(isset($params['limit']) ? $params['limit'] : 9);

            foreach ($results as $key => $result) {
                $results[$key] = ProductFlat::where('id', $result->id)->get()->first();
            }
    
        return $results;
    }

}