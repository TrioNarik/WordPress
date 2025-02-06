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
            echo '<div class="d-flex justify-content-center align-items-center gap-3 my-md-3">';
            echo '<input type="checkbox" name="' . $itemKey .'" checked disable>';
            echo '<label for="' . $itemKey .'">';
            echo $item['name'];
            echo '</label>';
            echo '</div>';
        }
    }
}

function displayIncludeImage($items) {
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
            echo '<li>';
            echo '<img src="img/' .  htmlspecialchars($item['image']) .'" alt="" />';
            echo '</li>';
        }
    }
}

function displayProductCheckbox($items, $col_text, $col_image, $currency) {
    echo '<div class="row">';
    foreach ($items as $itemKey => $item) {
        if ($item['active']) {
           
            if (!empty($item['image'])) {
                echo '<div class="col-4">';
                echo '<div class="photo text-center">';
                echo '<img itemprop="image" src="img/' . htmlspecialchars($item['image']) . '" class="img-fluid" alt="' . htmlspecialchars($item['name']) . '">';
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
            <div class="col-xs-12 col-md-6 title">
                <div class="py-2">
                    <h4><?php echo $translate['header'] ?></h4>
                </div>
            </div>
        </div>

        <div class="row options my-md-5">
            <div class="col-xs-12 col-md-4">
                
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <label for="color-picker">
                        <?php echo $translate['form']['colors']['fields']['label'] ?>:
                    </label>
                    <input type="color" id="favcolor" class="pulsating-outline" name="color-picker" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $translate['form']['colors']['fields']['info'] ?>">
                </div>
                <p class="info"><?php echo $translate['form']['colors']['fields']['help'] ?></p>

                <div class="d-flex justify-content-center align-items-center gap-3">
                    <label for="marker">
                        <?php echo $translate['form']['marker']['fields']['label'] ?>:
                    </label>
                    <input type="checkbox" id="marker" name="marker">
                </div>
                <p class="info"><?php echo $translate['form']['marker']['fields']['help'] ?></p>

                <div class="d-flex justify-content-center align-items-center gap-3">
                    <label for="flag">
                    <?php echo $translate['form']['flag']['fields']['label'] ?>:
                    </label>
                    <input type="checkbox" id="flag" name="flag">
                </div>
                <p class="info"><?php echo $translate['form']['flag']['fields']['help'] ?></p>

            </div>

            <div class="col-xs-12 col-md-4">
                <section id="image_paka" class="text-center">
                    <?php svg_code('paka.svg') ?>
                </section>
            </div>

            <div class="col-xs-12 col-md-4">
                
                <?php displayIncludePack($translate['package']['include']['products']); ?>
                
                <div class="premium d-flex justify-content-center align-items-center my-md-5" id="showModal">
                    <button class="btn btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">Dodatkowe wyposażenie</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="fs-5" id="exampleModalLabel">Wybierz dodatkowe wyposażenie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php displayProductCheckbox($translate['products'], 6, 6, $translate['settings']['currency']) ;?>
                    </div>
                </div>
                </div>

            </div>

            
        </div>

        <div class="row my-3">
            <div class="col-xs-12 col-md-6 title">
                <div class="py-2">
                    <h4><?php echo $translate['form']['order_summary']['value'] ?> <span id="countPrice">1.900</span></h4>
                </div>
            </div>
            <p><?php echo $translate['form']['order_summary']['cost'] ?> <span id="cost">350</span></p>
        </div>
    </section>

    <section class="container-fluid images">
        <ul class="d-flex justify-content-between align-items-center gap-3">
            <?php displayIncludeImage($translate['package']['include']['products']); ?>
        </ul>
    </section>

    <section class="container">
    <form id="orderForm" class="py-5">
            <main>
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="Input1"><?php echo $translate['form']['person']['fields']['first_name'] ?>:</label>
                            <input type="text" class="form-control" name="name" autocomplete="off" required>
                        </div> 
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="Input1"><?php echo $translate['form']['person']['fields']['last_name'] ?>:</label>
                            <input type="text" class="form-control" name="company" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        
                        <div class="form-group">
                            <label for="Input1"><?php echo $translate['form']['person']['fields']['email'] ?>:</label>
                            <input type="text" class="form-control" name="email" autocomplete="off" minlength="3" maxlength="64" required>
                        </div>

                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="Input1"><?php echo $translate['form']['person']['fields']['phone'] ?>:</label>
                            <input type="text" class="form-control" name="phone" autocomplete="off">
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
  </script>
</body>
</html>
