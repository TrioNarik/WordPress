<?php
session_start();

// Lang
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'pl';

$translate = include("lang/$lang.php");

// Config LANG'S
$config_lang = require 'config_languages.php';
$languages = $config_lang['languages'];


// Config Admin
$config = require 'config_admin.php';


// =========================================
// Funkcja do wczytywania i dekodowania JSON
// =========================================
function loadJsonFile($file_path, $default = []) {
    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    } else {
        return $default;
    }
}

$colors = loadJsonFile('colors.json', [
    ['name' => ['en' => 'Black'], 'color' => '#131516', 'ral' => 'RAL 9005']
]);

$fonts = loadJsonFile('fonts.json', [
    ['name' => 'Arial', 'rating' => 4, 'badge' => 'top', 'fontFamily' => 'Arial']
]);

$alignments = loadJsonFile('alignments.json', [
    ['name' => ['en' => 'Align left'], 'alignment' => 'left', 'panel' => 'top']
]);

// ======================================
// Funkcja do aktualizacji plików CSS/JPG
// ======================================
function version($file) {
    if (file_exists($file)) {
        return $file . '?v=3.' . filemtime($file);
    }
    return $file;
}

// ==============================
// Funkcja do pobrania SVG
// ==============================
function svg_code($svg_file) {
    if (file_exists($svg_file)) {
        echo file_get_contents($svg_file);
    }
}

// =============================
// Funkcja do formatowania ceny
// =============================
function formatPrice($price) {
    return number_format($price, 2, ',', ' '); // Formatowanie ceny z dwoma miejscami po przecinku i spacją jako separator tysięcy
}
function formatPriceWithSup($price) {
    $parts = explode('.', number_format($price, 2, '.', ''));
    if (count($parts) === 2) {
        return $parts[0] . ',<sup>' . $parts[1] . '</sup>';
    }
    return $parts[0] . ',<sup>00</sup>';
}



// ==========================================
// Funkcja do wyświetlania elementów Pakietu
// ==========================================
function displayIncludePack($items) {
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
            echo '<div class="d-flex justify-content-evenly align-items-center gap-2 my-1 my-md-3">';
            echo '<input type="checkbox" id="' . $itemKey .'" name="' . $itemKey .' " checked="checked" disable>';
            echo '<label for="' . $itemKey .'">';
            echo $item['name'];
            echo '</label>';
            if (!empty($item['image'])) {
                $imagePath = 'img/' . $item['image'];
                $versionedImagePath = version($imagePath);
                echo '<div class="include">';
                echo '<img class="img-fluid" src="'. htmlspecialchars($versionedImagePath) .'" alt="' . $item['name'] .'" />';
                echo '</div>';
            }

            echo '</div>';
        }
    }
}


function displayProductCheckbox($items, $currency) {
    echo '<div class="row">';
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {

            echo '<div class="col-12 col-md-4">';
            echo '<div class="featured m-2 my-md-5 p-2 p-md-4">';
            echo '<div class="d-flex justify-content-start align-items-center gap-4">';
            echo '<input type="checkbox" id="' . htmlspecialchars($itemKey) . '" name="' . htmlspecialchars($item['name']) . '" data-price="'. $item['price'] .'"  data-shipping="'. $item['shipping']['price'] .'">';
            echo '<label for="' . htmlspecialchars($itemKey) . '">' . htmlspecialchars($item['name']) . '</label>';
            echo '</div>';
            if (!empty($item['image'])) {

                $imagePath = 'img/' . $item['image'];
                $versionedImagePath = version($imagePath);

                echo '<div class="image d-flex justify-content-center align-items-center text-center py-2 py-md-5">';

                echo '<img class="img-fluid" src="' . htmlspecialchars($versionedImagePath) . '" alt="' . htmlspecialchars($item['name']) . '">';
                echo '</div>';
            }

            if ($item['show_price']) {
                echo '<div class="row parameters">';
                    echo '<div id="'. $itemKey .'" class="col-12 col-md-5 product-price">';
                        echo '<div class="block-price text-center p-2">';
                            echo '<span itemprop="price" content="'. formatPrice($item['price']) .'">'. formatPriceWithSup($item['price']) .'</span> ';
                            echo '<span itemprop="priceCurrency" content="'. $currency .'">'. $currency .'</span>';
                        echo '</div>';
                    echo '</div>';

                    if ($item['shipping']['active']) {
                    echo '<div id="'. $itemKey .'" class="col-12 col-md-7">';
                        echo '<div class="block-shipping text-end p-2">';
                            echo '<span>'. $item['shipping']['content'] .' '. formatPriceWithSup($item['shipping']['price']) .'</span>';
                        echo '</div>';
                    echo '</div>';
                    }

                echo '</div>';
                
                
                echo '</div>';
                echo '</div>';
            }


        }
    }
    echo '</div>';
}

?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translate['title'] ?></title>
    <link rel="icon" href="img/hussaria-electra-100x100.png" sizes="32x32">
    
    <meta name="description" content="✔ Hussaria Electra">
    <meta name="keywords" content="hussaria electra, elektryczna paka, ">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta property="og:title" content="<?php echo $translate['title'] ?>">
    <meta property="og:description" content="...">
    <meta property="og:image" content="https://electra.hussaria.pl/order_v2/img/elektryczna_paka.jpg">
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />
    <meta property="og:image:alt" content="><?php echo $translate['title'] ?>" />
    <meta property="og:url" content="https://electra.hussaria.pl/order/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Fonts for use -->
    <link rel="stylesheet" href="https://use.typekit.net/vfx4swx.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo version('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <nav class="navbar-default py-3">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 d-flex justify-content-end align-items-center gap-3">
                        <button class="btn contact"><?php echo $config['admin']['office_phone'] ?></button>

                        <ul class="d-flex justify-content-end align-items-center gap-1">
                            <?php foreach ($languages as $code => $langData): ?>
                                <li class="nav-item<?= ($lang == $code) ? ' active' : '' ?>">
                                    <?php if ($lang != $code): ?>
                                        <a class="nav-link" href="?lang=<?= $code ?>">
                                    <?php endif; ?>
                                        <img src="img/<?= $langData['img'] ?>" alt="<?= $langData['name'] ?>"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?= $langData['name'] ?>">
                                    <?php if ($lang != $code): ?>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <li class="modeTheme mx-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo$translate['settings']['theme'] ?>">
                                <img id="toggleTheme" src="<?php echo version('img/brightness.png'); ?>" alt="<?php echo$translate['settings']['theme'] ?>" />
                            </li>
                        </ul>

                    </div>
                </div>
            </section>
        </nav>
        
        <section class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-4 offset-md-4 d-flex justify-content-center">
                    <div class="logo-wrapper text-center py-2 py-md-5 my-2 my-md-5">
                        <a href="./">
                            <img id="logo" class="img-fluid logo" src="img/hussaria_electra_logo_white.png" alt="<?php echo $translate['title'] ?>">
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </header>   

    <form id="orderForm">
        <main>
            <section class="container-fluid model">

                <div class="row my-3">
                    <div class="col-12 col-md-6 title">
                        <div class="py-2">
                            <h2><?php echo $translate['header'] ?></h2>
                        </div>
                    </div>
                </div>

                <div class="row options my-md-5">
                    <div class="col-7 col-md-3 my-5">
                        
                        <div class="d-flex justify-content-between align-items-center gap-3 p-3">
                            <label for="favcolor">
                                <?php echo $translate['form']['colors']['fields']['label'] ?>:
                                <span class="info"><?php echo $translate['form']['colors']['fields']['help'] ?></span>
                            </label>
                            <input type="color" id="favcolor" class="pulsating-outline" name="color-picker"
                            data-price="<?php echo $translate['package']['price'] ?>"
                            data-promo="<?php if ($translate['package']['promo'] || $translate['package']['promo'] == 0) { ?><?php echo $translate['package']['promo'] ?><?php } ?>"
                            data-shipping="<?php echo $translate['package']['shipping']['price'] ?>"
                            data-delivery="<?php if ($translate['package']['shipping']['promo'] || $translate['package']['shipping']['promo'] === 0 || $translate['package']['shipping']['free']) { ?><?php echo $translate['package']['shipping']['promo'] ?><?php } ?>"
                            data-currency="<?php echo $translate['settings']['currency'] ?>"
                            list />
                    
                        </div>

                        
                        <div class="generator p-3">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <label for="marker">
                                    <?php echo $translate['form']['marker']['fields']['label'] ?>:
                                    <span class="info"><?php echo $translate['form']['marker']['fields']['help'] ?></span>
                                </label>
                                <input type="checkbox" id="marker" name="marker">
                            </div>

                            <div id="personal" class="p-3">
                                
                                <div class="form-group row panelOptions p-2 pt-md-4 rounded-top">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="personalizationText" placeholder="<?php echo $translate['form']['marker']['fields']['holder'] ?>..." maxlength="28">
                                    </div>
                                </div>

                                <div class="form-group row panelOptions p-2">
                                    <div class="col-4 col-md-3">    
                                        <div class="dropdown">
                                            <button id="dropdownButton" class="btn btn-colors dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <span id="selectedColorText" class="color-box"></span>
                                            </button>
                                        
                                            <ul class="colors dropdown-menu p-0" id="textColor">
                                                <?php
                                                foreach ($colors as $color) {
                                                    echo '<li style="background-color: ' . $color['color'] . ';">';
                                                    echo '<a class="dropdown-item d-flex align-items-center justify-content-between p-3 color-txt" data-color="'. $color['color'] .'" data-ral="'. $color['ral'] .'" href="#">';
                                                    echo '<span>' . $color['name'][$lang] . '</span>';
                                                    echo '<span class="ral">' . $color['ral'] . '</span>';
                                                    echo '</a></li>';
                                                } ?>
                                            </ul>
                                        </div>


                                            <style>
                                            .btn-colors,
                                            .btn-aligments {
                                                background: var(--grey-color);
                                            }

                                            .color-box {
                                                display: inline-block;
                                                width: 1.5em;
                                                height: 1.5em;
                                                border: 1px solid var(--main-color);
                                                border-radius: var(--radius);
                                                background-color: var(--main-color);
                                        }
                                            </style>

                                            <script>
                                            document.querySelectorAll('.color-txt').forEach(item => {
                                                item.addEventListener('click', function(event) {
                                                event.preventDefault();
                                                let color = this.getAttribute('data-color');
                                                document.getElementById('selectedColorText').style.backgroundColor = color;
                                                // document.getElementById('dropdownButton').textContent = this.textContent;
                                                });
                                            });
                                            </script>    

                                    </div>

                                    <div class="col-4 col-md-3">
                                        <div class="dropdown">
                                            <button id="dropdownButtonPosition" class="btn btn-aligments dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M1.992 4.25c0-.203.07-.375.211-.516a.741.741 0 0 1 .54-.234h8.507c.203 0 .375.078.516.234.156.141.234.313.234.516a.74.74 0 0 1-.234.54.701.701 0 0 1-.516.21H2.742a.782.782 0 0 1-.539-.21.782.782 0 0 1-.21-.54zm0 4.125c0-.203.07-.375.211-.516a.741.741 0 0 1 .54-.234h13.5c.218 0 .398.078.538.234.14.141.211.313.211.516s-.07.383-.21.54a.73.73 0 0 1-.54.21h-13.5a.782.782 0 0 1-.539-.21.782.782 0 0 1-.21-.54zm.75 3.375a.74.74 0 0 0-.539.234.701.701 0 0 0-.21.516c0 .203.07.383.21.54.156.14.336.21.54.21h16.5a.73.73 0 0 0 .538-.21.782.782 0 0 0 .211-.54.701.701 0 0 0-.21-.516.693.693 0 0 0-.54-.234h-16.5zm-.75 4.875c0-.203.07-.375.211-.516a.74.74 0 0 1 .54-.234h8.507c.203 0 .375.078.516.234.156.141.234.313.234.516a.741.741 0 0 1-.234.54.701.701 0 0 1-.516.21H2.742a.782.782 0 0 1-.539-.21.782.782 0 0 1-.21-.54zM2.742 20a.74.74 0 0 0-.539.234.701.701 0 0 0-.21.516c0 .203.07.383.21.54.156.14.336.21.54.21h18.515c.203 0 .375-.07.515-.21a.741.741 0 0 0 .235-.54.667.667 0 0 0-.235-.516.667.667 0 0 0-.515-.234H2.742z"></path></svg>
                                            </button>
                                        
                                            <ul class="aligments gap-2 p-3 dropdown-menu" id="textAlignt">
                                                <?php
                                                // Wyświetlenie SVG
                                                foreach ($alignments as $align) {
                                                    echo '<li><button type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $align['name'][$lang] .'" onclick="setAlignment(\''. $align['alignment'] .'\')">';
                                                    echo svg_code($align['svg']);
                                                    echo '</button>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-4 col-md-3">
                                        <div class="dropdown">
                                            <button id="dropdownButtonPosition" class="btn btn-aligments dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7.43 6.96a.809.809 0 0 0-.211-.257.658.658 0 0 0-.258-.164.443.443 0 0 0-.14-.023.386.386 0 0 0-.118-.024.443.443 0 0 0-.14.024.442.442 0 0 0-.141.023.878.878 0 0 0-.281.164.99.99 0 0 0-.188.258l-4.008 9.562a.704.704 0 0 0 0 .563.635.635 0 0 0 .422.375.704.704 0 0 0 .563 0 .795.795 0 0 0 .422-.422L4.219 15h4.968l.844 2.04a.807.807 0 0 0 .399.42.82.82 0 0 0 .61 0 .632.632 0 0 0 .398-.374.705.705 0 0 0 0-.563L7.43 6.961zm-.727 2.134L8.555 13.5H4.852l1.851-4.406zM18.047 6.96a.808.808 0 0 0-.211-.258.657.657 0 0 0-.258-.164.442.442 0 0 0-.14-.023.442.442 0 0 0-.141-.024.386.386 0 0 0-.117.024.442.442 0 0 0-.14.023.878.878 0 0 0-.282.164.99.99 0 0 0-.188.258l-4.008 9.562a.705.705 0 0 0 0 .563c.079.187.211.312.399.375a.76.76 0 0 0 .586 0 .796.796 0 0 0 .422-.422L14.813 15h4.968l.867 2.04a.807.807 0 0 0 .399.42.76.76 0 0 0 .586 0 .635.635 0 0 0 .422-.374.705.705 0 0 0 0-.563l-4.008-9.562zm1.101 6.539h-3.703l1.852-4.406 1.851 4.406z"></path></svg>
                                            </button>
                                        
                                            <ul class="aligments gap-2 p-3 dropdown-menu" id="textAlignt">
                                                <?php
                                                // Wyświetlenie SVG
                                                foreach ($alignments as $align) {
                                                    echo '<li><button type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $align['name'][$lang] .'" onclick="setAlignment(\''. $align['alignment'] .'\')">';
                                                    echo svg_code($align['svg']);
                                                    echo '</button>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row panelOptions p-2 rounded-bottom">
                                    
                                    <ul class="scrollable-list rounded">
                                        <?php
                                        // ==== Fonts ====
                                        foreach ($fonts as $index => $font) {
                                            echo '<li><canvas id="canvasfont_'.$index.'" 
                                            data-font="1.25em ' . htmlspecialchars($font['fontFamily']) . ', sans-serif"
                                            data-family="' . htmlspecialchars($font['fontFamily']) . '"
                                            data-name="' . htmlspecialchars($font["name"]) . '"
                                            data-rating="' . htmlspecialchars($font["rating"]) . '"
                                            data-badge="' . htmlspecialchars($font["badge"]) . '">
                                            </canvas></li>';
                        
                                        } ?>
                                    </ul>
                                    
                                </div>

                                        

                                    
                                

                                <div class="d-flex justify-content-start align-items-center gap-3">
                                    <label for="flag">
                                    <?php echo $translate['form']['flag']['fields']['label'] ?>:
                                    </label>
                                    <input type="checkbox" id="flag" name="flag">
                                </div>

                                <p class="info"><?php echo $translate['form']['flag']['fields']['help'] ?></p>
                                        
                            </div>
                        </div>
                        

                        


                    </div>

                    <div class="col-5 col-md-6">
                        <section id="image_paka" class="text-center">
                            <div class="shipping p-1 p-md-3">
                                <p><small><?php echo $translate['package']['shipping']['info'] ?></small></p>
                                <h5><?php echo $translate['package']['shipping']['content'] ?></h5>
                                <p class="source"><?php echo $translate['package']['shipping']['source'] ?></p>
                            </div>
                            <?php svg_code('paka.svg') ?>
                        </section>
                    </div>

                    <div class="col-12 col-md-3">
                        <section class="wall mx-1 mx-md-4 p-1 p-md-4">
                            <h4 class="neon text-center"><?php echo $translate['package']['include']['intro_products'] ?>:</h4>
                            <?php displayIncludePack($translate['package']['include']['products']); ?>
                        </section>
                    </div>

                </div>

            </section>

            <section class="container-fluid model extra py-3">
                <div class="container">
                <h4 class="neon"><?php echo $translate['extra'] ?>:</h4>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <?php displayProductCheckbox($translate['products'], $translate['settings']['currency']) ;?>
                </div>
                </div>
            </section>

            <section class="container">
                <div class="row text-center text-md-end">
                    
                    <p><?php echo $translate['form']['order_summary']['value'] ?>: <span id="totalPrice"></span> <?php echo $translate['settings']['currency'] ?></p>
                    <p><?php echo $translate['form']['order_summary']['cost'] ?>: <span id="totalShipping"></span> <?php echo $translate['settings']['currency'] ?></p>
                    <div class="col-12 order">
                        <div class="py-2">
                            <h4><?php echo $translate['form']['order_summary']['amount'] ?>: <span id="countPrice">20.250</span> <span><?php echo $translate['settings']['currency'] ?></span></h4>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="container py-2">
            
                
                    <h4><?php echo $translate['form']['person']['title'] ?></h4>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name"><?php echo $translate['form']['person']['fields']['first_name'] ?>:</label>
                                <input type="text" id="name" class="form-control" name="name" autocomplete="off" required>
                            </div> 
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="company"><?php echo $translate['form']['person']['fields']['last_name'] ?>:</label>
                                <input type="text" id="company" class="form-control" name="company" autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            
                            <div class="form-group">
                                <label for="email"><?php echo $translate['form']['person']['fields']['email'] ?>:</label>
                                <input type="text" id="email" class="form-control" name="email" autocomplete="off" minlength="3" maxlength="64" required>
                            </div>

                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="phone"><?php echo $translate['form']['person']['fields']['phone'] ?>:</label>
                                <input type="text" id="phone" class="form-control" name="phone" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <h4><?php echo $translate['form']['address']['title'] ?></h4>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="street"><?php echo $translate['form']['address']['fields']['street'] ?>:</label>
                                <input type="text" id="street" class="form-control" name="street" autocomplete="off">
                            </div> 
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="post"><?php echo $translate['form']['address']['fields']['post_code'] ?>:</label>
                                <input type="text" id="post" class="form-control" name="post" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="city"><?php echo $translate['form']['address']['fields']['city'] ?>:</label>
                                <input type="text" id="city" class="form-control" name="city" autocomplete="off">
                            </div> 
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="country"><?php echo $translate['form']['address']['fields']['country'] ?>:</label>
                                <input type="text" id="country" class="form-control" name="country" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 align-items-center">
                        <h4><?php echo $translate['form']['shipping']['title'] ?></h4>
                    
                        <label class="switch">
                            <input type="checkbox" id="differentShippingAddress" name="differentShippingAddress" value="1">
                            <span class="slider round"></span>
                        </label>
                        <span class="d-none d-md-block"> <?php echo $translate['form']['shipping']['fields']['checkbox'] ?>:</span> 
                    </div>

                    <div id="shippingAddressField">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="shippingStreet"><?php echo $translate['form']['shipping']['fields']['street'] ?>:</label>
                                    <input type="text" id="shippingStreet" class="form-control" name="shippingStreet" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="shippingPost"><?php echo $translate['form']['shipping']['fields']['post_code'] ?>:</label>
                                    <input type="text" id="shippingPost" class="form-control" name="shippingPost" autocomplete="off">
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="shippingCity"><?php echo $translate['form']['shipping']['fields']['city'] ?>:</label>
                                    <input type="text" id="shippingCity" class="form-control" name="shippingCity" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="shippingCountry"><?php echo $translate['form']['shipping']['fields']['country'] ?>:</label>
                                    <input type="text" id="shippingCountry" class="form-control" name="shippingCountry" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prepayment Block -->
                    <?php if ($translate['form']['prepayment']['active']): ?>
                    
                        <div class="mt-md-3 d-flex gap-3 align-items-center">
                            <h4 class="accent"><?php echo $translate['form']['prepayment']['fields']['checkbox'] ?></h4>
                        
                            <label class="switch">
                                <input type="checkbox" id="prepayment" name="prepayment" value="1">
                                <span class="slider round"></span>
                            </label>
                            <span class="d-none d-md-block"></span> 
                        </div>

                        <div id="prepaymentFields">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-start align-items-center gap-2 my-1 my-md-4">
                                            <input type="checkbox" id="wire" name="wire" checked="checked">
                                            <label for="wire"><?php echo $translate['form']['prepayment']['fields']['wire']['mode'] ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="prepaymentCount"><?php echo $translate['form']['prepayment']['fields']['amount'] ?>:</label>
                                        <input type="number" id="prepaymentCount" name="prepaymentCount" class="form-control" min="0" step="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                    <!-- end -->

                    <div class="row my-3 my-md-5">

                        <div class="col-12 col-md-9">
                            <div class="d-flex gap-3 align-items-center">
                                
                                <label for="accept">
                                    <input type="checkbox" id="accept" name="accept">       
                                </label>
                                <span class="d-block"><sup>*</sup><?php echo $translate['form']['order']['accept']['title'] ?> <a href="<?php echo $translate['form']['order']['accept']['link'] ?>"><?php echo $translate['form']['order']['accept']['name'] ?></a>.</span>
                                
                            </div>

                            <div class="d-flex gap-3 align-items-center">
                                
                                <label for="agree">
                                    <input type="checkbox" id="agree" name="agree">       
                                </label>
                                <span class="d-block"><sup>*</sup><?php echo $translate['form']['order']['agree']['title'] ?> <a href="<?php echo $translate['form']['order']['agree']['link'] ?>"><?php echo $translate['form']['order']['agree']['name'] ?></a> <?php echo $translate['form']['order']['agree']['shop'] ?>.</span>
                                
                            </div>
                        </div>

                        <div class="col-12 col-md-3 text-center text-md-end py-3">

                            <!-- Inputs -->
                            <input type="hidden" id="lang" name="lang" value="<?php echo $_SESSION['lang'] ?>"> 
                            <input type="hidden" id="human" name="human" value="">
                            <!-- end -->

                            <button class="btn send" id="send" type="submit"><?php echo $translate['form']['order_summary']['button'] ?></button>
                        </div>
                    </div>     
                
            

            
            <div class="notification" id="notification">
                <?php echo $translate['form']['order_summary']['notification'] ?>
            </div>

        </footer>
    </form>
        
    
    <script src="<?php echo version('js/theme.js'); ?>"></script>
    <!--  -->
    <script src="<?php echo version('js/functions.js'); ?>"></script>
    <!-- Fonts -->
    <script src="<?php echo version('js/fonts_canvas.js'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
    document.getElementById('favcolor').addEventListener('input', function() {
      var color = this.value;
      document.getElementById('front-panel').setAttribute('fill', color);
      document.getElementById('left-panel').setAttribute('fill', color);
      
      const handles = document.getElementsByClassName('handles');
      for (let i = 0; i < handles.length; i++) {
        handles[i].setAttribute('fill', color)
      }

      console.log(color);
    });


    document.addEventListener('DOMContentLoaded', function() {

            const buttons = document.querySelectorAll('#personal .btn');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    buttons.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            const markerCheckbox = document.getElementById('marker');
            const personalDiv = document.getElementById('personal');

            function togglePersonalDiv() {
                if (markerCheckbox.checked) {
                    // personalDiv.style.display = 'block';
                    personalDiv.classList.add('visible');
                } else {
                    // personalDiv.style.display = 'none';
                    personalDiv.classList.remove('visible');

                }
            }

            togglePersonalDiv();

            markerCheckbox.addEventListener('change', togglePersonalDiv);

            // Funkcja do zamykania #personal po kliknięciu poza blokiem
            document.addEventListener('click', function(event) {
                const isClickInside = personalDiv.contains(event.target);
                const isCheckboxClicked = markerCheckbox.contains(event.target);

                if (!isClickInside && !isCheckboxClicked) {
                    personalDiv.classList.remove('visible');
                    // Opcjonalnie: odznacz checkbox
                    // markerCheckbox.checked = false; 
                }
            });
        });



        // ==== Extra Products DIV
        document.addEventListener("DOMContentLoaded", function () {
            
            // let defaultProduct = document.querygetElementById("favcolor");

            updateTotals(); // Zaktualizuj sumy

            const notification = document.getElementById('notification');



            document.querySelectorAll(".featured").forEach(function (item) {
                item.addEventListener("click", function (e) {
                    let checkbox = this.querySelector("input[type='checkbox']");
                    
                    // Zapobiegamy dwukrotnemu kliknięciu, jeśli kliknięto w coś innego niż input
                    if (e.target.tagName !== "INPUT") {
                        checkbox.checked = !checkbox.checked;
                    }

                    // Dodajemy/Usuwamy klasę .selected
                    item.classList.toggle("selected", checkbox.checked);

                    // Jeśli checkbox jest zaznaczony, pokazujemy powiadomienie
                    if (checkbox.checked) {
                        notification.style.display = 'block';
                        setTimeout(function() {
                            notification.style.display = 'none';
                        }, 3000); // Po 3 sekundach
                    }

                    // Po kliknięciu aktualizujemy sumy
                    updateTotals();
                });
            });


                

            

        });
        // ===================================



        // Shipping address
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('differentShippingAddress');
            const shippingAddressField = document.getElementById('shippingAddressField');

            function toggleShippingAddressField() {
                if (checkbox.checked) {
                    shippingAddressField.style.display = 'block';
                } else {
                    shippingAddressField.style.display = 'none';
                }
            }

            checkbox.addEventListener('change', toggleShippingAddressField);

            // Initial check
            toggleShippingAddressField();
        });


        // Agree checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const acceptCheckbox = document.getElementById('accept');
            const agreeCheckbox = document.getElementById('agree');
            const sendButton = document.getElementById('send');

            function toggleSendButton() {
                if (acceptCheckbox.checked && agreeCheckbox.checked) {
                    sendButton.disabled = false;
                } else {
                    sendButton.disabled = true;
                }
            }

            acceptCheckbox.addEventListener('change', toggleSendButton);
            agreeCheckbox.addEventListener('change', toggleSendButton);

            // Initial check
            toggleSendButton();
        });
        



function updateTotals() {
    let totalPrice = 0;
    let maxShipping = 0;
    // ===
    // let totalShipping = 0;

    // ✅ Pobranie wartości domyślnego produktu z color-picker
    let defaultProduct = document.getElementById("favcolor");
        if (defaultProduct) {
            let defaultPrice = parseFloat(defaultProduct.dataset.price.replace(',', '.')) || 0;
            let defaultShipping = parseFloat(defaultProduct.dataset.shipping.replace(',', '.')) || 0;

            totalPrice += defaultPrice;
            maxShipping = Math.max(maxShipping, defaultShipping);
        }

    document.querySelectorAll('.featured input[type="checkbox"]:checked').forEach(input => {
        let price = parseFloat(input.dataset.price.replace(',', '.')) || 0;
        let shipping = parseFloat(input.dataset.shipping.replace(',', '.')) || 0;

        totalPrice += price;
        maxShipping = Math.max(maxShipping, shipping); // Wybieramy najwyższą wartość
        // ===
        // totalShipping += shipping;
    });

    // Aktualizacja wartości w HTML
    document.getElementById("totalPrice").textContent = totalPrice.toFixed(2).replace('.', ','); // Suma cen produktów
    // document.getElementById("totalShipping").textContent = maxShipping.toFixed(2).replace('.', ','); // Najwyższy koszt dostawy
    // document.getElementById("countPrice").textContent = formatPricePL(totalPrice + maxShipping); // Cena + najwyższy koszt dostawy
    document.getElementById("countPrice").textContent = formatPricePL(totalPrice); // Cena + najwyższy koszt dostawy
    // document.getElementById("countPrice").textContent = (totalPrice + totalShipping).toFixed(2).replace('.', ','); // Suma ceny + dostawy

}

function formatPricePL(number) {
    return number.toFixed(2) // Dwa miejsca po przecinku
            .replace('.', ',') // Zamiana kropki na przecinek
            .replace(/\B(?=(\d{3})+(?!\d))/g, ' '); // Spacja jako separator tysięcy
}

  </script>

<script>

    const svg = document.getElementById("svg_paka");

    // ===
    // Pobranie rzeczywistego rozmiaru na ekranie
    const width = svg.getBoundingClientRect().width;
    const height = svg.getBoundingClientRect().height;

    console.log(`Aktualny rozmiar SVG na ekranie: ${width}x${height}px`);

    // Pobranie wartości viewBox (definiuje układ współrzędnych)
    const viewBox = svg.viewBox.baseVal;
    console.log(`viewBox: x=${viewBox.x}, y=${viewBox.y}, width=${viewBox.width}, height=${viewBox.height}`);
    // ====

        const textInput = document.getElementById("personalizationText");
        const customText = document.getElementById("previewText");

        // Aktualizuje tekst na SVG w czasie rzeczywistym
        textInput.addEventListener("input", function() {
            customText.textContent = this.value; // Domyślny tekst jeśli pole puste
        });

        // Ustawia wyrównanie tekstu
        function setAlignment(align) {
            if (align === "left") {
                customText.setAttribute("text-anchor", "start");
                customText.setAttribute("x", "25"); // Lewa krawędź SVG
                customText.setAttribute("y", "18");
                customText.setAttribute("font-size", "2"); 
                customText.setAttribute("transform", "rotate(0.5 10 10) scale(1, 0.5)"); 
            } else if (align === "middle") {
                customText.setAttribute("text-anchor", "middle");
                customText.setAttribute("x", "52"); // Środek SVG
                customText.setAttribute("y", "120"); 
                customText.setAttribute("font-size", "4"); 
                customText.setAttribute("transform", "rotate(-0.8 10 10) scale(1, 0.5)"); 
            } else if (align === "right") {
                customText.setAttribute("text-anchor", "end");
                customText.setAttribute("x", "76"); // Prawa krawędź SVG
                customText.setAttribute("y", "18"); 
                customText.setAttribute("font-size", "2"); 
                customText.setAttribute("transform", "rotate(0.5 10 10) scale(1, 0.5)"); 
            }
        }
    </script>
  

</body>
</html>
