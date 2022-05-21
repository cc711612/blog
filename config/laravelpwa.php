<?php

return [
    'name'     => env("APP_NAME", "Roy's Blog"),
    'manifest' => [
        'name'             => env("APP_NAME", "RBlog"),
        'short_name'       => 'Blog',
        'start_url'        => '/',
        'background_color' => '#ffffff',
        'theme_color'      => '#000000',
        'display'          => 'standalone',
        'orientation'      => 'any',
        'status_bar'       => 'black',
        'icons'            => [
            '72x72'   => [
                'path'    => '/images/icons/blog-icon-72x72.png',
                'purpose' => 'any',
            ],
            '96x96'   => [
                'path'    => '/images/icons/blog-icon-96x96.png',
                'purpose' => 'any',
            ],
            '128x128' => [
                'path'    => '/images/icons/blog-icon-128x128.png',
                'purpose' => 'any',
            ],
            '144x144' => [
                'path'    => '/images/icons/blog-icon-144x144.png',
                'purpose' => 'any',
            ],
            '152x152' => [
                'path'    => '/images/icons/blog-icon-152x152.png',
                'purpose' => 'any',
            ],
            '192x192' => [
                'path'    => '/images/icons/blog-icon-192x192.png',
                'purpose' => 'any',
            ],
            '384x384' => [
                'path'    => '/images/icons/blog-icon-384x384.png',
                'purpose' => 'any',
            ],
            '512x512' => [
                'path'    => '/images/icons/blog-icon-512x512.png',
                'purpose' => 'any',
            ],
        ],
        'splash'           => [
            '640x1136'  => '/images/icons/blog-splash-640x1136.png',
            '750x1334'  => '/images/icons/blog-splash-750x1334.png',
            '828x1792'  => '/images/icons/blog-splash-828x1792.png',
            '1125x2436' => '/images/icons/blog-splash-1125x2436.png',
            '1242x2208' => '/images/icons/blog-splash-1242x2208.png',
            '1242x2688' => '/images/icons/blog-splash-1242x2688.png',
            '1536x2048' => '/images/icons/blog-splash-1536x2048.png',
            '1668x2224' => '/images/icons/blog-splash-1668x2224.png',
            '1668x2388' => '/images/icons/blog-splash-1668x2388.png',
            '2048x2732' => '/images/icons/blog-splash-2048x2732.png',
        ],
        'shortcuts'        => [
            [
                'name'        => 'Login',
                'description' => 'Shortcut Login',
                'url'         => '/login',
                'icons' => [
                    "src" => "/images/icons/blog-icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name'        => 'Create Post',
                'description' => 'Shortcut Create Post',
                'url'         => '/article/create',
                'icons' => [
                    "src" => "/images/icons/blog-icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
        ],
        'custom'           => [],
    ],
];
