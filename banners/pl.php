<?php
return [
    'title' => 'Zamówienia - Hussaria Electra',
    'header' => 'Formularz zamówienia',
    'form' => [
        'person' => [
            'title' => 'Dane personalne',
            'fields' => [
                'first_name' => 'Imię / Nazwa firmy',
                'last_name' => 'Nazwisko / Numer NIP',
                'email' => 'E-mail',
                'phone' => 'Telefon',
            ],
        ],
        'address' => [
            'title' => 'Adres do faktury',
            'fields' => [
                'street' => 'Ulica i numer',
                'post_code' => 'Kod pocztowy',
                'city' => 'Miejscowość',
                'country' => 'Kraj',
            ],
        ],
        'shipping' => [
            'title' => 'Adres do wysyłki',
            'fields' => [
                'checkbox' => 'Inny adres',
                'street' => 'Ulica i numer',
                'post_code' => 'Kod pocztowy',
                'city' => 'Miejscowość',
                'country' => 'Kraj',
            ],
        ],
        'order' => [
            'title' => 'Zamówienie',
        ],
        'prepayment' => [
            'active' => 1,
            'title' => 'Płatność',
            'fields' => [
                'checkbox' => 'Przedpłata',
                'amount' => 'Kwota przedpłaty',
                'cash' => [
                    'active' => 1,
                    'mode' => 'Gotówka/Płatność kartą',
                    'timing' => 0,
                    'status' => 'accept',
                ],
                'wire' => [
                    'active' => 1,
                    'mode' => 'Przelew',
                    'timing' => 14,
                    'status' => 'wait',
                ],
            ],
        ],
        'order_summary' => [
            'value' => 'Wartość zamówienia',
            'cost' => 'Koszt dostawy',
            'amount' => 'Kwota do zapłaty',
            'button' => 'Zamawiam',
            'back' => 'Powrót',
            'confirm' => 'Dziękujemy za zamówienie!',
        ],
    ],
    'settings' => [
        'currency' => 'PLN',
        'discount' => [
            'name' => 'Rabat',
            'value' => 400,
        ],
        'fees' => [
            'name' => 'Opłaty' ,
            'value' => 50,
        ]
    ],
    //===============
    'package' => [
        'active' => 1,
        'name' => 'Elektryczna paka',
        'show_price' => 1,
        'price' => 24477.00,
        'promo' => 20000,
        'status' => 'W magazynie',
        'desc' => 'Pionierskie rozwiązanie na rynku jeździeckim, które wprowadza napęd elektryczny i nową jakość w transporcie sprzętu.',
        'weight' => [
            'name' => 'Waga (kg)',
            'value' => 84
        ],
        'height' => [
            'name' => 'Wysokość (cm)',
            'value' => 150
        ],
        'width' => [
            'name' => 'Szerokość (cm)',
            'value' => 66
        ],
        'shipping' => [
            'active' => 1,
            'free' => 0,
            'price' => 300,
            'promo' => 100,
            'class' => '',
            'content' => 'Dostawa GRATIS',
        ],
        'include' => [
            'products' => [
                'battery' => [
                    'active' => 1,
                    'name' => 'Akumulator',
                    'desc' => 'Pojemność: 4 Ah',
                    'image' => '',
                ],
                'charger' => [
                    'active' => 1,
                    'name' => 'Ładowarka',
                    'desc' => 'Do akumulatora',
                    'image' => '',
                ],
                'pomp' => [
                    'active' => 1,
                    'name' => 'Pompka elektryczna',
                    'desc' => 'Bezprzewodową z funkcją powerbanku',
                    'image' => '',
                ],
                'lock' => [
                    'active' => 1,
                    'name' => 'Ochronne zapięcie',
                    'desc' => 'Zewnętrzne na szyfr',
                    'image' => '',
                ],
                'custom' => [
                    'active' => 1,
                    'name' => 'Personalizacja paki',
                    'desc' => 'Dowolny kolor i styl',
                    'image' => '',
                ],
            ],
            'widgets' => [
                'bag' => [
                    'active' => 1,
                    'name' => 'Torba',
                    'desc' => 'Materiałowa torba zakupowa',
                    'image' => '',
                ],
                'cables' => [
                    'active' => 1,
                    'name' => 'Zestaw kabli',
                    'desc' => 'Zestaw kabli do ładowania telefonu 3w1',
                    'image' => '',
                ],
                'hat' => [
                    'active' => 1,
                    'name' => 'Czapka',
                    'desc' => 'Stylowa czapka z daszkiem',
                    'image' => '',
                ],
                'scissors' => [
                    'active' => 1,
                    'name' => 'Nożyczki',
                    'desc' => 'Nożyczki taktyczne z zestawem śrubokrętów',
                    'image' => '',
                ],
                'cup' => [
                    'active' => 1,
                    'name' => 'Kubek termiczny',
                    'desc' => 'Kubek termiczny typu Stanley z możliwością personalizacją',
                    'image' => '',
                ],
                'pharmacy' => [
                    'active' => 1,
                    'name' => 'Apteczka',
                    'desc' => 'Przybornik medyczny',
                    'image' => '',
                ],
                'board' => [
                    'active' => 1,
                    'name' => 'Tablica',
                    'desc' => 'Magnetyczna tablica suchościeralna z kompletem akcesoriów',
                    'image' => '',
                ],
            ],

        ]
    ],
    // ==============
    'products' => [
        'tack_cover' => [
            'active' => 1,
            'name' => 'Pokrowiec na elektryczną pakę',
            'show_price' => 1,
            'price' => 2078.70,
            'shipping' => [
                'active' => 1,
                'free' => 0,
                'price' => 190,
                'class' => 'warning',
                'content' => 'Dostawa',
            ],
        ],
        'tack_customization' => [
            'active' => 1,
            'name' => 'Personalizacja',
            'show_price' => 1,
            'price' => 615.00,
            'shipping' => [
                'active' => 0,
                'free' => 1,
                'price' => 0,
                'class' => '',
                'content' => 'Dostawa',
            ],
            'form_label' => 'Dodatkowe uwagi',
        ],
        'battery' => [
            'active' => 1,
            'name' => 'Akumulator 40V 4Ah',
            'show_price' => 1,
            'price' => 971.70,
            'shipping' => [
                'active' => 0,
                'free' => 1,
                'price' => 0,
                'class' => 'success',
                'content' => '0',
            ],
        ],
        'charger' => [
            'active' => 1,
            'name' => 'Ładowarka 40V 4A',
            'show_price' => 1,
            'price' => 553.50,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 200,
                'class' => 'dark',
                'content' => 'Dostawa: 0 PLN',
            ],
        ],
        'ramp' => [
            'active' => 1,
            'name' => 'Trap załadunkowy',
            'show_price' => 1,
            'price' => 1476.00,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 200,
                'class' => '',
                'content' => 'Dostawa: 0 PLN',
            ],
        ],
        'ramp_cover' => [
            'active' => 1,
            'name' => 'Pokrowiec na trap załadunkowy',
            'show_price' => 1,
            'price' => 479.70,
            'shipping' => [
                'active' => 0,
                'free' => 0,
                'price' => 200,
                'class' => '',
                'content' => '',
            ],
        ],
    ],
];