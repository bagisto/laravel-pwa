<?php

namespace Webkul\PWA\DataGrids;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

/**
 * Push notification datagrid.
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 */
class PushNotificationDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('push_notifications')
            ->select(
                'id',
                'title',
                'description',
                'targeturl',
                'imageurl'
            );

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('pwa::app.admin.datagrid.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('pwa::app.admin.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'         => 'imageurl',
            'label'         => trans('pwa::app.admin.datagrid.icon'),
            'type'          => 'string',
            'searchable'    => false,
            'sortable'      => false,
            'filterable'    => false,
            'closure'       => function ($row) {
                if ($row->imageurl) {
                    return '<img src=' . Storage::url($row->imageurl) . ' class="img-thumbnail" width="50px" height="70px" />';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'targeturl',
            'label'      => trans('pwa::app.admin.datagrid.target-url'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('pwa::app.admin.datagrid.view'),
            'icon'   => 'icon-edit',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.pwa.pushnotification.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('pwa::app.admin.datagrid.delete'),
            'icon'   => 'icon-delete',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.pwa.pushnotification.delete', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('pwa::app.admin.datagrid.send'),
            'icon'   => 'icon-notification',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('pwa.pushnotification.pushtofirebase', $row->id);
            },
        ]);
    }
}
