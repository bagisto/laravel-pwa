<?php

return [
    'admin' => [
        'system' => [
            'pwa'                                   => 'PWA',
            'status'                                => 'Status',
            'btn-save'                              => 'Speichern',
            'name'                                  => 'Name',
            'topic'                                 => 'Thema',
            'media'                                 => 'Medien',
            'seo-author'                            => 'Autor',
            'settings'                              => 'Einstellungen',
            'general'                               => 'Allgemein',
            'short-name'                            => 'Kurzname',
            'theme-color'                           => 'Farbschema',
            'add_in_pwa'                            => 'In PWA hinzufügen',
            'messagingId'                           => 'Messaging-ID',
            'api-key'                               => 'API-Schlüssel',
            'auth-domain'                           => 'AUTH-DOMAIN',
            'database-url'                          => 'Datenbank-URL',
            'project-id'                            => 'Projekt-ID',
            'app-id'                                => 'App-ID',
            'public-vapid-key'                      => 'Öffentlicher VAPID-Schlüssel',
            'server-key'                            => 'Server-Schlüssel',
            'enable_slider'                         => 'Slider aktivieren',
            'background-color'                      => 'Hintergrundfarbe',
            'info'                                  => 'Hinweis: Verwenden Sie hexadezimale Farbcodes',
            'small'                                 => 'App-Symbol (48x48)',
            'medium'                                => 'App-Symbol (96x96)',
            'push-notification'                     => 'Push-Benachrichtigung',
            'large'                                 => 'App-Symbol (144x144)',
            'extra-large'                           => 'App-Symbol (196x196)',
            'pwa_full_name'                         => 'Progressive Web App',
            'enable_new'                            => 'Neue Produkte aktivieren',
            'enable_featured'                       => 'Hervorgehobene Produkte aktivieren',
            'enable_categories_home_page_listing'   => 'Kategorien-Listung auf der Startseite aktivieren',
            'redirect_to_pwa_if_mobile'             => 'Benutzer auf PWA umleiten, wenn ein Mobilgerät verwendet wird',
        ],

        'layouts' => [
            'index'             => 'PWA-Layout',
            'push-notification' => 'Push-Benachrichtigung'
        ],

        'push-notification' => [
            'icon'                  => 'Symbol',
            'label-title'           => 'Titel',
            'target-url'            => 'Ziel-URL',
            'description'           => 'Beschreibung',
            'notification'          => 'Benachrichtigung',
            'title-create'          => 'Benachrichtigung hinzufügen',
            'title-edit'            => 'Benachrichtigung bearbeiten',
            'edit-notification'     => 'Push-Benachrichtigung bearbeiten',
            'title'                 => 'Liste der Push-Benachrichtigungen',
            'create-notification'   => 'Push-Benachrichtigung erstellen',
            'success-notification'  => 'Erfolg: Push-Benachrichtigung erfolgreich gesendet.'
        ],

        'datagrid' => [
            'id'          => 'ID',
            'title'       => 'Titel',
            'delete'      => 'Löschen',
            'view'        => 'Anzeigen',
            'send'        => 'Senden',
            'target-url'  => 'Ziel-URL',
            'description' => 'Beschreibung',
        ]
    ],

    'shop'  => [
        'home'  => [
            'enable-pwa-status' => 'Warnung: Aktivieren Sie den PWA-Erweiterungsstatus in der Konfiguration.',
        ]
    ]
];
