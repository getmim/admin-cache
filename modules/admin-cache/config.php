<?php

return [
    '__name' => 'admin-cache',
    '__version' => '0.0.2',
    '__git' => 'git@github.com:getmim/admin-cache.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-cache' => ['install','update','remove'],
        'theme/admin/cache'   => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'AdminCache\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-cache/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminCacheIndex' => [
                'path' => [
                    'value' => '/cache'
                ],
                'handler' => 'AdminCache\\Controller\\Cache::index'
            ],
            'adminCacheData' => [
                'path' => [
                    'value' => '/cache/data'
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminCache\\Controller\\Cache::data'
            ],
            'adminCacheOutput' => [
                'path' => [
                    'value' => '/cache/output'
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminCache\\Controller\\Cache::output'
            ]
        ]
    ],
    'adminSetting' => [
        'menus' => [
            'admin-cache' => [
                'label' => 'Caches',
                'icon'  => '<i class="fas fa-memory"></i>',
                'info'  => 'Cleanup system caches',
                'perm'  => 'cleanup_caches',
                'index' => 1000,
                'options' => [
                    'admin-cache' => [
                        'label' => 'Cleanup Now',
                        'route' => ['adminCacheIndex']
                    ]
                ]
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.cache.blank' => []
        ]
    ]
];