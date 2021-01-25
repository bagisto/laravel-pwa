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
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'background_color',
                'title' => 'pwa::app.admin.system.background-color',
                'type' => 'text',
                'validation' => 'required',
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
                'default' => 'bagisto',
                'validation' => 'alpha_num',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'messaging-id',
                'title' => 'pwa::app.admin.system.messagingId',
                'type' => 'text',
                'validation' => 'numeric',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'api-key',
                'title' => 'pwa::app.admin.system.api-key',
                'type' => 'password',
                'channel_based' => true,
                'locale_based' => false
            ]
        ]
    ], [
        'key'  => 'pwa.social_login',
        'name' => 'pwa::app.admin.system.social-login',
        'sort' => 2,
    ], [
        'key'    => 'pwa.social_login.facebook',
        'name'   => 'pwa::app.admin.system.facebook',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'FACEBOOK_CLIENT_ID',
                'title'         => 'pwa::app.admin.system.facebook-client-id',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'FACEBOOK_CLIENT_SECRET',
                'title'         => 'pwa::app.admin.system.facebook-client-secret',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'FACEBOOK_CALLBACK_URL',
                'title'         => 'pwa::app.admin.system.facebook-callback-url',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'pwa.social_login.twitter',
        'name'   => 'pwa::app.admin.system.twitter',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'TWITTER_CLIENT_ID',
                'title'         => 'pwa::app.admin.system.twitter-client-id',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'TWITTER_CLIENT_SECRET',
                'title'         => 'pwa::app.admin.system.twitter-client-secret',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'TWITTER_CALLBACK_URL',
                'title'         => 'pwa::app.admin.system.twitter-callback-url',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'pwa.social_login.google',
        'name'   => 'pwa::app.admin.system.google',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'GOOGLE_CLIENT_ID',
                'title'         => 'pwa::app.admin.system.google-client-id',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'GOOGLE_CLIENT_SECRET',
                'title'         => 'pwa::app.admin.system.google-client-secret',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'GOOGLE_CALLBACK_URL',
                'title'         => 'pwa::app.admin.system.google-callback-url',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'pwa.social_login.linkedin',
        'name'   => 'pwa::app.admin.system.linkedin',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'LINKEDIN_CLIENT_ID',
                'title'         => 'pwa::app.admin.system.linkedin-client-id',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'LINKEDIN_CLIENT_SECRET',
                'title'         => 'pwa::app.admin.system.linkedin-client-secret',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'LINKEDIN_CALLBACK_URL',
                'title'         => 'pwa::app.admin.system.linkedin-callback-url',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'pwa.social_login.github',
        'name'   => 'pwa::app.admin.system.github',
        'sort'   => 5,
        'fields' => [
            [
                'name'          => 'GITHUB_CLIENT_ID',
                'title'         => 'pwa::app.admin.system.github-client-id',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'GITHUB_CLIENT_SECRET',
                'title'         => 'pwa::app.admin.system.github-client-secret',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'GITHUB_CALLBACK_URL',
                'title'         => 'pwa::app.admin.system.github-callback-url',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],        
    ]

];