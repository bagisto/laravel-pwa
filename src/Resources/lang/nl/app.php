<?php

return [
    'admin' => [
        'system'            => [
            'pwa'                                   => 'PWA',
            'status'                                => 'Status',
            'btn-save'                              => 'Opslaan',
            'name'                                  => 'Naam',
            'topic'                                 => 'Onderwerp',
            'media'                                 => 'Media',
            'seo-author'                            => 'Auteur',
            'settings'                              => 'Instellingen',
            'general'                               => 'Algemeen',
            'short-name'                            => 'Korte naam',
            'theme-color'                           => 'Themakleur',
            'add_in_pwa'                            => 'Toevoegen in PWA',
            'messagingId'                           => 'Berichten-ID',
            'api-key'                               => 'API-sleutel',
            'auth-domain'                           => 'AUTH-DOMEIN',
            'database-url'                          => 'DATABASE-URL',
            'project-id'                            => 'Project-ID',
            'app-id'                                => 'App-ID',
            'public-vapid-key'                      => 'Openbare VAPID-sleutel',
            'server-key'                            => 'Server-sleutel',
            'enable_slider'                         => 'Schuifregelaar inschakelen',
            'background-color'                      => 'Achtergrondkleur',
            'info'                                  => 'Opmerking: Gebruik hexadecimale kleurcode',
            'small'                                 => 'App-pictogram (48x48)',
            'medium'                                => 'App-pictogram (96x96)',
            'push-notification'                     => 'Pushmelding',
            'large'                                 => 'App-pictogram (144x144)',
            'extra-large'                           => 'App-pictogram (196x196)',
            'pwa_full_name'                         => 'Progressieve web-app',
            'enable_new'                            => 'Nieuwe producten inschakelen',
            'enable_featured'                       => 'Uitgelichte producten inschakelen',
            'enable_categories_home_page_listing'   => 'Categorieën homepage-vermelding inschakelen',
            'redirect_to_pwa_if_mobile'             => 'Gebruiker doorverwijzen naar PWA bij gebruik van mobiel apparaat',
        ],

        'layouts'           => [
            'index'             => 'PWA-indeling',
            'push-notification' => 'Pushmelding',
        ],

        'push-notification' => [
            'icon'                  => 'Pictogram',
            'label-title'           => 'Titel',
            'target-url'            => 'Doel-URL',
            'description'           => 'Beschrijving',
            'notification'          => 'Melding',
            'title-create'          => 'Melding toevoegen',
            'title-edit'            => 'Melding wijzigen',
            'edit-notification'     => 'Pushmelding bewerken',
            'title'                 => 'Lijst met pushmeldingen',
            'create-notification'   => 'Pushmelding maken',
            'success-notification'  => 'Succes: Pushmelding succesvol verzonden.',
        ],

        'datagrid'          => [
            'id'            => 'ID',
            'title'         => 'Titel',
            'delete'        => 'Verwijderen',
            'view'          => 'Bekijken',
            'send'          => 'Verzenden',
            'target-url'    => 'Doel-URL',
            'description'   => 'Beschrijving',
        ],

        'notification'      => [
            'update-success' => 'Pushmelding succesvol bijgewerkt.',
            'delete-success' => 'Pushmelding succesvol verwijderd.',
        ],
    ],

    'shop'  => [
        'home'  => [
            'enable-pwa-status' => 'Waarschuwing: Schakel alstublieft de PWA-extensiestatus in vanuit de configuratie.',
        ],
    ],
];
