<?php
session_start();

// Lang
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'pl';

$translate = include("lang/$lang.php");

// Funkcja do zapisu danych do pliku
function updateRating() {
    $file_path = 'rating.json';

    // Odczyt aktualnych danych z pliku
    if (file_exists($file_path)) {
        $data = json_decode(file_get_contents($file_path), true);
        $currentRating = $data['rating'];
        $currentVotes = $data['votes'];
    } else {
        $currentRating = 0;
        $currentVotes = 0;
    }

    // Zwiększenie ranking o 0.01 i liczby głosów o 1
    $newRating = bcadd($currentRating, '0.0025', 4); // Używamy bcadd z precyzją do 4 miejsc po przecinku
    $newVotes = $currentVotes + 1;

    // Zapis nowych danych do pliku
    $data = array('rating' => $newRating, 'votes' => $newVotes);
    file_put_contents($file_path, json_encode($data));
}

// =========================================
// Funkcja do odczytu Rankingu [HE] z pliku
// =========================================
function getRating($key) {
    $file_path = 'rating.json';
    if (file_exists($file_path)) {
        $data = json_decode(file_get_contents($file_path), true);
        return isset($data[$key]) ? $data[$key] : null;
    } else {
        return null;
    }
}


// ==========================================
// Funkcja do wyświetlania elementów Pakietu
// ==========================================
function displayIncludePack($items, $col_text, $col_image) {
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
            echo '<div class="row include">';
            echo '<div class="col-'. $col_text .'">';
            echo '<h4>' . htmlspecialchars($item['name']) . '</h4>';
            echo '<p><small>' . htmlspecialchars($item['desc']) . '</small></p>';
            echo '</div>';
            echo '<div class="col-'. $col_image .'">';
            if (!empty($item['image'])) {
                echo '<img src="img/' . htmlspecialchars($item['image']) . '" class="img-fluid img-thumbnail" alt="' . htmlspecialchars($item['name']) . '">';
            }
            echo '</div>';
            echo '</div>';
        }
    }
}

function displayProductCheckbox($items, $col_text, $col_image, $currency) {
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
            echo '<div class="d-flex flex-column box boxes">';
            echo '<label class="circle-checkbox">';
            echo '<div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">';
            echo '<input type="checkbox" name="product[]" value="'. $item['name'] .'" data-price="'. $item['price'] .'" data-shipping="'. $item['shipping']['price']. '">';
            echo '<span class="custom-circle"></span>';
            echo '<span class="custom-label">'. $item['name'] .'</span>';
            echo '</div>';

            if (!empty($item['image'])) {
                echo '<div class="image text-center">';
                echo '<img src="img/' . htmlspecialchars($item['image']) . '" class="img-fluid" alt="' . htmlspecialchars($item['name']) . '">';
                echo '</div>';
            }

            if (!empty($item['parameters']['value'])) {
                echo '<div class="row parameters">';
                echo '<div class="col-5">'. htmlspecialchars($item['parameters']['name']) .'</div>';
                echo '<div class="col-7"><strong>'. htmlspecialchars($item['parameters']['value']) .'</strong></div>';
                echo '</div>';
            }
            
            if (!empty($item['desc'])) {
                echo '<p class="parameters">'. htmlspecialchars($item['desc']) .'</p>';
            }
            
            if ($item['show_price']) {
                echo '<div class="d-flex justify-content-end align-items-center gap-1">';
                echo '<div id="battery" class="product-price text-end" data-product="'. $item['name'] .'" data-value="'. $item['price'] .'">';
                echo '<strong>'. $item['price'] .'</strong>';
                echo $currency;
                
                if ($item['shipping']['active']) {
                    echo '<p class="';
                    if ($item['shipping']['class']) {
                        echo 'bg-'. $item['shipping']['class'] .' text-light';
                    }
                    echo 'px-1 rounded">';
                    echo '<small>'. $item['shipping']['content'] .'</small></p>';
                }
                echo '</div>';
                echo '</div>';
            }

            
            echo '</label>';
            echo '</div>';

        }
    }
}


// Aktualizacja ranking i głosów przy każdym zamówieniu
updateRating();
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>">
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
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="><?php echo $translate['title'] ?>" />
    <meta property="og:url" content="https://electra.hussaria.pl/order/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="row py-sm-1 py-md-3">
                <div class="col-6 col-md-12">
                    <a class="navbar-brand text-sm-center" href="#">
                        <img src="img/hussaria_electra.png" alt="Hussaria Electra" class="img-fluid" />
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
    
        <div class="row">
            <section class="title py-3 py-md-5 d-flex justify-content-center">
                <h1><?php echo $translate['header'] ?></h1>
            </section>
        </div>

        
        <form id="orderForm">
            <main>
                <h2><?php echo $translate['form']['person']['title'] ?></h2>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['person']['fields']['first_name'] ?>:
                            <input type="text" name="name" autocomplete="off" required>
                        </label>
                    </div>
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['person']['fields']['last_name'] ?>:
                            <input type="text" name="company" autocomplete="off" required>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['person']['fields']['email'] ?>:
                            <input type="email" name="email" autocomplete="off" minlength="3" maxlength="64" required>
                        </label>
                    </div>
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['person']['fields']['phone'] ?>:
                            <input type="text" name="phone" autocomplete="off">
                        </label>
                    </div>
                </div>
                
                <h2><?php echo $translate['form']['address']['title'] ?></h2>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['address']['fields']['street'] ?>:
                            <input type="text" name="street" autocomplete="off">
                        </label>
                    </div>
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['address']['fields']['post_code'] ?>:
                            <input type="text" name="post" autocomplete="off">
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['address']['fields']['city'] ?>:
                            <input type="text" name="city" autocomplete="off">
                        </label>
                    </div>
                    <div class="col-12 col-md-6">
                        <label>
                            <?php echo $translate['form']['address']['fields']['country'] ?>:
                            <input type="text" name="country" value="" autocomplete="off">
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-3 align-items-center">
                <h2><?php echo $translate['form']['shipping']['title'] ?></h2>
                
                    <label class="switch">
                        <input type="checkbox" id="differentShippingAddress" name="differentShippingAddress" value="1">
                        <span class="slider round"></span>
                    </label>

                    <span class="d-none d-md-block"> <?php echo $translate['form']['shipping']['fields']['checkbox'] ?>:</span>
                    
                </div>

                <div id="shippingAddressField">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label>
                                <?php echo $translate['form']['shipping']['fields']['street'] ?>:
                                <input type="text" name="shippingStreet" autocomplete="off">
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label>
                                <?php echo $translate['form']['shipping']['fields']['post_code'] ?>:
                                <input type="text" name="shippingPost" autocomplete="off">
                            </label>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label>
                                <?php echo $translate['form']['shipping']['fields']['city'] ?>:
                                <input type="text" name="shippingCity" autocomplete="off">
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label>
                                <?php echo $translate['form']['shipping']['fields']['country'] ?>:
                                <input type="text" name="shippingCountry" autocomplete="off">
                            </label>
                        </div>
                    </div>
                </div>
                
                <section class="row my-3" id="products">
                    <h2><?php echo $translate['form']['order']['title'] ?></h2>
                    <div class="col-12 col-md-7">
                        <!-- Electric Pack -->
                        <?php if ($translate['package']['active']): ?>
                            <div class="d-flex flex-column box" itemscope itemtype="https://schema.org/Offer">
                                
                            <label class="circle-checkbox">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input
                                            type="checkbox" 
                                            name="product[]"
                                            value="<?php echo $translate['package']['name'] ?>"
                                            data-price="<?php echo $translate['package']['price'] ?>"
                                            data-promo="<?php if ($translate['package']['promo'] || $translate['package']['promo'] == 0) { ?><?php echo $translate['package']['promo'] ?><?php } ?>"
                                            data-shipping="<?php echo $translate['package']['shipping']['price'] ?>"
                                            data-delivery="<?php if ($translate['package']['shipping']['promo'] || $translate['package']['shipping']['promo'] === 0 || $translate['package']['shipping']['free']) { ?><?php echo $translate['package']['shipping']['promo'] ?><?php } ?>"
                                            data-currency="<?php echo $translate['settings']['currency'] ?>"
                                        /> 
                                        <span class="custom-circle"></span>
                                        <span class="custom-label" itemprop="name"><?php echo $translate['package']['name'] ?></span>
                                    </div>

                                    <?php if ($translate['package']['show_price']): ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="pack" class="product-price text-end" data-product="<?php echo $translate['package']['name'] ?>" data-value="<?php echo $translate['package']['price'] ?>">
                                                
                                                <?php if ($translate['package']['promo'] || $translate['package']['promo'] === 0) { ?>
                                                    <small class="text-danger"><del><?php echo $translate['package']['price'] ?></del></small>
                                                    <span itemprop="price"><?php echo $translate['package']['promo'] ?></span>
                                                <?php } else { ?>
                                                    <span itemprop="price"><?php echo $translate['package']['price'] ?></span>
                                                <?php } ?>

                                                <span itemprop="priceCurrency" content="<?php echo $translate['settings']['currency'] ?>"><?php echo $translate['settings']['currency'] ?></span>

                                                
                                                <?php if ($translate['package']['shipping']['active']) { ?>
                                                    <p class="px-1 rounded <?php if ($translate['package']['shipping']['class']) { ?>bg-<?php echo $translate['package']['shipping']['class'] ?> text-light<?php } ?>">
                                                        <small>
                                                            <?php echo $translate['package']['shipping']['content'] ?>

                                                            <?php if ($translate['package']['shipping']['promo'] || $translate['package']['shipping']['promo'] === 0 || $translate['package']['shipping']['free']) { ?>
                                                                <small class="text-danger"><del><?php echo $translate['package']['shipping']['price'] ?></del></small>
                                                                <?php echo $translate['package']['shipping']['promo'] ?>
                                                                <?php } else { ?>
                                                                    <small><?php echo $translate['package']['shipping']['price'] ?></small>
                                                            <?php } ?>

                                                            <?php if ($translate['package']['shipping']['price']) { ?>
                                                                <?php if ($translate['package']['shipping']['free']) { ?>
                                                                    <small><del><?php echo $translate['settings']['currency'] ?></del></small>
                                                                <?php } else { ?>
                                                                    <?php echo $translate['settings']['currency'] ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </small>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Describe -->
                                <div class="detail">
                                    <!-- Rating -->
                                    <section class="rate d-flex gap-2" itemprop="reviews" itemscope itemtype="https://schema.org/AggregateRating">
                                        <div itemprop="ratingValue"><?php echo number_format(getRating('rating'), 2, '.', '') ?></div>
                                        <div>
                                            <span class="fa fa-star text-warning"></span>
                                            <span class="fa fa-star text-warning"></span>
                                            <span class="fa fa-star text-warning"></span>
                                            <span class="fa fa-star text-warning"></span>
                                            <span class="fa fa-star text-warning"></span>
                                        </div>
                                        <meta itemprop="ratingValue" content="<?php echo number_format(getRating('rating'), 2, '.', '') ?>" />
                                        <meta itemprop="bestRating" content="5" />
                                        <div class="text-secondary" itemprop="ratingCount"><?php echo getRating('votes') ?> <?php echo $translate['package']['votes'] ?></div>
                                    </section>
                                    <!-- end -->

                                    <p itemprop="description"><?php echo $translate['package']['desc'] ?></p>
                                    
                                    <section class="text-success my-3">
                                    ✔ <span itemprop="availability" href="https://schema.org/InStock"><?php echo $translate['package']['status'] ?></span><br />
                                        <small class="text-secondary"><?php echo $translate['package']['deadline'] ?></small>
                                    </section>


                                    <?php if (isset($translate['package']['weight']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['weight']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['weight']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($translate['package']['height']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['height']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['height']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['width']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['width']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['width']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['load']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['load']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['load']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['speed']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['speed']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['speed']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['colors']['palette'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['colors']['name'] ?>:</dt>
                                            <dd class="w-100 d-flex gap-2 align-items-center">
                                                <span class="color-palette" style="background:<?php echo $translate['package']['colors']['palette']['black']['code'] ?>"></span> 
                                                <?php echo $translate['package']['colors']['palette']['black']['name'] ?> - 
                                                <?php echo $translate['package']['colors']['palette']['black']['ral'] ?>
                                            </dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['materials']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['materials']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['materials']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['equipment']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['equipment']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['equipment']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['wheels']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['wheels']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['wheels']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['led']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['led']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['led']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>

                                    <?php if (isset($translate['package']['drive']['value'])): ?>
                                        <dl class="d-flex">
                                            <dt class="w-50"><?php echo $translate['package']['drive']['name'] ?>:</dt>
                                            <dd class="w-100"><?php echo $translate['package']['drive']['value'] ?></dd>
                                        </dl>
                                    <?php endif; ?>


                                </div>
                                <!-- end -->

                                <!-- Include products -->
                                
                                <section class="row detail mt-3">
                
                                    <?php if (isset($translate['package']['include']['products'])): ?>
                                        <div class="col-12">
                                            <h2 class="bg-dark text-light p-2"><?php echo $translate['package']['include']['intro_products'] ?>:</h2>
                                            <?php displayIncludePack($translate['package']['include']['products'], 9, 3); ?>
                                        </div>        
                                    <?php endif; ?>
                                    
                                    <!-- Gadgets -->
                                    <?php if ($translate['package']['include']['widgets']['active']): ?>
                                        <div class="col-12">
                                            <h2 class="bg-dark text-light p-2"><?php echo $translate['package']['include']['intro_widgets'] ?>:</h2>    
                                            <?php displayIncludePack($translate['package']['include']['widgets'], 9, 3); ?>
                                        </div>           
                                    <?php endif; ?>
                
                                </section>

                                <section class="pack-image text-center">
                                    <img itemprop="image" src="img/elektryczna-paka.png" class="img-fluid" alt="">
                                </section>


                            </label>

                            </div>        
                        <?php endif; ?>
                        
                    </div>

                    <div class="col-12 col-md-5">
                        <!-- Other Products -->
                        <?php if ($translate['products']): ?>
                            <?php displayProductCheckbox($translate['products'], 6, 6, $translate['settings']['currency']) ;?>
                        <?php endif; ?>
                    </div>
                </section>


                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <?php if ($translate['form']['prepayment']['active']): ?>
                            <div class="d-flex align-items-center gap-3">
                                <h2><?php echo $translate['form']['prepayment']['title'] ?></h2>
                                <label class="switch">
                                    <input type="checkbox" id="prepayment" name="prepayment" value="1">
                                    <span class="slider round"></span>
                                </label>
                                <span class="custom-label"><?php echo $translate['form']['prepayment']['fields']['checkbox'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-8 col-md-4">
                        <h4 class="text-start text-md-end"><?php echo $translate['form']['order_summary']['value'] ?>:</h4>
                        <p class="text-start text-md-end"><?php echo $translate['form']['order_summary']['cost'] ?>:</p>

                        <?php if ($translate['settings']['discount']['value']): ?>
                            <p class="text-start text-md-end"><?php echo $translate['settings']['discount']['name'] ?>:</p>
                        <?php endif; ?>
                        <?php if ($translate['settings']['fees']['value']): ?>
                            <p class="text-start text-md-end"><?php echo $translate['settings']['fees']['name'] ?>:</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-4 col-md-2">
                        <h4 class="text-end"><span id="totalPrice"></span></h4>
                        <p class="text-end shipping"><span id="totalShipping"></span></p>
                        
                        <?php if ($translate['settings']['discount']['value']): ?>
                            <p class="text-end"><span id="discount"><?php echo $translate['settings']['discount']['value'] ?></span></p>
                        <?php endif; ?>
                        <?php if ($translate['settings']['fees']['value']): ?>
                            <p class="text-end"><span id="fees"><?php echo $translate['settings']['fees']['value'] ?></span></p>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Prepayment Block -->
                <?php if ($translate['form']['prepayment']['active']): ?>
                    <div class="row align-items-center" id="prepaymentFields">
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-column">
                                <?php if ($translate['form']['prepayment']['fields']['cash']['active']): ?>
                                    <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                            <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                                <input type="radio" name="prepaymentMode" value="<?php echo $translate['form']['prepayment']['fields']['cash']['mode'] ?>" data-time="<?php echo $translate['form']['prepayment']['fields']['cash']['timing'] ?>" data-status="<?php echo $translate['form']['prepayment']['fields']['cash']['status'] ?>"> 
                                                <span class="custom-circle"></span>
                                                <span class="custom-label"><?php echo $translate['form']['prepayment']['fields']['cash']['mode'] ?></span>
                                            </div>
                                    </label>
                                <?php endif; ?>

                                <?php if ($translate['form']['prepayment']['fields']['wire']['active']): ?>
                                    <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                            <input type="radio" name="prepaymentMode" value="<?php echo $translate['form']['prepayment']['fields']['wire']['mode'] ?>" data-time="<?php echo $translate['form']['prepayment']['fields']['wire']['timing'] ?>" data-status="<?php echo $translate['form']['prepayment']['fields']['wire']['status'] ?>"> 
                                            <span class="custom-circle"></span>
                                            <span class="custom-label"><?php echo $translate['form']['prepayment']['fields']['wire']['mode'] ?></span>
                                        </div>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label>
                                <?php echo $translate['form']['prepayment']['fields']['amount'] ?>:
                                <input type="number" name="prepaymentCount" min="0" step="0.01">
                            </label>
                        </div>

                    </div>
                <?php endif; ?>

            </main>
            <footer>  
                <div class="row">
                    <div class="col-12">
                        <h3><?php echo $translate['form']['order_summary']['amount'] ?> <span id="countPrice"></span></h3>
                        
                        <!-- Inputs -->
                        <input type="hidden" id="lang" name="lang" value="<?php echo $_SESSION['lang'] ?>"> 
                        <input type="hidden" id="human" name="human" value="">
                        <!-- end -->

                        <button type="submit"><?php echo $translate['form']['order_summary']['button'] ?></button>
                    </div>
                </div>     
            </footer>
        </form>

        <div class="notification" id="notification">
            <?php echo $translate['form']['order_summary']['notification'] ?>
        </div>
        
        <div id="confirmationMessage" style="display: none;">
            <h4><?php echo $translate['form']['order_summary']['confirm'] ?></h4>
            <hr />
            <a class="btn" href="./" role="button"><?php echo $translate['form']['order_summary']['back'] ?></a>
        </div>
        
    </div>
    <script src="js/form.js"></script>
</body>
</html>
