<?php
session_start();

// Lang
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'pl';

$translate = include("lang/$lang.php");

// ==========================================
// Funkcja do wyświetlania elementów pakietu
// ==========================================
function displayProducts($items) {
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
            echo '<div class="item">';
            echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
            echo '<p>' . htmlspecialchars($item['desc']) . '</p>';
            if (!empty($item['image'])) {
                echo '<img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['name']) . '">';
            }
            echo '</div>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translate['title'] ?></title>
    <link rel="icon" href="img/hussaria-electra-100x100.png" sizes="32x32">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="row py-sm-1 py-md-3">
                <div class="col-6 col-md-12">
                    <a class="navbar-brand text-sm-center" href="#">
                        <img src="img/he_logo.png" alt="Hussaria Electra" class="img-fluid" />
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
    
    <!-- ================== -->
    <div class="row">
        <?php if (isset($translate['products']['tack_locker']['include']['products'])): ?>
            <div class="col-6">                
                <?php displayProducts($translate['products']['tack_locker']['include']['products']); ?>
            </div>        
        <?php endif; ?>

        <?php if (isset($translate['products']['tack_locker']['include']['widgets'])): ?>
            <div class="col-6">         
                <?php displayProducts($translate['products']['tack_locker']['include']['widgets']); ?>
            </div>           
        <?php endif; ?>
    </div>
    <!-- ================= -->

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

                <h2><?php echo $translate['form']['order']['title'] ?></h2>
                <div class="row" id="products">
                    <div class="col-12 col-md-6">
                
                        <?php if ($translate['product_tack_locker']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input
                                            type="checkbox" 
                                            name="product[]"
                                            value="<?php echo $translate['product_tack_locker']['name'] ?>"
                                            data-price="<?php echo $translate['product_tack_locker']['price'] ?>"
                                            data-promo="<?php if ($translate['product_tack_locker']['promo'] || $translate['product_tack_locker']['promo'] == 0) { ?><?php echo $translate['product_tack_locker']['promo'] ?><?php } ?>"
                                            data-shipping="<?php echo $translate['product_tack_locker']['shipping']['price'] ?>"
                                            data-delivery="<?php if ($translate['product_tack_locker']['shipping']['promo'] || $translate['product_tack_locker']['shipping']['promo'] === 0 || $translate['product_tack_locker']['shipping']['free']) { ?><?php echo $translate['product_tack_locker']['shipping']['promo'] ?><?php } ?>"
                                            data-currency="<?php echo $translate['order']['currency'] ?>"
                                        > 
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_tack_locker']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_tack_locker']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="pack" class="product-price text-end" data-product="<?php echo $translate['product_tack_locker']['name'] ?>" data-value="<?php echo $translate['product_tack_locker']['price'] ?>">
                                                
                                                <?php if ($translate['product_tack_locker']['promo'] || $translate['product_tack_locker']['promo'] === 0) { ?>
                                                    <small class="text-danger"><del><?php echo $translate['product_tack_locker']['price'] ?></del></small>
                                                    <strong><?php echo $translate['product_tack_locker']['promo'] ?></strong>
                                                <?php } else { ?>
                                                    <small><?php echo $translate['product_tack_locker']['price'] ?></small>
                                                <?php } ?>

                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_tack_locker']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_tack_locker']['shipping']['class']) { ?>bg-<?php echo $translate['product_tack_locker']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded">
                                                        <small>
                                                            <?php echo $translate['product_tack_locker']['shipping']['content'] ?>

                                                            <?php if ($translate['product_tack_locker']['shipping']['promo'] || $translate['product_tack_locker']['shipping']['promo'] === 0 || $translate['product_tack_locker']['shipping']['free']) { ?>
                                                                <small><del><?php echo $translate['product_tack_locker']['shipping']['price'] ?></del></small>
                                                                <?php echo $translate['product_tack_locker']['shipping']['promo'] ?>
                                                            <?php } ?>

                                                            <?php if ($translate['product_tack_locker']['shipping']['price']) { ?>
                                                                <?php echo $translate['product_tack_locker']['shipping']['price'] ?>
                                                                <?php echo $translate['order']['currency'] ?>
                                                            <?php } ?>
                                                        </small>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>

                        <?php if ($translate['product_tack_cover']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_tack_cover']['name'] ?>" data-price="<?php echo $translate['product_tack_cover']['price'] ?>" data-shipping="<?php echo $translate['product_tack_cover']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_tack_cover']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_tack_cover']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="case" class="product-price text-end" data-product="<?php echo $translate['product_tack_cover']['name'] ?>" data-value="<?php echo $translate['product_tack_cover']['price'] ?>">
                                                <strong><?php echo $translate['product_tack_cover']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_tack_cover']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_tack_cover']['shipping']['class']) { ?>bg-<?php echo $translate['product_tack_cover']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_tack_cover']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>

                        <?php if ($translate['product_tack_customization']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_tack_customization']['name'] ?>" data-price="<?php echo $translate['product_tack_customization']['price'] ?>" data-shipping="<?php echo $translate['product_tack_customization']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_tack_customization']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_tack_customization']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="custom" class="product-price text-end" data-product="<?php echo $translate['product_tack_customization']['name'] ?>" data-value="<?php echo $translate['product_tack_customization']['price'] ?>">
                                                <strong><?php echo $translate['product_tack_customization']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_tack_customization']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_tack_customization']['shipping']['class']) { ?>bg-<?php echo $translate['product_tack_customization']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_tack_customization']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>

                            <div class="">
                                <label for="customization" class="form-label"><?php echo $translate['product_tack_customization']['form_label'] ?></label>
                                <textarea id="customization" name="customization" rows="3"></textarea>
                            </div>
                        <?php } ?>

                    </div>

                    <div class="col-12 col-md-6">

                        
                        <?php if ($translate['product_battery']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_battery']['name'] ?>" data-price="<?php echo $translate['product_battery']['price'] ?>" data-shipping="<?php echo $translate['product_battery']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_battery']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_battery']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="battery" class="product-price text-end" data-product="<?php echo $translate['product_battery']['name'] ?>" data-value="<?php echo $translate['product_battery']['price'] ?>">
                                                <strong><?php echo $translate['product_battery']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_battery']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_battery']['shipping']['class']) { ?>bg-<?php echo $translate['product_battery']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_battery']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>


                        <?php if ($translate['product_charger']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_charger']['name'] ?>" data-price="<?php echo $translate['product_charger']['price'] ?>" data-shipping="<?php echo $translate['product_charger']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_charger']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_charger']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="charger" class="product-price text-end" data-product="<?php echo $translate['product_charger']['name'] ?>" data-value="<?php echo $translate['product_charger']['price'] ?>">
                                                <strong><?php echo $translate['product_charger']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_charger']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_charger']['shipping']['class']) { ?>bg-<?php echo $translate['product_charger']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_charger']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>

                        <?php if ($translate['product_ramp']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_ramp']['name'] ?>" data-price="<?php echo $translate['product_ramp']['price'] ?>" data-shipping="<?php echo $translate['product_ramp']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_ramp']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_ramp']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="trap" class="product-price text-end" data-product="<?php echo $translate['product_ramp']['name'] ?>" data-value="<?php echo $translate['product_ramp']['price'] ?>">
                                                <strong><?php echo $translate['product_ramp']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_ramp']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_ramp']['shipping']['class']) { ?>bg-<?php echo $translate['product_ramp']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_ramp']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>

                        
                        <?php if ($translate['product_ramp_cover']['active']) { ?>
                            <div class="d-flex flex-column box">
                                <label class="circle-checkbox d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" name="product[]" value="<?php echo $translate['product_ramp_cover']['name'] ?>" data-price="<?php echo $translate['product_ramp_cover']['price'] ?>" data-shipping="<?php echo $translate['product_ramp_cover']['shipping']['price'] ?>" data-currency="<?php echo $translate['order']['currency'] ?>">
                                        <span class="custom-circle"></span>
                                        <span class="custom-label"><?php echo $translate['product_ramp_cover']['name'] ?></span>
                                    </div>
                                    <?php if ($translate['product_ramp_cover']['show_price']) { ?>
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <div id="case_trap" class="product-price text-end" data-product="<?php echo $translate['product_ramp_cover']['name'] ?>" data-value="<?php echo $translate['product_ramp_cover']['price'] ?>">
                                                <strong><?php echo $translate['product_ramp_cover']['price'] ?></strong>
                                                <?php echo $translate['order']['currency'] ?>
                                                
                                                <?php if ($translate['product_ramp_cover']['shipping']['active']) { ?>
                                                    <p class="<?php if ($translate['product_ramp_cover']['shipping']['class']) { ?>bg-<?php echo $translate['product_ramp_cover']['shipping']['class'] ?> text-light<?php } ?> px-1 rounded"><small><?php echo $translate['product_ramp_cover']['shipping']['content'] ?></small></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </label>
                            </div>
                        <?php } ?>

                    </div>
                </div>


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
        
        <div id="confirmationMessage" style="display: none;">
            <h4><?php echo $translate['form']['order_summary']['confirm'] ?></h4>
            <hr />
            <a class="btn" href="./" role="button"><?php echo $translate['form']['order_summary']['back'] ?></a>
        </div>
        
    </div>
    <script src="js/form.js"></script>
</body>
</html>
