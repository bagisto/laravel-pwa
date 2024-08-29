<?php

return [
    'admin' => [
        'system'            => [
            'pwa'                                   => 'PWA',
            'status'                                => 'Estado',
            'btn-save'                              => 'Guardar',
            'name'                                  => 'Nombre',
            'topic'                                 => 'Tema',
            'media'                                 => 'Medios',
            'seo-author'                            => 'Autor',
            'settings'                              => 'Configuraciones',
            'general'                               => 'General',
            'short-name'                            => 'Nombre Corto',
            'theme-color'                           => 'Color del Tema',
            'add_in_pwa'                            => 'Añadir en PWA',
            'messagingId'                           => 'ID de Mensajería',
            'api-key'                               => 'Clave API',
            'auth-domain'                           => 'DOMINIO DE AUTENTICACIÓN',
            'database-url'                          => 'URL de la Base de Datos',
            'project-id'                            => 'ID de Proyecto',
            'app-id'                                => 'ID de Aplicación',
            'public-vapid-key'                      => 'Clave Pública VAPID',
            'server-key'                            => 'Clave del Servidor',
            'enable_slider'                         => 'Habilitar Slider',
            'background-color'                      => 'Color de Fondo',
            'info'                                  => 'Nota: Use código de color hexadecimal',
            'small'                                 => 'Icono de la Aplicación (48x48)',
            'medium'                                => 'Icono de la Aplicación (96x96)',
            'push-notification'                     => 'Notificación Push',
            'large'                                 => 'Icono de la Aplicación (144x144)',
            'extra-large'                           => 'Icono de la Aplicación (196x196)',
            'pwa_full_name'                         => 'Aplicación Web Progresiva',
            'enable_new'                            => 'Habilitar productos nuevos',
            'enable_featured'                       => 'Habilitar productos destacados',
            'enable_categories_home_page_listing'   => 'Habilitar listado de categorías en la página de inicio',
            'redirect_to_pwa_if_mobile'             => 'Redirigir al usuario a PWA si está usando un dispositivo móvil',
        ],

        'layouts'           => [
            'index'             => 'Diseño de PWA',
            'push-notification' => 'Notificación Push',
        ],

        'push-notification' => [
            'icon'                  => 'Icono',
            'label-title'           => 'Título',
            'target-url'            => 'URL de Destino',
            'description'           => 'Descripción',
            'notification'          => 'Notificación',
            'title-create'          => 'Agregar Notificación',
            'title-edit'            => 'Modificar Notificación',
            'edit-notification'     => 'Editar Notificación Push',
            'title'                 => 'Lista de Notificaciones Push',
            'create-notification'   => 'Crear Notificación Push',
            'success-notification'  => 'Éxito: Notificación Push enviada exitosamente.',
        ],

        'datagrid'          => [
            'id'            => 'ID',
            'title'         => 'Título',
            'delete'        => 'Eliminar',
            'view'          => 'Ver',
            'send'          => 'Enviar',
            'target-url'    => 'URL de Destino',
            'description'   => 'Descripción',
        ],

        'notification'      => [
            'update-success' => 'Notificación Push actualizada correctamente.',
            'delete-success' => 'Notificación Push eliminada correctamente.',
        ],
    ],

    'shop'  => [
        'home'  => [
            'enable-pwa-status' => 'Advertencia: Por favor, habilite el estado de la extensión PWA desde la configuración.',
        ],
    ],
];
