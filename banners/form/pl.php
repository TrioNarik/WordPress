<?php
return [
    'settings' => [
        'theme' => 'Zmień motyw',
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

    'title' => 'Zamówienia - Hussaria Electra',
    'header' => 'Formularz zamówienia',
    'form' => [
        'colors' => [
            'title' => 'Kolor skrzyni',
            'fields' => [
                'label' => 'Kolor skrzyni',
                'help' => 'Wybierz kolor folii',
                'info' => 'Dostępna paleta kolorów folii do zmiany koloru paki',
            ],
        ],
        'marker' => [
            'title' => 'Oznakowanie',
            'fields' => [
                'label' => 'Oznakowanie',
                'help' => 'Personalizacja',
                'info' => 'Dostępne opcje personalizacji paki',
                'holder' => 'Jan Kowalski'
            ],
            'options' => [
                'left' => 'Lewa',
                'middle' => 'Środek',
                'right' => 'Prawa'
            ]
        ],
        'flag' => [
            'title' => 'Flaga kraju',
            'fields' => [
                'label' => 'Flaga kraju',
                'help' => 'Dołączyć flagę kraju',
                'info' => '',
            ],
        ],
        // ========================

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
            'price' => 358,
            'promo' => '',
            'class' => 'dark',
            'content' => 'Dostawa GRATIS',
        ],
        'include' => [
            'intro_products' => 'W zestawie',
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
                'pomp' => [
                    'active' => 1,
                    'name' => 'Pompka elektryczna',
                    'desc' => 'z funkcją Powerbanku',
                    'image' => 'pomp.png',
                ],
                'pharmacy' => [
                    'active' => 1,
                    'name' => 'Apteczka',
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
                    'name' => 'PowerBank',
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
            

        ]
    ],
    // ==============
    'extra' => 'Przejdź na wersję 2.2 🚀 Uzyskaj lepszą wydajność',
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
                'price' => 22,
                'class' => '',
                'content' => 'Dodstawa od',
            ],
        ],
        'charger' => [
            'active' => 1,
            'name' => 'Ładowarka do akumulatora',
            'desc' => '',
            'parameters' => [
                'name' => '',
                'value' => '',
            ],
            'status' => 'W magazynie',
            'delivery' => 'Możliwa natychmiastowa wysyłka',
            'image' => 'charger_4_5ah.png',
            'show_price' => 1,
            'price' => 565.20,
            'shipping' => [
                'active' => 1,
                'free' => 0,
                'price' => 22,
                'class' => '',
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
                'free' => 0,
                'price' => 75,
                'class' => '',
                'content' => 'Dodstawa od',
            ],
        ],
        
    ],
];