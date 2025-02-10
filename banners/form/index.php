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

// ==============================
// Funkcja do aktualizacji plików
// ==============================
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

// function displayIncludeImage($items) {
//     foreach ($items as $itemKey => $item) {
//         if ($item['active']) {
//             echo '<li>';
//             echo '<img class="img-fluid" src="img/' .  htmlspecialchars($item['image']) .'?" alt="" />';
//             echo '</li>';
//         }
//     }
// }





function displayProductCheckbox($items, $currency) {
    echo '<div class="row">';
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {

            echo '<div class="col-12 col-md-4">';
            echo '<div class="featured m-2 my-md-5 p-2 p-md-4">';
            echo '<div class="d-flex justify-content-start align-items-center gap-4">';
            echo '<input type="checkbox" id="' . htmlspecialchars($item['name']) . '" name="' . htmlspecialchars($item['name']) . '">';
            echo '<label for="' . htmlspecialchars($item['name']) . '">' . htmlspecialchars($item['name']) . '</label>';
            echo '</div>';
            if (!empty($item['image'])) {

                $imagePath = 'img/' . $item['image'];
                $versionedImagePath = version($imagePath);

                echo '<div class="image d-flex justify-content-center align-items-center text-center">';

                echo '<img class="img-fluid" src="' . htmlspecialchars($versionedImagePath) . '" alt="' . htmlspecialchars($item['name']) . '">';
                echo '</div>';
            }

            
            if (!empty($item['parameters']['value'])) {
                echo '<div class="row parameters">';
                echo '<div class="col-6">'. htmlspecialchars($item['parameters']['name']) .':</div>';
                echo '<div class="col-6 "><strong>'. htmlspecialchars($item['parameters']['value']) .'</strong></div>';
                echo '</div>';
            }
            
            if (!empty($item['desc'])) {
                echo '<p class="parameters" itemprop="description">'. htmlspecialchars($item['desc']) .'</p>';
            }
            
            if ($item['show_price']) {
                echo '<div class="row parameters">';
                echo '<div id="'. $itemKey .'" class="col-sx-12 col-md-4 product-price" data-product="'. $item['name'] .'" data-value="'. $item['price'] .'">';
                echo '<div class="block-price text-light text-center p-2">';
                echo '<span itemprop="price" content="'. $item['price'] .'">'. $item['price'] .'</span> ';
                echo '<span itemprop="priceCurrency" content="'. $currency .'">'. $currency .'</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                if ($item['shipping']['active']) {
                    echo '<div class="block-shipping';
                    if ($item['shipping']['class']) {
                        echo 'bg-'. $item['shipping']['class'] .' text-light';
                    }
                    echo ' col-sx-12 col-md-8 text-end p-2">';
                    echo '<small>'. $item['shipping']['content'] .' '. $item['shipping']['price'] .' '. $currency .'</small>';
                    echo '</div>';
                }
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
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="><?php echo $translate['title'] ?>" />
    <meta property="og:url" content="https://electra.hussaria.pl/order/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo version('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
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
                <div class="logo-wrapper py-2 py-md-5 my-2 my-md-5">
                    <a href="./">
                        <img id="logo" class="img-fluid logo" src="img/hussaria_electra_logo_white.png" alt="<?php echo $translate['title'] ?>">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid model">

        <div class="row my-3">
            <div class="col-12 col-md-6 title">
                <div class="py-2">
                    <h1><?php echo $translate['header'] ?></h1>
                </div>
            </div>
        </div>

        <div class="row options my-md-5">
            <div class="col-7 my-5 col-md-3">
                
                <div class="d-flex justify-content-start align-items-center gap-3">
                    <label for="favcolor">
                        <?php echo $translate['form']['colors']['fields']['label'] ?>:
                    </label>
                    <input type="color" id="favcolor" class="pulsating-outline" name="color-picker" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $translate['form']['colors']['fields']['info'] ?>" list />
            
                </div>
                <p class="info"><?php echo $translate['form']['colors']['fields']['help'] ?></p>

                <div class="d-flex justify-content-start align-items-center gap-3">
                    <label for="marker">
                        <?php echo $translate['form']['marker']['fields']['label'] ?>:
                    </label>
                    <input type="checkbox" id="marker" name="marker">
                </div>
                <p class="info"><?php echo $translate['form']['marker']['fields']['help'] ?></p>

                <div id="personal">
                    <div class="form-group row">
                        <div class="col-6">
                        <input type="text" class="form-control" id="person" placeholder="Jan Kowalski...">
                        
                        <div class="d-flex justify-content-between align-items-center gap-3 my-3">
                        
                            <button class="btn" id="leftSide">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="23px" viewBox="0 0 34 23" version="1.1">
                                <g id="left">
                                <path d="M 2.203125 0 L 31.796875 0 C 33.011719 0 34 1 34 2.234375 C 34 3.472656 33.011719 4.472656 31.796875 4.472656 L 2.203125 4.472656 C 0.988281 4.472656 0 3.472656 0 2.234375 C 0 1 0.988281 0 2.203125 0 Z M 2.203125 0 "/>
                                <path d="M 2.203125 9.265625 L 19.40625 9.265625 C 20.621094 9.265625 21.609375 10.265625 21.609375 11.5 C 21.609375 12.734375 20.621094 13.734375 19.40625 13.734375 L 2.203125 13.734375 C 0.988281 13.734375 0 12.734375 0 11.5 C 0 10.265625 0.988281 9.265625 2.203125 9.265625 Z M 2.203125 9.265625 "/>
                                <path d="M 2.203125 18.527344 L 8.601562 18.527344 C 9.816406 18.527344 10.804688 19.527344 10.804688 20.765625 C 10.804688 22 9.816406 23 8.601562 23 L 2.203125 23 C 0.988281 23 0 22 0 20.765625 C 0 19.527344 0.988281 18.527344 2.203125 18.527344 Z M 2.203125 18.527344 "/>
                                </g>
                                </svg>
                            </button>

                            <button class="btn" id="centerSide">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="23px" viewBox="0 0 34 23" version="1.1">
                                <g id="center">
                                <path d="M 2.203125 0 L 31.796875 0 C 33.011719 0 34 1 34 2.234375 C 34 3.472656 33.011719 4.472656 31.796875 4.472656 L 2.203125 4.472656 C 0.988281 4.472656 0 3.472656 0 2.234375 C 0 1 0.988281 0 2.203125 0 Z M 2.203125 0 "/>
                                <path d="M 12.789062 9.265625 L 21.21875 9.265625 C 22.4375 9.265625 23.421875 10.265625 23.421875 11.5 C 23.421875 12.734375 22.4375 13.734375 21.21875 13.734375 L 12.789062 13.734375 C 11.570312 13.734375 10.585938 12.734375 10.585938 11.5 C 10.585938 10.265625 11.570312 9.265625 12.789062 9.265625 Z M 12.789062 9.265625 "/>
                                <path d="M 2.203125 18.527344 L 31.796875 18.527344 C 33.011719 18.527344 34 19.527344 34 20.765625 C 34 22 33.011719 23 31.796875 23 L 2.203125 23 C 0.988281 23 0 22 0 20.765625 C 0 19.527344 0.988281 18.527344 2.203125 18.527344 Z M 2.203125 18.527344 "/>
                                </g>
                                </svg>
                            </button>

                            <button class="btn" id="rightSide">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="23px" viewBox="0 0 34 23" version="1.1">
                                <g id="right">
                                <path d="M 2.203125 0 L 31.796875 0 C 33.011719 0 34 1 34 2.234375 C 34 3.472656 33.011719 4.472656 31.796875 4.472656 L 2.203125 4.472656 C 0.988281 4.472656 0 3.472656 0 2.234375 C 0 1 0.988281 0 2.203125 0 Z M 2.203125 0 "/>
                                <path d="M 14.59375 9.265625 L 31.796875 9.265625 C 33.011719 9.265625 34 10.265625 34 11.5 C 34 12.734375 33.011719 13.734375 31.796875 13.734375 L 14.59375 13.734375 C 13.378906 13.734375 12.390625 12.734375 12.390625 11.5 C 12.390625 10.265625 13.378906 9.265625 14.59375 9.265625 Z M 14.59375 9.265625 "/>
                                <path d="M 25.398438 18.527344 L 31.796875 18.527344 C 33.011719 18.527344 34 19.527344 34 20.765625 C 34 22 33.011719 23 31.796875 23 L 25.398438 23 C 24.183594 23 23.195312 22 23.195312 20.765625 C 23.195312 19.527344 24.183594 18.527344 25.398438 18.527344 Z M 25.398438 18.527344 "/>
                                </g>
                                </svg>
                            </button>
                        </div>
                        
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-start align-items-center gap-3">
                    <label for="flag">
                    <?php echo $translate['form']['flag']['fields']['label'] ?>:
                    </label>
                    <input type="checkbox" id="flag" name="flag">
                </div>
                <p class="info"><?php echo $translate['form']['flag']['fields']['help'] ?></p>

            </div>

            <div class="col-5 col-md-6">
                <section id="image_paka" class="text-center">
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
        <h4><?php echo $translate['extra'] ?>:</h4>
        <div class="d-flex justify-content-center align-items-center gap-3">
            <?php displayProductCheckbox($translate['products'], $translate['settings']['currency']) ;?>
        </div>
        </div>
    </section>

    <section class="container-fluid model">
        <div class="row my-3">
            <div class="col-12 col-md-6 title">
                <div class="py-2">
                    <h4><?php echo $translate['form']['order_summary']['value'] ?>: <span id="countPrice">1.900</span> <span><?php echo $translate['settings']['currency'] ?></span></h4>
                </div>
            </div>
            <p><?php echo $translate['form']['order_summary']['cost'] ?> <span id="cost">350</span> <?php echo $translate['settings']['currency'] ?></p>
        </div>
    </section>

    <section class="container">
    <form id="orderForm" class="py-md-5">
            <main>
                
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
                
                

            </main>
            <footer>  
                <div class="row">
                    <div class="col-12">

                        <!-- Inputs -->
                        <input type="hidden" id="lang" name="lang" value="<?php echo $_SESSION['lang'] ?>"> 
                        <input type="hidden" id="human" name="human" value="">
                        <!-- end -->

                        <button type="submit"><?php echo $translate['form']['order_summary']['button'] ?></button>
                    </div>
                </div>     
            </footer>
        </form>

        
        
    </section>
        
        
    </div>
    <script src="<?php echo version('js/theme.js'); ?>"></script>
    <script src="<?php echo version('js/rating.js'); ?>"></script>
    <!-- <script src="<?php echo version('js/form.js'); ?>"></script> -->

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
                    personalDiv.style.display = 'block';
                } else {
                    personalDiv.style.display = 'none';
                }
            }

            togglePersonalDiv();

            markerCheckbox.addEventListener('change', togglePersonalDiv);
        });



        // ==== Extra Products DIV
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".featured").forEach(function (item) {
                item.addEventListener("click", function (e) {
                    let checkbox = this.querySelector("input[type='checkbox']");
                    
                    // Zapobiegamy dwukrotnemu kliknięciu
                    if (e.target.tagName !== "INPUT") {
                        checkbox.checked = !checkbox.checked;
                    }

                    // Dodajemy/Usuwamy klasę .selected
                    this.classList.toggle("selected", checkbox.checked);
                });
            });
        });



  </script>
</body>
</html>
