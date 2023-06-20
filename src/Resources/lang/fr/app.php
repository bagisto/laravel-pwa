<?php

return [
    'admin' => [
        'system' => [
            'pwa'                                   => 'PWA',
            'status'                                => 'Statut',
            'btn-save'                              => 'Enregistrer',
            'name'                                  => 'Nom',
            'topic'                                 => 'Sujet',
            'media'                                 => 'Médias',
            'seo-author'                            => 'Auteur',
            'settings'                              => 'Paramètres',
            'general'                               => 'Général',
            'short-name'                            => 'Nom court',
            'theme-color'                           => 'Couleur du thème',
            'add_in_pwa'                            => 'Ajouter dans PWA',
            'messagingId'                           => 'ID de messagerie',
            'api-key'                               => 'Clé API',
            'auth-domain'                           => 'Domaine d\'authentification',
            'database-url'                          => 'URL de la base de données',
            'project-id'                            => 'ID de projet',
            'app-id'                                => 'ID de l\'application',
            'public-vapid-key'                      => 'Clé publique VAPID',
            'server-key'                            => 'Clé du serveur',
            'enable_slider'                         => 'Activer le curseur',
            'background-color'                      => 'Couleur de fond',
            'info'                                  => 'Remarque : Utilisez un code couleur hexadécimal',
            'small'                                 => 'Icône de l\'application (48x48)',
            'medium'                                => 'Icône de l\'application (96x96)',
            'push-notification'                     => 'Notification push',
            'large'                                 => 'Icône de l\'application (144x144)',
            'extra-large'                           => 'Icône de l\'application (196x196)',
            'pwa_full_name'                         => 'Application Web Progressive',
            'enable_new'                            => 'Activer les nouveaux produits',
            'enable_featured'                       => 'Activer les produits en vedette',
            'enable_categories_home_page_listing'   => 'Activer la liste des catégories sur la page d\'accueil',
            'redirect_to_pwa_if_mobile'             => 'Rediriger l\'utilisateur vers PWA s\'il utilise un appareil mobile',
        ],

        'layouts' => [
            'index'             => 'Mise en page PWA',
            'push-notification' => 'Notification push'
        ],

        'push-notification' => [
            'icon'                  => 'Icône',
            'label-title'           => 'Titre',
            'target-url'            => 'URL de destination',
            'description'           => 'Description',
            'notification'          => 'Notification',
            'title-create'          => 'Ajouter une notification',
            'title-edit'            => 'Modifier la notification',
            'edit-notification'     => 'Modifier la notification push',
            'title'                 => 'Liste des notifications push',
            'create-notification'   => 'Créer une notification push',
            'success-notification'  => 'Succès : Notification push envoyée avec succès.'
        ],

        'datagrid' => [
            'id'          => 'ID',
            'title'       => 'Titre',
            'delete'      => 'Supprimer',
            'view'        => 'Voir',
            'send'        => 'Envoyer',
            'target-url'  => 'URL de destination',
            'description' => 'Description',
        ]
    ],
    
    'shop'  => [
        'home'  => [
            'enable-pwa-status' => 'Attention : Veuillez activer le statut de l\'extension PWA dans la configuration.',
        ]
    ]
];
