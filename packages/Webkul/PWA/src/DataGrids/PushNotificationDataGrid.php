<?php

namespace Webkul\PWA\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use DB;

/**
 * OrderDataGrid Class
 *
 * @author Aayush Bhatt <aayush.bhatt172@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class PushNotificationDataGrid extends DataGrid
{
    protected $index = 'id';            // the column that needs to be treated as index column

    protected $sortOrder = 'desc';      // asc or desc

    protected $itemsPerPage = 5;        // enables the pagination

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('push_notifications')
                ->select('id','title','description','targeturl','imageurl');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('pwa::app.admin.system.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => trans('pwa::app.admin.system.pushnotificationtitle'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'description',
            'label' => trans('pwa::app.admin.system.pushnotificationdescription'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false
        ]);

        $this->addColumn([
            'index' => 'targeturl',
            'label' => trans('pwa::app.admin.system.targeturl'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'pwa.pushnotification.edit',
            'icon' => 'icon pencil-lg-icon',
            'method' => 'GET'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'pwa.pushnotification.delete',
            'icon' => 'icon trash-icon',
            'method' => 'GET'
        ]);

        $this->addAction([
            'type' => 'send',
            'route' => 'pwa.pushnotification.pushtofirebase',
            'icon' => 'icon angle-right-icon',
            'method' => 'GET'
        ]);
    }
}
?>