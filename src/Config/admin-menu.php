<?php

return [
    [
        'key'   => 'PWA',
        'name'  => 'pwa::app.admin.system.pwa_full_name',
        'route' => 'admin.pwa.pushnotification.index',
        'sort'  => 8,
        'icon'  => 'pwa-icon',
    ], [
        'key'   => 'PWA.index',
        'route' => 'admin.pwa.pushnotification.index',
        'name'  => 'pwa::app.admin.layouts.push-notification',
        'sort'  => 1,
    ], [
        'key'   => 'PWA.layout',
        'route' => 'admin.pwa.layout',
        'name'  => 'pwa::app.admin.layouts.title',
        'sort'  => 1,
    ],
];
