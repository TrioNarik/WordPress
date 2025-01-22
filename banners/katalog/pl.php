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
            'notification' => 'Produkt został dodany!',
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
        'desc' => 'Doskonałe rozwiązanie do przechowywania siodeł i sprzętu jeździeckiego dla wymagających zawodników i pasjonatów jeździectwa. Wyposażona w duże koła i napęd elektryczny eliminuje wysiłek fizyczny i zapewnia łatwość w manewrowaniu.',
        'votes' => 'ocen',
        'status' => 'W magazynie',
        'deadline' => 'Gdy towaru nie ma na magazynie, termin realizacji wydłuża się do 3-4 tygodni',
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
        'load' => [
            'name' => 'Maks. obciążenie (kg)',
            'value' => 100
        ],
        'speed' => [
            'name' => 'Maks. prędkość (km/h)',
            'value' => 4.2
        ],
        'colors' => [
            'name' => 'Kolor',
            'palette' => [
                'black' => [
                    'name' => 'Czarny',
                    'ral' => 'RAL 9005',
                    'code' => '#151515',
                ] 
            ]            
        ],
        'materials' => [
            'name' => 'Użyte materiały',
            'value' => 'Aluminium, malowane proszkowo'
        ],
        'equipment' => [
            'name' => 'Wyposażenie',
            'value' => '5 wieszaków na ogłowia z wysuwanymi prowadnicami'
        ],
        'wheels' => [
            'name' => 'Rodzaj kół',
            'value' => '2 stałe, 2 skrętne z hamulcem'
        ],
        'led' => [
            'name' => 'Podświetlenie LED',
            'value' => 'TAK'
        ],
        'drive' => [
            'name' => 'Napęd elektryczny',
            'value' => 'TAK'
        ],

        'show_price' => 1,
        'price' => 24477.00,
        'promo' => 20000,
        'shipping' => [
            'active' => 1,
            'free' => 1,
            'price' => 300,
            'promo' => '',
            'class' => 'dark',
            'content' => 'Dostawa GRATIS',
        ],
        'include' => [
            'intro_products' => 'W zestawie otrzymujesz',
            'products' => [
                'battery' => [
                    'active' => 1,
                    'name' => 'Akumulator',
                    'desc' => 'Pojemność: 4 Ah',
                    'image' => 'battery.png',
                ],
                'charger' => [
                    'active' => 1,
                    'name' => 'Ładowarka',
                    'desc' => 'Do akumulatora',
                    'image' => 'charger.png',
                ],
                'custom' => [
                    'active' => 1,
                    'name' => 'Personalizacja paki',
                    'desc' => 'Dowolny kolor i styl',
                    'image' => 'custom.png',
                ],
                'pomp' => [
                    'active' => 1,
                    'name' => 'Bezprzewodowa pompka elektryczna',
                    'desc' => 'z funkcją Powerbanku',
                    'image' => 'pomp.png',
                ],
                'pharmacy' => [
                    'active' => 1,
                    'name' => 'Apteczka pierwszej pomocy',
                    'desc' => 'Przybornik medyczny',
                    'image' => 'pharmacy.png',
                ],
                'lock' => [
                    'active' => 1,
                    'name' => 'Kłódka na szyfr',
                    'desc' => 'Ochronne zapięcie',
                    'image' => 'lock.png',
                ],
                'powerbank' => [
                    'active' => 1,
                    'name' => 'PowerBank 24000 mAh',
                    'desc' => 'Z funkcją szybkiego ładowania',
                    'image' => 'powerbank.png',
                ],
                'cables' => [
                    'active' => 1,
                    'name' => 'Zestaw kabli',
                    'desc' => 'Zestaw kabli do ładowania telefonu 3w1',
                    'image' => 'cables.png',
                ],
                'gadgets' => [
                    'active' => 1,
                    'name' => 'Zestaw gadżetów jeździeckich',
                    'desc' => 'Materiałowa torba zakupowa, stylowa czapka z daszkiem, nożyczki taktyczne z zestawem śrubokrętów, kubek termiczny, magnetyczna tablica suchościeralna z kompletem akcesoriów',
                    'image' => 'gadgets.png',
                ],
            ],
            // ================= OFF ==========
            'intro_widgets' => 'Zestaw gadżetów',
            'widgets' => [
                'active' => 0,
                'bag' => [
                    'active' => 1,
                    'name' => 'Torba',
                    'desc' => 'Materiałowa torba zakupowa',
                    'image' => 'bag.png',
                ],
                'cables' => [
                    'active' => 1,
                    'name' => 'Zestaw kabli',
                    'desc' => 'Zestaw kabli do ładowania telefonu 3w1',
                    'image' => 'cables.png',
                ],
                'hat' => [
                    'active' => 1,
                    'name' => 'Czapka',
                    'desc' => 'Stylowa czapka z daszkiem',
                    'image' => 'hat.png',
                ],
                'scissors' => [
                    'active' => 1,
                    'name' => 'Nożyczki',
                    'desc' => 'Nożyczki taktyczne z zestawem śrubokrętów',
                    'image' => 'scissors.png',
                ],
                'cup' => [
                    'active' => 1,
                    'name' => 'Kubek termiczny',
                    'desc' => 'Kubek termiczny typu Stanley z możliwością personalizacją',
                    'image' => 'cup.png',
                ],
                'pharmacy' => [
                    'active' => 1,
                    'name' => 'Apteczka',
                    'desc' => 'Przybornik medyczny',
                    'image' => 'pharmacy.png',
                ],
                'board' => [
                    'active' => 1,
                    'name' => 'Tablica',
                    'desc' => 'Magnetyczna tablica suchościeralna z kompletem akcesoriów',
                    'image' => 'board.png',
                ],
            ],

        ]
    ],
    // ==============
    'products' => [
        'battery' => [
            'active' => 1,
            'name' => 'Akumulator',
            'desc' => '',
            'parameters' => [
                'name' => 'Pojemność',
                'value' => '4 Ah',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'battery_4ah.png',
            'show_price' => 1,
            'price' => 971.70,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
        'battery5h' => [
            'active' => 1,
            'name' => 'Akumulator',
            'desc' => 'Zapewnia większą moc i jeszcze dłuższy czas pracy',
            'parameters' => [
                'name' => 'Pojemność',
                'value' => '5 Ah',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'battery_5ah.png',
            'show_price' => 1,
            'price' => 971.70,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
        'charger' => [
            'active' => 1,
            'name' => 'Ładowarkę do akumulatora',
            'desc' => '',
            'parameters' => [
                'name' => '',
                'value' => '',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'charger_4_5ah.png',
            'show_price' => 1,
            'price' => 971.70,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
        'ramp' => [
            'active' => 1,
            'name' => 'Składany trap załadunkowy',
            'desc' => 'Wygodny w użyciu i łatwy do przechowywania, idealny do szybkiego załadunku i rozładunku. Po złożeniu zajmuje bardzo mało miejsca.',
            'parameters' => [
                'name' => 'Maks. obciążenie',
                'value' => '270 kg',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'ramp_270kg.png',
            'show_price' => 1,
            'price' => 1476.00,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
        // ===== OFF
        'tack_cover' => [
            'active' => 0,
            'name' => 'Pokrowiec',
            'desc' => '',
            'parameters' => [
                'name' => 'Pojemność',
                'value' => '4 Ah',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'ramp.png',
            'show_price' => 1,
            'price' => 1476.00,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
        'ramp_cover' => [
            'active' => 0,
            'name' => 'Pokrowiec na trap załadunkowy',
            'desc' => '',
            'parameters' => [
                'name' => 'Pojemność',
                'value' => '4 Ah',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'ramp.png',
            'show_price' => 1,
            'price' => 1476.00,
            'shipping' => [
                'active' => 1,
                'free' => 1,
                'price' => 18,
                'class' => 'success',
                'content' => 'Dodstawa od',
            ],
        ],
    ],
];