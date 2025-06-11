<?php
return [
    'tables' => [
        'cms_languages'        => 'cms_languages',
        'cms_user_groups'      => 'cms_user_groups',
        'cms_user_groups_lang' => 'cms_user_groups_lang',
        'cms_hooks'            => 'cms_hooks',
        'cms_modules'          => 'cms_modules',
        // =========================================
        'users'                => 'users',
        'user_address'         => 'user_address',
        'user_logs'            => 'user_logs',
        'user_reviews'         => 'user_reviews',
        'carts'                => 'carts',
        'orders'               => 'orders',
        'order_products'       => 'order_products',
        'configurations'       => 'configurations',
        'payment_methods'      => 'payment_methods',
        'payment_methods_lang' => 'payment_methods_lang',
        'payments'             => 'payments',
        'shipping_methods'     => 'shipping_methods',
        'shipping_methods_lang'=> 'shipping_methods_lang',
        'shippings'            => 'shippings',
        'coupons'              => 'coupons',
        'coupon_usage'         => 'coupon_usage',
    ],

    'default_languages' => [
        ['active' => 1, 'code' => 'en', 'name' => 'English', 'iso' => 'EN', 'currency' => 'EUR', 'flag' => 'en.png'],
        ['active' => 1, 'code' => 'pl', 'name' => 'Polski', 'iso' => 'PL', 'currency' => 'PLN', 'flag' => 'pl.png'],
    ],

    'default_languages' => [
        [
            'active' => 1,
            'code' => 'en',
            'name' => 'English',
            'iso' => 'EN',
            'currency' => 'EUR',
            'flag' => 'en.png'
        ],
        [
            'active' => 1,
            'code' => 'pl',
            'name' => 'Polski',
            'iso' => 'PL',
            'currency' => 'PLN',
            'flag' => 'pl.png'
        ]
    ],

    'default_groups' => [
        [
            'active' => 1,
            'name' => 'guest',
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Guest',
                    'description' => 'A user with limited access, typically read-only.'
                ],
                [
                    'lang' => 2,
                    'title' => 'Gość',
                    'description' => 'Użytkownik z ograniczonymi uprawnieniami, zazwyczaj tylko do odczytu.'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'client',
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Client',
                    'description' => 'A regular user with standard access rights.'
                ],
                [
                    'lang' => 2,
                    'title' => 'Klient',
                    'description' => 'Regularny użytkownik z standardowymi prawami dostępu.'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'distributor',
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Distributor',
                    'description' => 'A user with rights to distribute content or products.'
                ],
                [
                    'lang' => 2,
                    'title' => 'Dystrybutor',
                    'description' => 'Użytkownik z prawami do dystrybucji treści lub produktów.'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'manager',
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Manager',
                    'description' => 'A user with elevated rights to manage other users or content.'
                ],
                [
                    'lang' => 2,
                    'title' => 'Menadżer',
                    'description' => 'Użytkownik z podwyższonymi uprawnieniami do zarządzania innymi użytkownikami lub treściami.'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'administrator',
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Administrator',
                    'description' => 'A user with full access rights and control over the system.'
                ],
                [
                    'lang' => 2,
                    'title' => 'Administrator',
                    'description' => 'Użytkownik z pełnymi prawami dostępu i kontrolą nad systemem.'
                ]
            ]
        ]
    ],

    'default_hooks' => [
        // Sidebar hooks
        [
            'active' => 1,
            'location' => 'FO',
            'hook_name' => 'displaySidebarBefore',
            'description' => 'Hooks for displaying content Before SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'hook_name' => 'displaySidebarTop',
            'description' => 'Hooks for displaying content on Top SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'hook_name' => 'displaySidebarContent',
            'description' => 'Hooks for displaying content on SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'hook_name' => 'displaySidebarBottom',
            'description' => 'Hooks for displaying content on Bottom SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'hook_name' => 'displaySidebarAfter',
            'description' => 'Hooks for displaying content After SideBar',
        ],

        
    ]
];
