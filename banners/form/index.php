<?php
require_once 'include/init.php';
require_once 'include/functions.php';


// Ustawienie języka na podstawie GET lub sesji
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    // Domyślny język to polski, jeśli nie ustawiono
    $_SESSION['lang'] = $_SESSION['lang'] ?? 'pl'; 
}

$lang = $_SESSION['lang'];

// Pobranie pliku tłumaczeń
$lang_file = "lang/$lang.php";
$translate = safeInclude($lang_file);

// Pobranie konfiguracji języków
$config_lang_file = 'config/languages.php';
$config_lang = safeInclude($config_lang_file);
$languages = $config_lang['languages'] ?? [];

// Pobranie konfiguracji administratora
$config_file = 'config/admin.php';
$config = safeInclude($config_file);


// =========================================
// ========== JSON Dane ====================
// =========================================

// Step-by-Step Tour [przewodnik] => OFF
// $stepTours = loadJsonFile('step-tours.json');

// Wesje plików do Download
$downloads = loadJsonFile('downloads.json');
// Kolory PAKI
$PACKcolors = loadJsonFile('colors.json');
// Kolory tekstu
$font_colors = loadJsonFile('font-colors.json');
// Grawery => położenie i wyrównanie 
$grawer_positions = loadJsonFile('grawer-positions.json');
// Tekst => położenie i wyrównanie 
$text_positions = loadJsonFile('text-positions.json');
// Rozmiar tekstu
$sizes = loadJsonFile('font-sizes.json');
// Formatowanie tekstu
$styles = loadJsonFile('font-styles.json');
// Czcionki
$fonts = loadJsonFile('fonts.json');
// =========================================




// ==========================================
// Funkcja do wyświetlania elementów Pakietu
// ==========================================
function displayIncludePack($items) {
    if ($items) {
        echo '<ul id="gadgets-list" class="gadgets row row-cols-3 row-cols-sm-4 list-unstyled my-1 my-md-3">';
        foreach ($items as $itemKey => $item) {
            if ($item['active']) {
                echo '<li class="col-6 col-md-3 mb-4" data-id="'. $itemKey .'" data-name="'. $item['name'] .'">';
                
                  echo '<div class="gadget__image py-2 mb-2 text-center rounded">';
                    if (!empty($item['image'])) {
                        $imagePath = 'img/' . $item['image'];
                        $versionedImagePath = version($imagePath);
                        echo '<div class="thumbnail">';
                            echo '<img loading="lazy" decoding="async" class="img-fluid" src="'. htmlspecialchars($versionedImagePath) .'" alt="' . $item['name'] .'">';
                        echo '</div>';
                    }
                  echo '</div>';

                  echo '<div class="gadget__name d-flex align-items-center justify-content-center gap-1">';
                    echo '<span>';
                        echo svg_code('checked.svg');
                    echo '</span>';
                    echo $item['name'];
                  echo '</div>';

                echo '</li>';
            }
        }
        echo '</ul>';
    }
}


function displayProductCheckbox($items, $currency, $button) {
    echo '<div class="row">';
    foreach ($items as $itemKey => $item) {
        if ($item) {

            echo '<div class="col-12 col-md-4">';

                echo '<div class="featured m-2 p-2 p-md-4">';
                
                if (!empty($item['image'])) {

                    $imagePath = 'img/' . $item['image'];
                    $versionedImagePath = version($imagePath);

                    echo '<div class="image d-flex justify-content-center align-items-center text-center my-md-3">';

                    echo '<img loading="lazy" decoding="async" class="img-fluid" src="' . htmlspecialchars($versionedImagePath) . '" alt="' . htmlspecialchars($item['name']) . '">';
                    echo '</div>';
                }

                    echo '<h6 class="text-center">'. $item['name'].'</h6>';
                    echo '<div class="parameters d-flex justify-content-between align-items-center gap-3 py-2 my-4">';
                        echo '<span class="text-center">'. htmlspecialchars($item['parameters']['name']).'</span>';
                        echo '<span class="text-center">'. htmlspecialchars($item['parameters']['value']).'</span>';
                    echo '</div>';

                    echo '<div class="d-flex justify-content-between align-items-center">';
                        if ($item['show_price']) {
                            
                                echo '<div id="'. $itemKey .'" class="col-4 col-md-5 product-price">';
                                    echo '<div class="block-price text-center p-2">';
                                        echo '<span>'. formatPriceWithSup($item['price']) .'</span> ';
                                        echo '<span>'. htmlspecialchars($currency) .'</span>';
                                    echo '</div>';
                                echo '</div>';

                                if ($item['shipping']['active']) {
                                echo '<div id="'. $itemKey .'" class="col-8 col-md-7">';
                                    echo '<div class="block-shipping text-end p-2">';
                                        echo '<span>'. htmlspecialchars($item['shipping']['content']) .' '. formatPriceWithSup($item['shipping']['price']) .'</span>';
                                    echo '</div>';
                                echo '</div>';
                                }
                            
                        }

                        echo '<div class="d-flex justify-content-start align-items-center gap-4">';
                        if ($item['active']) {
                            echo '<button type="button" class="btn btn-warning addToCart" data-button-action="add-to-cart" data-key="' . htmlspecialchars($itemKey) . '" data-img="'. htmlspecialchars($item['image']) .'" data-name="'. htmlspecialchars($item['name']) .'" data-price="'. $item['price'] .'" data-shipping="'. $item['shipping']['price'] .'">';
                            echo svg_code('cart.svg');
                            echo '</button>';
                        } else {
                            echo '<button type="button" class="btn btn-outline-light disabled cart">';
                            echo svg_code('cart.svg');
                            echo '</button>';
                        }
                        echo '</div>';
                
                    echo '</div>';

            echo '</div>';
            echo '</div>';

        }
    }
    echo '</div>';
}



?>
<!DOCTYPE html>
<html lang="pl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translate['title'] ?></title>
    <link rel="icon" href="img/hussaria-electra-100x100.png" sizes="32x32">
    
    <meta name="description" content="<?php echo $translate['package']['desc'] ?>">
    <meta name="keywords" content="hussaria electra, elektryczna paka, paka z napędem elektrycznym">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta name="token" content="<?php echo $_SESSION['csrf_token']; ?>">

    <meta property="og:title" content="<?php echo $translate['title'] ?>">
    <meta property="og:description" content="<?php echo $translate['package']['desc'] ?>">
    <meta property="og:image" content="https://electra.hussaria.pl/order_v2/img/elektryczna_paka.jpg">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="500">
    <meta property="og:image:height" content="500">
    <meta property="og:image:alt" content="><?php echo $translate['title'] ?>">
    <meta property="og:url" content="https://electra.hussaria.pl/order/">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Fonts USED -->
    <link rel="stylesheet" href="https://use.typekit.net/vfx4swx.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo version('css/style.css') ?>">
</head>
<body>
    <header id="header" class="container-fluid fixed-top py-1">
        <div class="row">
            <!-- LOGO -->
            <div class="col-12 order-sm-1 col-md-3 order-md-1 d-flex align-items-center justify-content-between justify-content-md-start gap-1">
                <div class="logo-wrapper">
                    <a href="./"><img id="logo" class="logo" src="<?php echo version('img/hussaria_electra_logo_white.png') ?>" alt="<?php echo $translate['title'] ?>"></a>
                </div>
                <div class="info px-2">
                    <div class="contact d-flex align-items-center justify-content-start gap-1">
                        <?php 
                            echo svg_code('phone.svg');
                            echo "<span>" . $config['admin']['office_phone'] ."</span>";
                        ?>
                    </div>
                </div>
            </div>
            <!-- end -->

            <!-- Delivery -->
            <div class="col-12 order-sm-3 col-md-6 order-md-2 d-flex align-items-center justify-content-center delivery position-relative my-1 py-3">
                <div class="d-flex align-items-center justify-content-start gap-3">
                    <div class="delivery__content align-items-center justify-content-between gap-2">
                        <div class="delivery__content__headline"><?php echo $translate['package']['shipping']['content'] ?></div>
                        <div class="delivery__content__subheadline text-center px-2"><?php echo $translate['package']['shipping']['info'] ?></div>
                    </div>
                </div>
            </div>
            <!-- end -->

            <!-- Navigation -->
            <div class="col-12 order-sm-2 col-md-3 order-md-3 d-flex align-items-center justify-content-around justify-content-md-end gap-3">
                <!-- Download -->
                <div class="download mx-1">
                    <ul class="d-flex flex-wrap align-items-center justify-content-start gap-1">
                        <?php
                        foreach ($downloads as $download) {
                            echo '<li>';
                            echo '<a class="d-flex justify-content-between align-items-center gap-2" data-version="'. $download['version'] .'" href="?generate=' .$download['link'] .'&token='. $_SESSION['csrf_token'] .'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $download['name'][$lang] .'">';
                            echo '<span class="icon">';
                            echo svg_code($download['svg']);
                            echo '</span>';
                            echo '<span>' . $download['version'] . '</span>';
                            echo '</a></li>';
                        } ?>
                    </ul>
                </div>
                <!-- end -->

                <!-- Cart -->
                <div class="cart_button">
                    <button type="button" id="cart" class="btn btn-warning position-relative step" data-step="4" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" title="<?php echo $translate['form']['buttons']['view'] ?>">
                        <span>
                            <?php echo svg_code('cart.svg')?>
                            <sup id="button_cart_total" class="position-absolute top-0 start-100 translate-middle badge border border-light rounded-circle bg-danger"></sup>
                        </span>
                    </button>
                </div>
                <!-- end -->

                <!-- Languages -->
                 <nav>
                    <ul class="d-flex flex-wrap align-items-center justify-content-end gap-2">
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
                        <li class="mx-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Zmień motyw">
                                
                            </li>
                    </ul>
                 </nav>
                
                <button id="toggleTheme" type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="<?php echo $translate['settings']['theme'] ?>">
                    <img src="<?php echo version('img/brightness.png') ?>" alt="<?php echo $translate['settings']['theme'] ?>">
                </button>
                <!-- end -->
            </div>
            <!-- end -->            
        </div>
    </header>

    <main id="main" class="container-fluid">
        <div class="row configurator position-relative">
            <!-- MODEL -->
            <div class="col-12 configurator__model">
                <div id="paka" class="configurator__model__view text-center">
                    <?php updateSVGcodeWithTEXTitems(__DIR__ . '/svg/paka.svg', __DIR__ . '/json/text-positions.json', __DIR__ . '/json/grawer-positions.json', __DIR__ . '/json/flag-positions.json'); ?>
                </div>
            </div>

            <!-- OPTIONS -->
            <div class="col-12 col-md-4 col-lg-3 configurator__panel position-absolute z-1 top-0 start-0">
                <div class="configurator__panel__options p-1 p-md-3 my-1 my-md-3 step" data-step="1">
                    <!-- COLORS PACK -->
                    <section class="pack-colors d-flex flex-wrap align-items-center justify-content-between">
                        <div class="options_label">
                            <div class="d-flex align-items-center justify-content-start gap-3">
                                <div class="icons">
                                    <?php echo svg_code('palette.svg') ?>
                                </div>
                                <div class="option">
                                    <h4 class="fs-6"><?php echo $translate['form']['colors']['fields']['label'] ?>:</h4>
                                    <span class="help"><?php echo $translate['form']['colors']['fields']['help'] ?></span>
                                </div>
                            </div>
                        </div>

                        <?php
                            $defaultColor = null;
                            // Znajdź domyślny kolor z wszystkich kategorii
                            foreach ($PACKcolors as $categoryKey => $category) {
                                if (!empty($category['items'])) {
                                    foreach ($category['items'] as $color) {
                                        if (!empty($color['default']) && !empty($color['active'])) {
                                            $defaultColor = $color;
                                            break 2; // Wyjdź z obu pętli
                                        }
                                    }
                                }
                            }
                        ?>

                        <div class="dropdown">
                            <button class="btn btn-colors d-flex align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <span id="selectedColorPack" class="color-box me-1"
                                    <?php if ($defaultColor): ?>
                                        style="background-color: <?= htmlspecialchars($defaultColor['color'], ENT_QUOTES, 'UTF-8'); ?>;"
                                    <?php endif; ?>
                                ></span>
                            </button>

                            <ul class="colors dropdown-menu" id="packColor" data-option-type="select">
                                <?php
                                foreach ($PACKcolors as $categoryKey => $category) {
                                    if (!empty($category['items'])) {
                                        echo '<li class="dropdown-header">' . htmlspecialchars($category['category'][$lang], ENT_QUOTES, 'UTF-8') . '</li>';
                                        echo '<li>';
                                        echo '<div class="d-flex flex-wrap gap-2 px-3">';
                                        foreach ($category['items'] as $color) {
                                            $defaultAttr = isset($color['default']) && $color['default'] ? 1 : 0;

                                            echo '<div style="background-color: ' . htmlspecialchars($color['color'], ENT_QUOTES, 'UTF-8') . ';" class="picker">';
                                            echo '<a href="#" class="dropdown-item color-txt"
                                                    data-category="' . htmlspecialchars($categoryKey, ENT_QUOTES, 'UTF-8') . '"
                                                    data-default="' . $defaultAttr . '"
                                                    data-color="' . htmlspecialchars($color['color'], ENT_QUOTES, 'UTF-8') . '"
                                                    data-name="' . htmlspecialchars($color['name'][$lang], ENT_QUOTES, 'UTF-8') . '"
                                                    data-color-ral="' . htmlspecialchars($color['ral'], ENT_QUOTES, 'UTF-8') . '"
                                                    data-price="' . htmlspecialchars($color['price'][$lang], ENT_QUOTES, 'UTF-8') . '"
                                                    data-promo="' . htmlspecialchars($color['promo'][$lang], ENT_QUOTES, 'UTF-8') . '"
                                                    title="' . htmlspecialchars($color['name'][$lang], ENT_QUOTES, 'UTF-8') . '"></a>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </section>
                    <!-- end -->
                </div>

                <!-- BROKAT PACK  -->
                <div class="configurator__panel__options p-1 p-md-3 my-1 my-md-3 d-none">
                    
                    <section class="pack-colors d-flex flex-wrap align-items-center justify-content-between">
                        <div class="options_label">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div class="icons">
                                    <?php echo svg_code('glitter.svg') ?>
                                </div>
                                <div class="option">
                                    <h4 class="fs-6"><?php echo $translate['form']['glitter']['fields']['label'] ?>:</h4>
                                </div>
                            </div>                                
                        </div>

                        <div class="glitter">
                            <input type="checkbox" id="glitter" class="d-none" 
                            data-option-type="checkbox"
                            data-name="<?php echo $translate['form']['glitter']['title'] ?>"
                            data-price="<?php echo $translate['form']['glitter']['price']['value'] ?>"
                            data-promo="<?php echo $translate['form']['glitter']['price']['promo'] ?>">
                            <label for="glitter" class="switch"><span></span></label>   
                        </div>
                    </section>
                </div>
                <!-- end -->

                <div class="configurator__panel__options p-1 p-md-3 my-1 my-md-3 step" data-step="2">
                    <!-- TEXT CUSTOMIZATION -->
                        <section class="customization">
                            <div class="options_label">
                                <div class="d-flex flex-wrap align-items-start justify-content-between gap-1">
                                    <div class="d-flex align-items-center justify-content-start gap-3">
                                        <div class="icons">
                                            <?php echo svg_code('person.svg') ?>
                                        </div>
                                        <div class="option">
                                            <h4 class="fs-6"><?php echo $translate['form']['marker']['fields']['label'] ?>:</h4>
                                        </div>
                                    </div>
                                </div>       
                            </div>

                            <!-- Personalization Option -->
                            <!-- Grawer -->
                            <?php if($grawer_positions): ?>
                            <div class="text-positions d-flex flex-wrap align-items-center justify-content-between gap-1 my-2 p-2">
                                <div class="help personalization"><?php echo $translate['form']['marker']['options']['grawer']['title'] ?>:</div>

                                <ul class="d-flex flex-wrap align-items-center justify-content-end gap-1">
                                    <?php      
                                        foreach ($grawer_positions as $key => $value) {
                                            if ($value[0]['active'] == 1) {
                                                foreach ($value as $item) {
                                                    echo '<li><a href="#'. htmlspecialchars($key) .'" class="nav-tab" data-target="'. htmlspecialchars($key) .'">' . htmlspecialchars($item['title'][$lang]) . "</a></li>";
                                                }
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                            <?php endif ?>
                            <?php
                                foreach ($grawer_positions as $key => $value) {
                                    // Sprawdź, czy element jest aktywny
                                    if ($value[0]['active'] == 1) {
                                        echo '<div id="'. $key .'" class="positions-tabs">';
                                            // Text Input
                                            echo '<div class="form-group my-2 settings">';
                                            echo '<div class="input-group">';
                                                echo '<span class="input-group-text">';
                                                foreach ($value as $item) {
                                                    echo svg_code($item['icon']);
                                                }
                                                echo '</span>';
                                                echo '<input type="text" class="form-control" id="text_'. $key .'" data-option-type="text"';
                                                foreach ($value as $item) {
                                                    echo 'data-name="'. $translate['form']['marker']['options']['grawer']['title'] .': '. $item['title'][$lang] .'"';
                                                }
                                                echo 'data-price="'. $translate['form']['marker']['options']['grawer']['price']['value'].'"';
                                                echo 'data-promo="'. $translate['form']['marker']['options']['grawer']['price']['promo'].'"';
                                                echo 'placeholder="'. $translate['form']['marker']['fields']['holder'] .'..." maxlength="15">';
                                            echo '</div>';
                                            echo '</div>';

                                           
                                            // Text Size
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-size'] .':';
                                                echo '</div>';
                                                echo '<ul class="d-flex tools justify-content-start align-items-center gap-3" id="textSize_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-size'] .'">';
                                                    foreach ($sizes as $size) {
                                                        echo '<li><button type="button" class="tools"
                                                        data-size-name="'. $size['name'][$lang] .'"
                                                        data-size="'. $size['size'] .'"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $size['name'][$lang] .'">';
                                                        echo svg_code($size['svg']);
                                                        echo '</button>';
                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            // Text Style
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-style'] .':';
                                                echo '</div>';
                                                echo '<ul class="d-flex tools justify-content-start align-items-center gap-3" id="textStyle_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-style'] .'">';
                                                    foreach ($styles as $style) {
                                                        echo '<li><button type="button" class="tools" 
                                                        data-style-name="'. $style['name'][$lang] .'"
                                                        data-style="'. $style['style'] .'"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="'. $style['name'][$lang] .'">';
                                                        echo svg_code($style['svg']);
                                                        echo '</button>';
                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            // Text Fonts
                                            echo '<div class="d-flex align-items-start justify-content-start flex-column settings p-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-family'] .':';
                                                echo '</div>';
                                                echo '<ul class="scrollable-list rounded" id="fontFamily_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-family'] .'">';
                                                        foreach ($fonts as $index => $font) {
                                                            echo '<li><canvas id="canvasfont_'.$index.'_'. $key .'"
                                                            data-font="1.25em ' . htmlspecialchars($font['fontFamily']) . ', sans-serif"
                                                            data-family="' . htmlspecialchars($font['fontFamily']) . '"
                                                            data-name="' . htmlspecialchars($font["name"]) . '"
                                                            data-font-name="' . htmlspecialchars($font["name"]) . '"
                                                            data-rating="' . htmlspecialchars($font["rating"]) . '"
                                                            data-badge="' . htmlspecialchars($font["badge"]) . '">
                                                            </canvas></li>';
                                                        }
                                                echo '</ul>';
                                            echo '</div>';

                                        echo '</div>';
                                    }
                                }
                            ?>

                            <!-- Text -->
                            <?php if($text_positions): ?>
                            <div class="text-positions d-flex flex-wrap align-items-center justify-content-between gap-1 my-2 p-2">
                                <div class="help personalization"><?php echo $translate['form']['marker']['options']['texts']['title'] ?>:</div>

                                <ul class="d-flex flex-wrap align-items-center justify-content-end gap-1">
                                    <?php
                                    
                                        foreach ($text_positions as $key => $value) {
                                            if ($value[0]['active'] == 1) {
                                                foreach ($value as $item) {
                                                    echo '<li><a href="#'. htmlspecialchars($key) .'" class="nav-tab" data-target="'. htmlspecialchars($key) .'">' . htmlspecialchars($item['title'][$lang]) . "</a></li>";
                                                }
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                            <?php endif ?>

                            <!-- Personalization Settins -->
                            <?php
                                foreach ($text_positions as $key => $value) {
                                    // Sprawdź, czy element jest aktywny
                                    if ($value[0]['active'] == 1) {
                                        echo '<div id="'. $key .'" class="positions-tabs">';
                                            // Text Input
                                            echo '<div class="form-group my-2 settings">';
                                            echo '<div class="input-group">';
                                                echo '<span class="input-group-text">';
                                                foreach ($value as $item) {
                                                    echo svg_code($item['icon']);
                                                }
                                                echo '</span>';
                                                echo '<input type="text" class="form-control" id="text_'. $key .'" data-option-type="text"';
                                                foreach ($value as $item) {
                                                    echo 'data-name="'. $translate['form']['marker']['options']['texts']['title'] .': '. $item['title'][$lang] .'"';
                                                }
                                                echo 'data-price="'. $translate['form']['marker']['options']['texts']['price']['value'].'"';
                                                echo 'data-promo="'. $translate['form']['marker']['options']['texts']['price']['promo'].'"';
                                                echo 'placeholder="'. $translate['form']['marker']['fields']['holder'] .'..." maxlength="15">';
                                            echo '</div>';
                                            echo '</div>';

                                            // Text Color
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                            echo '<div class="fs-6">';
                                                echo $translate['form']['marker']['tools']['font-color'] . ':';
                                            echo '</div>';

                                            echo '<div class="dropdown">';
                                                echo '<button class="btn btn-colors d-flex align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown">';
                                                echo '<span id="selectedColorText_' . $key . '" class="color-box me-2" style="background-color:'.$value[0]['start_color'].'"></span>';
                                                echo '</button>';

                                                echo '<ul class="colors dropdown-menu" id="textColor_' . $key . '"data-name="'. $translate['form']['marker']['tools']['font-color'] .'"
                                                data-option-type="select">';

                                                // Pobieramy wartości z JSON
                                                $default_colors = isset($value[0]['default_colors']) ? $value[0]['default_colors'] : 0;
                                                $specific_colors = isset($value[0]['specific_colors']) ? $value[0]['specific_colors'] : [];

                                                // Jeśli `default_colors == 1`, dodajemy całą listę kolorów z `$font_colors`
                                                if ($default_colors == 1) {
                                                    foreach ($font_colors as $category) {
                                                        echo '<li class="dropdown-header">' . $category['category'][$lang] . '</li>';
                                                        echo '<li>';
                                                        echo '<div class="d-flex flex-wrap gap-2 px-3">';
                                                        foreach ($category['colors'] as $color) {
                                                            echo '<div style="background-color: ' . $color . ';" class="picker">';
                                                            echo '<a class="dropdown-item color-txt" data-color="' . $color . '" href="#" title="' . $color . '"></a>';
                                                            echo '</div>';
                                                        }
                                                        echo '</div>';
                                                        echo '</li>';
                                                    }
                                                }

                                                // Jeśli `specific_colors` ma kolory, dodajemy je osobno
                                                if (!empty($specific_colors)) {
                                                    echo '<li class="dropdown-header">' . $translate['form']['marker']['tools']['font-color'] . '</li>';
                                                    echo '<li>';
                                                    echo '<div class="d-flex flex-wrap gap-2 px-3">';
                                                    foreach ($specific_colors as $color) {
                                                        echo '<div style="background-color: ' . $color . ';" class="picker">';
                                                        echo '<a class="dropdown-item color-txt" data-color="' . $color . '" href="#" title="' . $color . '"></a>';
                                                        echo '</div>';
                                                    }
                                                    echo '</div>';
                                                    echo '</li>';
                                                }

                                                echo '</ul>';
                                            echo '</div>';
                                            echo '</div>';


                                            // Text Align
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-align'] .':';
                                                echo '</div>';

                                                echo '<ul class="d-flex tools justify-content-start align-items-center gap-3" id="textAlignt_'. $key .'" 
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-align'] .'">';

                                                    foreach ($value as $item) {
                                                        foreach ($item['align'] as $alignName => $alignData) {
                                                            echo '<li><button type="button" class="tools"
                                                             id="buttonText_'. $key .'_'. $alignName .'"
                                                             data-x="'. $alignData['x'] .'"
                                                             data-y="'. $alignData['y'] .'"
                                                             data-anchor="'. $alignData['anchor'] .'"
                                                             data-align-name="'. $alignData['name'][$lang] .'"
                                                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $alignData['name'][$lang] .'">';
                                                            echo svg_code($alignData['svg']);
                                                            echo '</button>';
                                                        }
                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            // Text Size
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-size'] .':';
                                                echo '</div>';
                                                echo '<ul class="d-flex tools justify-content-start align-items-center gap-3" id="textSize_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-size'] .'">';
                                                    foreach ($sizes as $size) {
                                                        echo '<li><button type="button" class="tools"
                                                        data-size-name="'. $size['name'][$lang] .'"
                                                        data-size="'. $size['size'] .'"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="'. $size['name'][$lang] .'">';
                                                        echo svg_code($size['svg']);
                                                        echo '</button>';
                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            // Text Style
                                            echo '<div class="d-flex align-items-center justify-content-between settings py-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-style'] .':';
                                                echo '</div>';
                                                echo '<ul class="d-flex tools justify-content-start align-items-center gap-3" id="textStyle_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-style'] .'">';
                                                    foreach ($styles as $style) {
                                                        echo '<li><button type="button" class="tools" 
                                                        data-style-name="'. $style['name'][$lang] .'"
                                                        data-style="'. $style['style'] .'"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="'. $style['name'][$lang] .'">';
                                                        echo svg_code($style['svg']);
                                                        echo '</button>';
                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            // Text Fonts
                                            echo '<div class="d-flex align-items-start justify-content-start flex-column settings p-1">';
                                                echo '<div class="fs-6">';
                                                    echo ''. $translate['form']['marker']['tools']['font-family'] .':';
                                                echo '</div>';
                                                echo '<ul class="scrollable-list rounded" id="fontFamily_'. $key .'"
                                                data-option-type="select"
                                                data-name="'. $translate['form']['marker']['tools']['font-family'] .'">';
                                                        foreach ($fonts as $index => $font) {
                                                            echo '<li><canvas id="canvasfont_'.$index.'_'. $key .'"
                                                            data-font="1.25em ' . htmlspecialchars($font['fontFamily']) . ', sans-serif"
                                                            data-family="' . htmlspecialchars($font['fontFamily']) . '"
                                                            data-name="' . htmlspecialchars($font["name"]) . '"
                                                            data-font-name="' . htmlspecialchars($font["name"]) . '"
                                                            data-rating="' . htmlspecialchars($font["rating"]) . '"
                                                            data-badge="' . htmlspecialchars($font["badge"]) . '">
                                                            </canvas></li>';
                                                        }
                                                echo '</ul>';
                                            echo '</div>';

                                        echo '</div>';
                                    }
                                }
                            ?>
                            <!-- end -->
                           
                        </section>
                    <!-- end -->
                </div>

                <div class="configurator__panel__options p-1 p-md-3 my-1 my-md-3">
                    <!-- FLAGS -->
                    <section class="flags d-flex flex-wrap align-items-center justify-content-between">
                            <div class="options_label">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="icons">
                                        <?php echo svg_code('flag.svg') ?>
                                    </div>
                                    <div class="option">
                                        <h4 class="fs-6"> <?php echo $translate['form']['marker']['flag']['fields']['label'] ?>:</h4>
                                    </div>
                                </div>                                
                            </div>

                            <div class="input-group w-50 d-none" id="flagInputGroup">
                                <span class="input-group-text">
                                    <?php echo '<img src="'. $_SESSION['flagUrl'] .'" alt="'. $_SESSION['country'] .'">' ?>
                                </span>
                                <input type="text" class="form-control" id="country_user" 
                                data-option-type="text"
                                data-name="<?php echo $translate['form']['marker']['flag']['fields']['holder'] ?>"
                                placeholder="<?php echo $translate['form']['marker']['flag']['fields']['holder'] ?>..." value="<?php echo $_SESSION['country'] ?>" maxlength="10">
                            </div>

                            <div class="checkbox">
                                <input type="checkbox" id="flag" class="d-none"
                                data-option-type="checkbox"
                                data-name="<?php echo $translate['form']['marker']['flag']['title'] ?>">
                                <label for="flag" class="switch"><span></span></label>   
                            </div>
                        </section>
                    <!-- end -->
                </div>

                <!-- SEND PDF -->
                <div class="options_label p-1 p-md-3 sendFiles">
                    <div class="d-flex align-items-center justify-content-start gap-3">
                        <div class="icons">
                            <?php echo svg_code('images.svg') ?>
                        </div>
                        <div class="option">
                            <div class="info"><?php echo $translate['form']['info']['send-pdf'] ?>: <span id="contact_Email"><?php echo $translate['form']['info']['send-mail'] ?></span></div>
                        </div>
                    </div>                                
                </div>
                
                <!-- SAVE BUTTON -->
                <div class="my-2">
                    <button class="btn btn-warning step" data-step="3" id="save-config" type="button" data-pack-name="<?php echo $translate['package']['name'] ?>" data-pack-price="<?php echo $translate['package']['price'] ?>" data-pack-promo="<?php echo $translate['package']['promo'] ?>" data-pack-delivery="<?php echo $translate['package']['shipping']['price'] ?>" data-pack-delivery-promo="<?php echo $translate['package']['shipping']['promo'] ?>" data-currency="<?php echo $translate['settings']['currency'] ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><?php echo $translate['form']['buttons']['save'] ?></button>
                </div>
                <!-- end -->
            </div>

            <!-- HELPER -->
            <div class="col-12 col-md-4 col-lg-3 configurator__helper position-absolute z-1 top-0 end-0 me-0 me-md-3 mt-0 mt-md-5 p-2 p-md-4">
                <?php echo $translate['helper'] ?>
            </div>


        </div>

        <section class="container-fluid py-3 py-md-5">
            <div class="container">
                <h4><?php echo $translate['package']['include']['intro_products'] ?>:</h4>
                <?php displayIncludePack($translate['package']['include']['products']); ?>
            </div>
        </section>

    </main>

    <footer id="footer">
        <section class="container-fluid py-3 py-md-5">
            <div class="container">
                <h4><?php echo $translate['extra'] ?>:</h4>
                <div id="featured_info_toCart" class="d-flex justify-content-center align-items-center gap-3" data-error-add-message="<?php echo $translate['form']['errors']['add-product'] ?>" data-error-delete-message="<?php echo $translate['form']['errors']['del-product'] ?>" data-delete-button="<?php echo $translate['form']['buttons']['delete'] ?>" data-total-cart-message="<?php echo $translate['form']['cart']['product-total'] ?>" data-quantity-cart-message="<?php echo $translate['form']['cart']['product-quantity'] ?>">
                    <?php displayProductCheckbox($translate['products'], $translate['settings']['currency'], $translate['form']['buttons']['add']) ;?>
                </div>
            </div>
        </section>
        <div class="copyright text-center py-3">
            © Copyright 2025 | <?php echo $translate['package']['name'] ?>
        </div>
    </footer>


    <!-- SIDEBAR -->
    <section class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <small id="offcanvasRightLabel"><?php echo $translate['header'] ?></small>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- BODY -->
            <h4 class="fs-6"><?php echo $translate['form']['buttons']['cart'] ?></h4>
            <div id="cartBox" class="cartBox my-1 my-md-2 p-2">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="product d-flex align-items-center justify-content-start gap-2">
                        <div class="thumbnail p-1">
                            <img src="<?php echo version('img/he_thumbnail_elektryczna_paka.png'); ?>" alt="Elektryczna paka - HE">
                        </div>
                        <div class="product__name">
                            <span id="mainProductName"><?php echo $translate['package']['name'] ?></span>
                        </div>
                    </div>
                    <div class="product__price">
                        <span id="mainProductPrice"><?php echo number_format($translate['package']['price'], 2, ',', ' ') ?></span> <span class="currency"><?php echo $translate['settings']['currency'] ?></span>
                    </div>
                </div>

                <!-- Dodatkowe opcje, kolor -->
                <div id="cartOptions" class="cartBoxOptions my-2">
                    <small><?php echo $translate['form']['colors']['fields']['label'] ?>:</small>
                    <div class="boxColor d-flex justify-content-between align-items-center">
                        <div class="color-name d-flex justify-content-between align-items-center gap-2">
                            <span id="mainProductColorMarker"></span>
                            <span id="mainProductColorName"></span>
                        </div>
                        <div class="color-price">
                            <span id="mainProductColorPrice"></span>
                            <span class="currency"><?php echo $translate['settings']['currency'] ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Dodatkowe produkty -->
            <div id="cartBoxPromo" class="cartBoxPromo"></div>

            <!-- Podsumowanie Koszyka -->
            <div class="cartBoxTotal p-2">
                <div class="cartBoxTotal__value d-flex justify-content-between align-items-center gap-2"><span><?php echo $translate['form']['order_summary']['value'] ?>:</span><span id="cartValue"></span></div>
                <div class="cartBoxTotal__delivery d-flex justify-content-between align-items-center gap-2"><span><?php echo $translate['form']['order_summary']['cost'] ?>:</span><span id="cartDelivery"></span></div>
                <div class="cartBoxTotal__amount d-flex justify-content-end align-items-center gap-2"><span><?php echo $translate['form']['order_summary']['amount'] ?>:</span><span id="cartTotal"></span></div>
            </div>

            <!-- FORMULARZ -->
            <div id="cartBoxForm" class="cartBoxForm my-1 p-2">
                <h4 class="fs-6"><?php echo $translate['form']['person']['title'] ?></h4>
            
                <div class="form-group">
                    <label for="name"><?php echo $translate['form']['person']['fields']['first_name'] ?>:</label>
                    <input type="text" id="name" class="form-control" name="name" autocomplete="off" required>
                </div> 
                <div class="form-group">
                    <label for="company"><?php echo $translate['form']['person']['fields']['last_name'] ?>:</label>
                    <input type="text" id="company" class="form-control" name="company" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="email"><?php echo $translate['form']['person']['fields']['email'] ?>:</label>
                    <input type="text" id="email" class="form-control" name="email" autocomplete="off" minlength="3" maxlength="64" required>
                </div>
                <div class="form-group">
                    <label for="phone"><?php echo $translate['form']['person']['fields']['phone'] ?>:</label>
                    <input type="text" id="phone" class="form-control" name="phone" autocomplete="off">
                </div>

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
            <div class="submit py-1 text-center">
                <!-- Inputs -->
                <input type="hidden" id="lang" name="lang" value="<?php echo $lang ?>"> 
                <input type="hidden" id="human" name="human" value="">
                <!-- end -->

                <button class="btn send" id="send" type="submit"><?php echo $translate['form']['buttons']['submit'] ?></button>
            </div>
        </div>
    </section>
        
    
        
    <!-- Theme Settings -->
    <script src="<?php echo version('js/theme.js'); ?>"></script>
    <!-- Helper Functions -->
    <script src="<?php echo version('js/functions.js'); ?>"></script>
    <!-- Corol Pack -->
    <script src="<?php echo version('js/color_pack.js'); ?>"></script>
    <!-- Text Format -->
    <script src="<?php echo version('js/personal_text.js'); ?>"></script>
    <!-- Text Font -->
    <script src="<?php echo version('js/fonts_canvas.js'); ?>"></script>
    <!-- Flags -->
    <script src="<?php echo version('js/flags.js'); ?>"></script>
    <!-- Cart -->
    <script src="<?php echo version('js/cart.js'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    
    <script>
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
        





function formatPricePL(number) {
    return number.toFixed(2) // Dwa miejsca po przecinku
            .replace('.', ',') // Zamiana kropki na przecinek
            .replace(/\B(?=(\d{3})+(?!\d))/g, ' '); // Spacja jako separator tysięcy
}

  </script>


<script>
    // Aktualizacjia Koszyka po wybaniu koloru Paki
    document.addEventListener('DOMContentLoaded', function() {
    const colorMarkerElement = document.getElementById('mainProductColorMarker');
    const colorNameElement = document.getElementById('mainProductColorName');
    const colorPriceElement = document.getElementById('mainProductColorPrice');

    function updateCartWithColor(colorName, colorPrice, colorCode, colorRal) {
        colorNameElement.textContent = `${colorName}`;
        colorNameElement.setAttribute('data-color', colorCode)
        colorNameElement.setAttribute('data-color-ral', colorRal)
        colorPriceElement.textContent = colorPrice ? `${formatNumberWithSpaces(colorPrice)}` : `${formatNumberWithSpaces(0)}`;
        colorMarkerElement.style.backgroundColor = colorCode;
    }

    const defaultColorOption = document.querySelector('#packColor a[data-default="1"]');
    if (defaultColorOption) {
        const colorName = defaultColorOption.getAttribute('title');
        const colorPrice = parseFloat(defaultColorOption.getAttribute('data-price'));
        const colorCode = defaultColorOption.getAttribute('data-color');
        const colorRal = defaultColorOption.getAttribute('data-color-ral');

        updateCartWithColor(colorName, colorPrice, colorCode, colorRal);
    }

    const colorOptions = document.querySelectorAll('#packColor a');
    colorOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            const colorName = this.getAttribute('title');
            const colorPrice = parseFloat(this.getAttribute('data-price'));
            const colorCode = this.getAttribute('data-color');
            const colorRal = this.getAttribute('data-color-ral');

            const colorCategory = this.getAttribute('data-category');
            const glitterLayer = document.getElementById('glitter-layer');
            if (colorCategory === 'brocate') {
                createGlitter(glitterLayer, 10000, 1); // Dodaj brokat => 0 - no blinking
            } else {
                removeGlitter(glitterLayer); // Usuń brokat
            }

            updateCartWithColor(colorName, colorPrice, colorCode, colorRal);
        });
    });
});



</script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const offcanvasElement = document.getElementById('offcanvasRight');
        
        const headerContent = document.getElementById('header');
        const mainContent = document.getElementById('main');
        const footerContent = document.getElementById('footer');

        offcanvasElement.addEventListener('show.bs.offcanvas', function () {
            headerContent.classList.add('gray-blur');
            mainContent.classList.add('gray-blur');
            footerContent.classList.add('gray-blur');
        });

        offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
            headerContent.classList.remove('gray-blur');
            mainContent.classList.remove('gray-blur');
            footerContent.classList.remove('gray-blur');
        });
        });
    </script>

<script>
// Generator PDF/JPEG
document.querySelectorAll('a[data-version]').forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault(); // Zapobiega przeładowaniu strony

        const type = this.getAttribute('data-version').toLowerCase(); // Pobiera typ pliku (pdf/jpeg)
        generateFile(type); // Wywołuje funkcję
    });
});
</script>



<script>
function updateCartItem(data, blockId) {
    const currency = document.getElementById('save-config').dataset.currency || 'zł' // waluta z button

    const cartBox = document.getElementById('cartBox');
    let cartItem = document.getElementById(blockId);

    // Jeśli 'Text' jest pusty, usuwamy blok i aktualizujemy sumę
    if (!data.Text) {
        if (cartItem) {
            cartBox.removeChild(cartItem);
        }
        updateCartTotal();
        return;
    }

    // Jeśli blok istnieje, aktualizujemy go
    if (cartItem) {
        // Aktualizujemy każdą właściwość (Text, Color, Price, Promo itp.)
        if (data.Title) {
            const titleElement = cartItem.querySelector('.Title');
            if (titleElement) {
                titleElement.textContent = encodeHTML(data.Title);
            }
        }

        if (data.Text) {
            const textElement = cartItem.querySelector('.Text');
            if (textElement) {
                textElement.textContent = encodeHTML(data.Text);
            }
        }

        if (data.Color) {
            const colorElement = cartItem.querySelector('.Color');
            if (colorElement) {
                colorElement.style.backgroundColor = encodeHTML(data.Color);
            }
        }

        if (data.Price) {
            const priceElement = cartItem.querySelector('.Price');
            if (priceElement) {
                priceElement.textContent = `${formatNumberWithSpaces(data.Price)} ${encodeHTML(currency)}`;
            }
        }

        if (data.Promo) {
            const promoElement = cartItem.querySelector('.Promo');
            if (promoElement) {
                promoElement.textContent = `${formatNumberWithSpaces(data.Promo)} ${encodeHTML(currency)}`;
                promoElement.style.color = 'green';
            }
        }

        if (data.Size) {
            const sizeElement = cartItem.querySelector('.Size');
            if (sizeElement) {
                sizeElement.textContent = encodeHTML(data.Size);
            }
        }

        if (data.Format) {
            const formatElement = cartItem.querySelector('.Format');
            if (formatElement) {
                formatElement.textContent = encodeHTML(data.Format);
            }
        }

        if (data.Font) {
            const fontElement = cartItem.querySelector('.Font');
            if (fontElement) {
                fontElement.textContent = encodeHTML(data.Font);
            }
        }

        if (data.Align) {
            const alignElement = cartItem.querySelector('.Align');
            if (alignElement) {
                alignElement.textContent = encodeHTML(data.Align);
            }
        }
    } else {
        // Jeśli blok nie istnieje, tworzymy nowy
        cartItem = document.createElement('div');
        cartItem.id = blockId;
        cartItem.classList.add('cart-item', 'customization', 'my-1');

        // Budujemy HTML nowego elementu, każdy atrybut w osobnym <span>
        let innerHTML = `
            <div class="Title">${data.Title}</div>
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div class="w-75">
                    <span class="Text">${encodeHTML(data.Text)}</span>
                    <small>
                        <span class="Color" ${data.Color ? `style="background-color: ${data.Color};"` : ''}>${data.Color || ''}</span>
                        <span class="Align">${encodeHTML(data.Align || '')}</span>
                        <span class="Size">${encodeHTML(data.Size || '')}</span>
                        <span class="Format">${encodeHTML(data.Format || '')}</span>
                        <span class="Font">${encodeHTML(data.Font || '')}</span>
                    </small>
                </div>
                <div class="price">
                    <span class="Price">
                        ${data.Promo
                            ? `<del>${formatNumberWithSpaces(data.Price)} ${currency}</del> ${formatNumberWithSpaces(data.Promo)} <span class="currency">${currency}</span>`
                            : (data.Price
                                ? `${formatNumberWithSpaces(data.Price)} <span class="currency">${currency}</span>`
                                : `${formatNumberWithSpaces(0)} <span class="currency">${currency}</span>`)}
                    </span>
                </div>
            </div>
        `;
        cartItem.innerHTML = innerHTML;

        cartBox.appendChild(cartItem);
    }

    // Aktualizujemy sumę cen w koszyku
    updateCartTotal();
}





function getTextData(sectionPrefix) {
    const title = document.getElementById(`text_${sectionPrefix}`).dataset.name || false;
    const text = document.getElementById(`text_${sectionPrefix}`).value.trim();
    const color = document.getElementById(`selectedColorText_${sectionPrefix}`)?.style.backgroundColor || false;
    const align = document.querySelector(`#textAlignt_${sectionPrefix} .tools.active`)?.dataset.alignName || false;
    const size = document.querySelector(`#textSize_${sectionPrefix} .tools.active`)?.dataset.sizeName || false;
    const formats = Array.from(document.querySelectorAll(`#textStyle_${sectionPrefix} .tools.active`))
        .map(tool => tool.dataset.styleName)
        .join(', ') || false;
    const font = document.querySelector(`#fontFamily_${sectionPrefix} canvas.active`)?.dataset.fontName || false;
    const price = parseFloat(document.getElementById(`text_${sectionPrefix}`).dataset.price) || false;
    const promo = document.getElementById(`text_${sectionPrefix}`).dataset.promo || false;

    return {
        Title: title,
        Text: text,
        Color: color,
        Align: align,
        Size: size,
        Format: formats,
        Font: font,
        Price: price,
        Promo: promo
    };
}

function handleSaveConfig() {
    
    // const sections = ['grawerTopPanel', 'toppanel', 'door', 'side'];

    // ✅ Elastyczne i skalowalne => dodasz/usuniesz sekcję, kod się dostosuje.
    const sections = Array.from(document.querySelectorAll('input[id^="text_"]'))
        .map(el => el.id.replace('text_', '')); // Usuwa 'text_' z ID, zostawiając same nazwy sekcji


    sections.forEach(sectionId => {
        const sectionData = getTextData(sectionId);
        if (sectionData) {
            updateCartItem(sectionData, `${sectionId}-item`);
        } else {
            showError(`Brak danych dla sekcji: ${sectionId}`);
            console.warn(`Brak danych dla sekcji: ${sectionId}`);
        }
    });
}



document.getElementById('save-config').addEventListener('click', handleSaveConfig);


</script>


<script>
    function updateCartTotal() {
        const cartItems = document.querySelectorAll('.cart-item');
        let total = 0;

        cartItems.forEach(item => {
            const priceElement = item.querySelector('.personalization-price');
            if (priceElement) {
                const priceText = priceElement.textContent || priceElement.innerHTML;
                const priceMatch = priceText.match(/(\d+(\.\d{1,2})?)/);
                if (priceMatch) {
                    const price = parseFloat(priceMatch[0]);
                    total += price;
                }
            }
        });

        const cartTotalElement = document.getElementById('cartTotal');
        if (cartTotalElement) {
            cartTotalElement.textContent = `${total.toFixed(2)}`;
        }
    }

</script>

</body>
</html>
