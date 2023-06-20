<?php

return [
    'admin' => [
        'system' => [
            'pwa'                                   => 'PWA',
            'status'                                => 'ステータス',
            'btn-save'                              => '保存',
            'name'                                  => '名前',
            'topic'                                 => 'トピック',
            'media'                                 => 'メディア',
            'seo-author'                            => 'SEO著者',
            'settings'                              => '設定',
            'general'                               => '一般',
            'short-name'                            => '短縮名',
            'theme-color'                           => 'テーマカラー',
            'add_in_pwa'                            => 'PWAに追加',
            'messagingId'                           => 'メッセージングID',
            'api-key'                               => 'APIキー',
            'auth-domain'                           => '認証ドメイン',
            'database-url'                          => 'データベースURL',
            'project-id'                            => 'プロジェクトID',
            'app-id'                                => 'アプリID',
            'public-vapid-key'                      => '公開VAPIDキー',
            'server-key'                            => 'サーバーキー',
            'enable_slider'                         => 'スライダーを有効にする',
            'background-color'                      => '背景色',
            'info'                                  => '注意：16進数のカラーコードを使用してください',
            'small'                                 => 'アプリアイコン（48x48）',
            'medium'                                => 'アプリアイコン（96x96）',
            'push-notification'                     => 'プッシュ通知',
            'large'                                 => 'アプリアイコン（144x144）',
            'extra-large'                           => 'アプリアイコン（196x196）',
            'pwa_full_name'                         => 'プログレッシブウェブアプリ',
            'enable_new'                            => '新しい商品を有効にする',
            'enable_featured'                       => '注目商品を有効にする',
            'enable_categories_home_page_listing'   => 'カテゴリホームページリストを有効にする',
            'redirect_to_pwa_if_mobile'             => 'モバイルデバイスを使用している場合はPWAにリダイレクトする',
        ],

        'layouts' => [
            'index'             => 'PWAレイアウト',
            'push-notification' => 'プッシュ通知'
        ],

        'push-notification' => [
            'icon'                  => 'アイコン',
            'label-title'           => 'タイトル',
            'target-url'            => 'ターゲットURL',
            'description'           => '説明',
            'notification'          => '通知',
            'title-create'          => '通知を追加',
            'title-edit'            => '通知を編集',
            'edit-notification'     => 'プッシュ通知を編集',
            'title'                 => 'プッシュ通知リスト',
            'create-notification'   => 'プッシュ通知を作成',
            'success-notification'  => '成功：プッシュ通知が正常に送信されました。'
        ],

        'datagrid' => [
            'id'          => 'ID',
            'title'       => 'タイトル',
            'delete'      => '削除',
            'view'        => '表示',
            'send'        => '送信',
            'target-url'  => 'ターゲットURL',
            'description' => '説明',
        ]
    ],

    'shop'  => [
        'home'  => [
            'enable-pwa-status' => '警告：設定からPWA拡張機能のステータスを有効にしてください。',
        ]
    ]
];
