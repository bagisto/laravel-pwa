<?php
    return [
        [
            'key' => 'PWA',
            'name' => 'pwa::app.admin.system.pwa_full_name',
            'route' => 'pwa.pushnotification.create',
            'sort' => 9,
            'icon-class' => 'pwa-icon',
        ], [
            'key' => 'PWA.index',
            'route' => 'pwa.pushnotification.index',
            'name' => 'pwa::app.admin.layouts.push-notification',
            'sort' => 1
        ], [
            'key' => 'PWA.layout',
            'route' => 'pwa.layout',
            'name' => 'pwa::app.admin.layouts.index',
            'sort' => 1
        ]
    ];
?>