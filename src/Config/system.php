<?php

return [
    [
        'key' => 'pwa',
        'name' => 'pwa::app.admin.system.pwa',
        'sort' => 1
    ], [
        'key' => 'pwa.settings',
        'name' => 'pwa::app.admin.system.settings',
        'sort' => 1,
    ], [
        'key' => 'pwa.settings.general',
        'name' => 'pwa::app.admin.system.general',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'pwa::app.admin.system.status',
                'type' => 'boolean'
            ],  [
                'name' => 'name',
                'title' => 'pwa::app.admin.system.name',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'short_name',
                'title' => 'pwa::app.admin.system.short-name',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'theme_color',
                'title' => 'pwa::app.admin.system.theme-color',
                'type' => 'text',
                'validation' => 'required',
                'info' => 'pwa::app.admin.system.info',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'background_color',
                'title' => 'pwa::app.admin.system.background-color',
                'type' => 'text',
                'validation' => 'required',
                'info' => 'pwa::app.admin.system.info',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'enable_new',
                'title' => 'pwa::app.admin.system.enable_new',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'enable_featured',
                'title' => 'pwa::app.admin.system.enable_featured',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'enable_slider',
                'title' => 'pwa::app.admin.system.enable_slider',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'enable_categories_home_page_listing',
                'title' => 'pwa::app.admin.system.enable_categories_home_page_listing',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'redirect_to_pwa_if_mobile',
                'title' => 'pwa::app.admin.system.redirect_to_pwa_if_mobile',
                'type' => 'boolean',
                'channel_based' => true,
                'locale_based' => false
            ]
        ]
    ], [
        'key' => 'pwa.settings.seo',
        'name' => 'admin::app.settings.channels.seo',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'seo_author',
                'title' => 'pwa::app.admin.system.seo-author',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'seo_title',
                'title' => 'admin::app.settings.channels.seo-title',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'seo_description',
                'title' => 'admin::app.settings.channels.seo-description',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'seo_keywords',
                'title' => 'admin::app.settings.channels.seo-keywords',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => false
            ],
        ]
    ], [
        'key' => 'pwa.settings.media',
        'name' => 'pwa::app.admin.system.media',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'small',
                'title' => 'pwa::app.admin.system.small',
                'type' => 'image',
                'validation' => 'ext:jpeg,jpg,png',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'medium',
                'title' => 'pwa::app.admin.system.medium',
                'type' => 'image',
                'validation' => 'ext:jpeg,jpg,png',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'large',
                'title' => 'pwa::app.admin.system.large',
                'type' => 'image',
                'validation' => 'ext:jpeg,jpg,png',
                'channel_based' => false,
                'locale_based' => false
            ], [
                'name' => 'extra_large',
                'title' => 'pwa::app.admin.system.extra-large',
                'type' => 'image',
                'validation' => 'ext:jpeg,jpg,png',
                'channel_based' => false,
                'locale_based' => false
            ]
        ]
    ], [
        'key' => 'pwa.settings.push-notification',
        'name' => 'pwa::app.admin.system.push-notification',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'topic',
                'title' => 'pwa::app.admin.system.topic',
                'type' => 'text',
                'validation' => 'required',
                'default' => 'bagisto',
                'validation' => 'alpha_num|required',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'messaging-id',
                'title' => 'pwa::app.admin.system.messagingId',
                'type' => 'text',
                'validation' => 'required',
                'validation' => 'numeric|required',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'api-key',
                'title' => 'pwa::app.admin.system.server-key',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'auth-domain',
                'title' => 'pwa::app.admin.system.auth-domain',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'database-url',
                'title' => 'pwa::app.admin.system.database-url',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'project-id',
                'title' => 'pwa::app.admin.system.project-id',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'app-id',
                'title' => 'pwa::app.admin.system.app-id',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'web-api-key',
                'title' => 'pwa::app.admin.system.api-key',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ],
        ]
    ]

];