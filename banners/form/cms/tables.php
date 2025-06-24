<?php
return [
    'tables' => [
        'cms_currencies'            => 'cms_currencies',
        'cms_languages'             => 'cms_languages',
        'cms_hooks'                 => 'cms_hooks',
        'cms_modules'               => 'cms_modules',

        // =============== GRUPY USERS ===================
        'user_groups'               => 'user_groups',
        'user_groups_lang'          => 'user_groups_lang',

        // =============== STATUTY ZAMÓWIEŃ ==============
        'statuses'                  => 'statuses',
        'statuses_lang'             => 'statuses_lang',
        
        // =============== KATEGORIE PRODUKTÓW ===========
        'categories'                => 'categories',
        'categories_lang'           => 'categories_lang',

        // =============== ATRYBUTY PRODUKTÓW ============ 
        'attributes'                => 'attributes',
        'attributes_lang'           => 'attributes_lang',
        'attribute_values'          => 'attribute_values',
        'attribute_values_lang'     => 'attribute_values_lang',

        // =============== PRODUKTY i KOMBINACJE =========
        'products'                  => 'products',
        'products_lang'             => 'products_lang',
        'product_variants'          => 'products_variants',

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

    'default_currencies' => [
        ['active' => 1, 'code' => 'EUR', 'name' => 'Euro', 'unit' => 2, 'symbol' => '€'],
        ['active' => 1, 'code' => 'PLN', 'name' => 'Złoty', 'unit' => 2, 'symbol' => 'zł'],
        ['active' => 0, 'code' => 'USD', 'name' => 'US Dollars', 'unit' => 2, 'symbol' => '$'],
    ],

    'default_languages' => [
        ['active' => 1, 'code' => 'en', 'name' => 'English', 'iso' => 'EN', 'currency' => 1, 'flag' => 'en.png'],
        ['active' => 1, 'code' => 'pl', 'name' => 'Polski', 'iso' => 'PL', 'currency' => 2, 'flag' => 'pl.png'],
        ['active' => 0, 'code' => 'es', 'name' => 'Español', 'iso' => 'ES', 'currency' => 1, 'flag' => 'es.png'],
        ['active' => 0, 'code' => 'fr', 'name' => 'Français', 'iso' => 'FR', 'currency' => 1, 'flag' => 'fr.png'],
    ],

    'default_categories' => [
        ['active' => 1, 'name' => 'main', 'slug' => 'main', 'langs' => [
            ['lang' => 1, 'title' => 'Main', 'description' => 'Basic product'],
            ['lang' => 2, 'title' => 'Głowny', 'description' => 'Produkt główny']
            ]
        ],
        ['active' => 1, 'name' => 'equipment', 'slug' => 'equipment', 'langs' => [
                ['lang' => 1, 'title' => 'Equipment', 'description' => 'Additional equipment'],
                ['lang' => 2, 'title' => 'Wyposażenie', 'description' => 'Dodatkowe wyposażenie']
            ]
        ],
        ['active' => 1, 'name' => 'accessories', 'slug' => 'accessories', 'langs' => [
                ['lang' => 1, 'title' => 'Accessories', 'description' => 'Accessories'],
                ['lang' => 2, 'title' => 'Akcesoria', 'description' => 'Akcesoria']
            ]
        ],
        ['active' => 1, 'name' => 'fashion', 'slug' => 'fashion', 'langs' => [
                ['lang' => 1, 'title' => 'Fashion', 'description' => 'Clothing and fashion'],
                ['lang' => 2, 'title' => 'Moda', 'description' => 'Ubrania i moda']
            ]
        ]
    ],

    'default_attributes' => [
        ['active' => 1, 'name' => 'color', 'slug' => 'color', 'priority' => 0, 'langs' => [
            ['lang' => 1, 'title' => 'Color', 'description' => 'Color product'],
            ['lang' => 2, 'title' => 'Kolor', 'description' => 'Kolor produktu']
            ]
        ],
        ['active' => 1, 'name' => 'size', 'slug' => 'size', 'priority' => 1, 'langs' => [
                ['lang' => 1, 'title' => 'Size', 'description' => 'Product size'],
                ['lang' => 2, 'title' => 'Rozmiar', 'description' => 'Rozmiar produktu']
            ]
        ],
        ['active' => 1, 'name' => 'load', 'slug' => 'load', 'priority' => 2, 'langs' => [
                ['lang' => 1, 'title' => 'Max load', 'description' => 'Max load'],
                ['lang' => 2, 'title' => 'Maks. obciążenie', 'description' => 'Maksymalne dopuszczalne obciążenie']
            ]
        ],
        ['active' => 1, 'name' => 'capacity', 'slug' => 'capacity', 'priority' => 3, 'langs' => [
                ['lang' => 1, 'title' => 'Capacity', 'description' => 'Capacity'],
                ['lang' => 2, 'title' => 'Pojemność', 'description' => 'Pojmność']
            ]
        ]
    ],

    'default_attribute_values' => [
        'color' => [
            [
                'value_key' => 'red',
                'priority' => 0,
                'hex_code' => '#FF0000',
                'extra_info' => 'Energetic and bold color',
                'langs' => [
                    ['lang_id' => 1, 'title' => 'Red'],
                    ['lang_id' => 2, 'title' => 'Czerwony']
                ]
            ],
            [
                'value_key' => 'blue',
                'priority' => 1,
                'hex_code' => '#0000FF',
                'extra_info' => 'Cool and calming tone',
                'langs' => [
                    ['lang_id' => 1, 'title' => 'Blue'],
                    ['lang_id' => 2, 'title' => 'Niebieski']
                ]
            ]
        ],
        'size' => [
            [
                'value_key' => 'S',
                'priority' => 0,
                'hex_code' => null,
                'extra_info' => 'Small size, recommended for kids',
                'langs' => [
                    ['lang_id' => 1, 'title' => 'Small'],
                    ['lang_id' => 2, 'title' => 'Mały']
                ]
            ],
            [
                'value_key' => 'L',
                'priority' => 1,
                'hex_code' => null,
                'extra_info' => 'Large size, suitable for adults',
                'langs' => [
                    ['lang_id' => 1, 'title' => 'Large'],
                    ['lang_id' => 2, 'title' => 'Duży']
                ]
            ]
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

    'default_statutes' => [
        [
            'active' => 1,
            'name' => 'draft',
            'color' => 'gray',
            'priority' => 0,
            'is_final' => 0,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Draft',
                    'description' => 'Draft version'
                ],
                [
                    'lang' => 2,
                    'title' => 'Roboczy',
                    'description' => 'Wersja robocza'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'pending',
            'color' => 'yellow',
            'priority' => 1,
            'is_final' => 0,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Pending',
                    'description' => 'Waiting for payment'
                ],
                [
                    'lang' => 2,
                    'title' => 'Oczekujący',
                    'description' => 'Oczekuje na płatność'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'paid',
            'color' => 'blue',
            'priority' => 2,
            'is_final' => 0,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Paid',
                    'description' => 'Paid order'
                ],
                [
                    'lang' => 2,
                    'title' => 'Opłacone',
                    'description' => 'Opłacone zamówienie'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'processing',
            'color' => 'orange',
            'priority' => 3,
            'is_final' => 0,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Processing',
                    'description' => 'In progress'
                ],
                [
                    'lang' => 2,
                    'title' => 'W realizacji',
                    'description' => 'W toku realizacji'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'shipped',
            'color' => '#00bfff',
            'priority' => 4,
            'is_final' => 0,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Shipped',
                    'description' => 'Shipped'
                ],
                [
                    'lang' => 2,
                    'title' => 'Wysłane',
                    'description' => 'Wysłane zamówienie'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'delivered',
            'color' => 'green',
            'priority' => 5,
            'is_final' => 1,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Delivered',
                    'description' => 'Delivered'
                ],
                [
                    'lang' => 2,
                    'title' => 'Dostarczone',
                    'description' => 'Dostarczone zamówienie'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'cancelled',
            'color' => 'red',
            'priority' => 6,
            'is_final' => 1,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Cancelled',
                    'description' => 'Cancelled'
                ],
                [
                    'lang' => 2,
                    'title' => 'Anulowane',
                    'description' => 'Anulowane zamówienie'
                ]
            ]
        ],
        [
            'active' => 1,
            'name' => 'refunded',
            'color' => 'purple',
            'priority' => 7,
            'is_final' => 1,
            'langs' => [
                [
                    'lang' => 1,
                    'title' => 'Refunded',
                    'description' => 'Refunded'
                ],
                [
                    'lang' => 2,
                    'title' => 'Zwrot',
                    'description' => 'Zwrot środków'
                ]
            ]
        ],
    ],

    'default_hooks' => [
        // Sidebar hooks
        [
            'active' => 1,
            'location' => 'FO',
            'title' => 'displaySidebarBefore',
            'description' => 'Hooks for displaying content Before SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'title' => 'displaySidebarTop',
            'description' => 'Hooks for displaying content on Top SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'title' => 'displaySidebarContent',
            'description' => 'Hooks for displaying content on SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'title' => 'displaySidebarBottom',
            'description' => 'Hooks for displaying content on Bottom SideBar',
        ],
        [
            'active' => 1,
            'location' => 'FO',
            'title' => 'displaySidebarAfter',
            'description' => 'Hooks for displaying content After SideBar',
        ],

        
    ]
];
