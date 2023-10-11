<?php

return [
    'admin' => [
        'system' => [
            'pwa'                                   => 'PWA',
            'status'                                => 'Status',
            'btn-save'                              => 'Zapisz',
            'name'                                  => 'Nazwa',
            'topic'                                 => 'Temat',
            'media'                                 => 'Media',
            'seo-author'                            => 'Autor SEO',
            'settings'                              => 'Ustawienia',
            'general'                               => 'Ogólne',
            'short-name'                            => 'Krótka nazwa',
            'theme-color'                           => 'Kolor motywu',
            'add_in_pwa'                            => 'Dodaj do PWA',
            'messagingId'                           => 'ID wiadomości',
            'api-key'                               => 'Klucz API',
            'auth-domain'                           => 'Domena uwierzytelniania',
            'database-url'                          => 'Adres URL bazy danych',
            'project-id'                            => 'ID projektu',
            'app-id'                                => 'ID aplikacji',
            'public-vapid-key'                      => 'Publiczny klucz VAPID',
            'server-key'                            => 'Klucz serwera',
            'enable_slider'                         => 'Włącz suwak',
            'background-color'                      => 'Kolor tła',
            'info'                                  => 'Uwaga: Użyj kodu koloru szesnastkowego',
            'small'                                 => 'Ikona aplikacji (48x48)',
            'medium'                                => 'Ikona aplikacji (96x96)',
            'push-notification'                     => 'Powiadomienie push',
            'large'                                 => 'Ikona aplikacji (144x144)',
            'extra-large'                           => 'Ikona aplikacji (196x196)',
            'pwa_full_name'                         => 'Progressive Web App',
            'enable_new'                            => 'Włącz nowe produkty',
            'enable_featured'                       => 'Włącz polecane produkty',
            'enable_categories_home_page_listing'   => 'Włącz listę kategorii na stronie głównej',
            'redirect_to_pwa_if_mobile'             => 'Przekieruj użytkownika do PWA, jeśli korzysta z urządzenia mobilnego',
        ],

        'layouts' => [
            'index'             => 'Układ PWA',
            'push-notification' => 'Powiadomienie push'
        ],

        'push-notification' => [
            'icon'                  => 'Ikona',
            'label-title'           => 'Tytuł',
            'target-url'            => 'Adres URL docelowy',
            'description'           => 'Opis',
            'notification'          => 'Powiadomienie',
            'title-create'          => 'Dodaj powiadomienie',
            'title-edit'            => 'Edytuj powiadomienie',
            'edit-notification'     => 'Edytuj powiadomienie push',
            'title'                 => 'Lista powiadomień push',
            'create-notification'   => 'Utwórz powiadomienie push',
            'success-notification'  => 'Sukces: Powiadomienie push zostało pomyślnie wysłane.'
        ],

        'datagrid' => [
            'id'          => 'ID',
            'title'       => 'Tytuł',
            'delete'      => 'Usuń',
            'view'        => 'Wyświetl',
            'send'        => 'Wyślij',
            'target-url'  => 'Adres URL docelowy',
            'description' => 'Opis',
        ]
    ],

    'shop'  => [
        'home'  => [
            'enable-pwa-status' => 'Ostrzeżenie: Włącz status rozszerzenia PWA w konfiguracji.',
        ]
    ]
];
