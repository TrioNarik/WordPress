<?php

// SETUP
require_once '../setup.php';
if (!defined('_HE_PATH_')) {
	exit;
}

$db_config  = require_once _HE_PATH_ . '/include/settings.php'; // _DB && Login
$host       = $db_config['db_sql']['host'];
$dbname     = $db_config['db_sql']['dbname'];
$user       = $db_config['db_sql']['user'];
$pass       = $db_config['db_sql']['password'];
$prefix     = $db_config['db_sql']['prefix'];
$engine     = $db_config['db_sql']['mysql_engine'];

$tables     = require_once _HE_PATH_ . '/include/tables.php';   // TABELES NAME & DEFAULT DATA

$default_currencies         = $tables['default_currencies'];        // WALUTY
$default_langs              = $tables['default_languages'];         // LANGUAGES

$default_hooks              = $tables['default_hooks'];             // HOOKS
$default_groups             = $tables['default_groups'];            // USER GROUPS

$default_categories         = $tables['default_categories'];        // KATEGORIE PRODUKTÓW
$default_attributes         = $tables['default_attributes'];        // ATRYBUTY PRODUKTÓW
$default_attribute_values   = $tables['default_attribute_values'];  // WARTOŚCI ATRYBUTÓW

$default_statutes           = $tables['default_statutes'];          // STATUSY ZAMÓWIEŃ






try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

$sql = [];

// ================= CMS TABLES =====================
// Waluty
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["cms_currencies"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(25) NOT NULL UNIQUE,
    `code` VARCHAR(3) NOT NULL,
    `unit` INT(2) DEFAULT 2,
    `symbol` VARCHAR(10) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// Języki
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['cms_languages'] . '` (
    `id` INT(3) AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `code` CHAR(2) NOT NULL UNIQUE,
    `name` VARCHAR(15) NOT NULL,
    `iso` CHAR(2) NOT NULL,
    `currency` INT NOT NULL,
    `flag` VARCHAR(10),
    FOREIGN KEY (`currency`) REFERENCES `' . $prefix . $tables["tables"]["cms_currencies"] . '` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// Hooki
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["cms_hooks"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `location` ENUM("BO", "FO") DEFAULT "FO",
    `title` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';


// Moduły
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['cms_modules'] . '` (
    `id` INT(3) AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `alias` CHAR(15) NOT NULL UNIQUE,
    `lang_id` VARCHAR(2) NOT NULL,
    `type` ENUM("core", "module", "theme") DEFAULT "module",
    `required`  TINYINT(1) DEFAULT 0,
    `dependencies` JSON,
    `show_in_menu` TINYINT(1) DEFAULT 0,
    `hooks` JSON,
    `priority` INT DEFAULT 10,
    `name` VARCHAR(50) NOT NULL,
    `version` VARCHAR(10) NOT NULL,
    `description` TEXT NOT NULL,
    `author` VARCHAR(50),
    `path` VARCHAR(255) NOT NULL,
    `groups` JSON,
    `control` ENUM("visitor", "user", "member", "distributor", "manager", "administrator") DEFAULT "administrator",
    `settings` JSON,
    `updated` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// ========================================================================================

// Grupy użytkowników
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["user_groups"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["user_groups_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `' . $prefix . $tables["tables"]["user_groups"] . '`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables["tables"]["cms_languages"] . '`(`id`) ON DELETE CASCADE,
    UNIQUE (`group_id`, `lang_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// Statusy zamówień
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["statuses"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `priority` INT(2) DEFAULT 0,
    `color` VARCHAR(20),
    `is_final` TINYINT(1) DEFAULT 0, 
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["statuses_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `status_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`status_id`) REFERENCES `' . $prefix . $tables["tables"]["statuses"] . '`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// Kategorie produktów
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["categories"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(30) NOT NULL UNIQUE,
    `slug` VARCHAR(30) NOT NULL UNIQUE,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (active)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["categories_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `' . $prefix . $tables["tables"]["categories"] . '`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// Atrybuty produktów
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["attributes"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(30) NOT NULL UNIQUE,
    `slug` VARCHAR(30) NOT NULL UNIQUE,
    `priority` INT(3) DEFAULT 0,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (active)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["attributes_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `attribute_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`attribute_id`) REFERENCES `' . $prefix . $tables["tables"]["attributes"] . '`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["attribute_values"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `attribute_id` INT NOT NULL,
    `value_key` VARCHAR(50) NOT NULL,
    `priority` INT DEFAULT 0,
    `hex_code` VARCHAR(7) DEFAULT NULL, -- dla kolorów, np. "#FF0000"
    `extra_info` TEXT DEFAULT NULL,     -- dodatkowe dane, np. jednostki, tooltipy
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`attribute_id`) REFERENCES `' . $prefix . $tables["tables"]["attributes"] . '`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["attribute_values_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `value_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`value_id`) REFERENCES `' . $prefix . $tables["tables"]["attribute_values"] . '`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables["tables"]["cms_languages"] . '`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// =================



// -- Użytkownicy
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['users'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `group_id` INT NOT NULL DEFAULT 2,
    `name` VARCHAR(50),
    `lastname` VARCHAR(50) NOT NULL,
    `phone` VARCHAR(20),
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `' . $prefix . $tables['tables']['user_groups'] . '`(`id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';


// -- Adresy użytkowników
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['user_address'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `is_default` TINYINT(1) DEFAULT 0,
    `user_id` INT NOT NULL,
    `type` ENUM("shipping", "billing") DEFAULT "shipping",
    `company` VARCHAR(255) DEFAULT NULL,
    `street` VARCHAR(255) NOT NULL,
    `local` VARCHAR(50) DEFAULT NULL,
    `postcode` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `state` VARCHAR(50) NOT NULL,
    `country` CHAR(2) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] . '`(`id`) ON DELETE CASCADE,
    INDEX (`user_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Logi użytkowników
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['user_logs'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `action` ENUM("login", "logout", "register", "password_change", "profile_update", "address_update") NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] .'`(`id`) ON DELETE CASCADE,
    INDEX (`user_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Opinie użytkowników
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['user_reviews'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `user_id` INT NOT NULL,
    `review` TEXT,
    `rating` INT,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] .'`(`id`) ON DELETE CASCADE,
    INDEX (`user_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Koszyki
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['carts'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_ref` VARCHAR(10) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(15),
    `address` TEXT,
    `total` DECIMAL(10,2) DEFAULT NULL,
    `status_id` INT DEFAULT 1,
    `payment_method_id` INT(3),
    `coupon_code` VARCHAR(50),
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`status_id`) REFERENCES `' . $prefix . $tables['tables']['statuses'] .'`(`id`) ON DELETE SET NULL
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Zamówienia
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['orders'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT DEFAULT NULL,
    `user_id` INT DEFAULT NULL,
    `total` DECIMAL(10,2) DEFAULT NULL,
    `status` INT DEFAULT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`cart_id`) REFERENCES `' . $prefix . $tables['tables']['carts'] .'`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] .'`(`id`) ON DELETE SET NULL,
    INDEX (`cart_id`),
    INDEX (`user_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Produkty w zamówieniu
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['order_products'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(10,2) DEFAULT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `' . $prefix . $tables['tables']['orders'] .'`(`id`) ON DELETE CASCADE,
    INDEX (`order_id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Konfiguracje produktu
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['configurations'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT DEFAULT NULL,
    `user_id` INT DEFAULT NULL,
    `data` TEXT,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `' . $prefix . $tables['tables']['orders'] .'`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] .'`(`id`) ON DELETE SET NULL
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Metody płatności
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['payment_methods'] . '` (
    `id` INT(3) AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `name` VARCHAR(35) NOT NULL,
    `world` TINYINT(1) DEFAULT 0,
    `europe` TINYINT(1) DEFAULT 0,
    `poland` TINYINT(1) DEFAULT 1,
    `other_iso` VARCHAR(50)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['payment_methods_lang'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `method_id` INT(3) NOT NULL,
    `lang_id` INT(3) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `desc` VARCHAR(250) NOT NULL,
    FOREIGN KEY (`method_id`) REFERENCES `' . $prefix . $tables['tables']['payment_methods'] .'`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables['tables']['cms_languages'] .'`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Płatności
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['payments'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `method_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `status` ENUM("pending", "paid", "failed") DEFAULT "pending",
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `' . $prefix . $tables['tables']['orders'] .'`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`method_id`) REFERENCES `' . $prefix . $tables['tables']['payment_methods'] .'`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Metody dostawy
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['shipping_methods'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `name` VARCHAR(35) NOT NULL,
    `world` TINYINT(1) DEFAULT 0,
    `europe` TINYINT(1) DEFAULT 0,
    `poland` TINYINT(1) DEFAULT 1,
    `code_iso` VARCHAR(50)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['shipping_methods_lang'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `method_id` INT NOT NULL,
    `lang_id` INT(3) NOT NULL,
    `price` DECIMAL(10,2) DEFAULT NULL,
    `name` VARCHAR(50) NOT NULL,
    `desc` VARCHAR(250) NOT NULL,
    FOREIGN KEY (`method_id`) REFERENCES `' . $prefix . $tables['tables']['shipping_methods'] .'`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables['tables']['cms_languages'] .'`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Dostawy
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['shippings'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `method_id` INT NOT NULL,
    `tracking_number` VARCHAR(100),
    `status` VARCHAR(50),
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `' . $prefix . $tables['tables']['orders'] .'`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`method_id`) REFERENCES `' . $prefix . $tables['tables']['shipping_methods'] .'`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Kupony
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['coupons'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `lang_id` INT(3) NOT NULL,
    `code` VARCHAR(15) NOT NULL UNIQUE,
    `type` ENUM("percent", "fixed", "free_product", "free_shipping", "first_order", "loyalty") DEFAULT "percent",
    `value` DECIMAL(10,2) DEFAULT NULL,
    `min_order_value` DECIMAL(10,2) DEFAULT NULL,
    `usage_limit` INT DEFAULT 1,
    `used_count` INT DEFAULT 0,
    `start` DATE DEFAULT CURRENT_DATE,
    `finish` DATE DEFAULT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables['tables']['cms_languages'] .'`(`id`) ON DELETE CASCADE
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Użycie kuponów
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['coupon_usage'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `coupon_id` INT NOT NULL,
    `user_id` INT DEFAULT NULL,
    `order_id` INT DEFAULT NULL,
    `used_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`coupon_id`) REFERENCES `' . $prefix . $tables['tables']['coupons'] .'`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `' . $prefix . $tables['tables']['users'] .'`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`order_id`) REFERENCES `' . $prefix . $tables['tables']['orders'] .'`(`id`) ON DELETE SET NULL
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';


// foreach ($tables['tables'] as $table) {
//     $sql = 'DROP TABLE IF EXISTS `' . $prefix . $table . '`;';
//     try {
//         $pdo->exec($sql);
//         echo "Usunięto tabelę: $prefix$table<br>";
//     } catch (PDOException $e) {
//         echo "Błąd przy usuwaniu tabeli $prefix$table: " . $e->getMessage() . "<br>";
//     }
// }

// Utwórz tabele
foreach ($sql as $query) {
    try {
        $pdo->exec($query);
    } catch (PDOException $e) {
        echo "Błąd SQL: " . $e->getMessage() . "\n";
    }
}


// Wstawienie Walut
foreach ($default_currencies as $currency) {
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_currencies'] . "`
        (active, code, name, unit, symbol)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            name = VALUES(name),
            unit = VALUES(unit),
            symbol = VALUES(symbol)"
    );

    $stmt->execute([
        $currency['active'],
        $currency['code'],
        $currency['name'],
        $currency['unit'],
        $currency['symbol']
    ]);
}

// Wstawienie Języków
foreach ($default_langs as $lang) {
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_languages'] . "`
        (active, code, name, iso, currency, flag)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            name = VALUES(name),
            iso = VALUES(iso),
            currency = VALUES(currency),
            flag = VALUES(flag)"
    );

    $stmt->execute([
        $lang['active'],
        $lang['code'],
        $lang['name'],
        $lang['iso'],
        $lang['currency'],
        $lang['flag']
    ]);
}

// Wstawienie Statusów zamówień
foreach ($default_statutes as $status) {
    // Wstawiamy lub aktualizujemy status
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['statuses'] . "`
        (`active`, `name`, `color`, `priority`, `is_final`)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            color = VALUES(color),
            priority = VALUES(priority),
            is_final = VALUES(is_final)");

    $stmt->execute([
        $status['active'],
        $status['name'],
        $status['color'],
        $status['priority'],
        $status['is_final'],
    ]);

    // Pobieramy ID statusu - jeśli insertował nowy rekord lub zaktualizował istniejący
    $status_id = $pdo->lastInsertId();
    if (!$status_id) {
        // Jeśli nie insertował nowego, pobieramy ID istniejącego
        $stmtSel = $pdo->prepare("SELECT id FROM `" . $prefix . $tables['tables']['statuses'] . "` WHERE name = ?");
        $stmtSel->execute([$status['name']]);
        $status_id = $stmtSel->fetchColumn();
    }

    // Teraz tłumaczenia
    foreach ($status['langs'] as $lang) {
        $stmtLang = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['statuses_lang'] . "`
            (`status_id`, `lang_id`, `title`, `description`)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description)");

        $stmtLang->execute([
            $status_id,
            $lang['lang'],
            $lang['title'],
            $lang['description'],
        ]);
    }
}


// Wstawienie Grup użytkowników
foreach ($default_groups as $group) {
    // Wstawiamy lub aktualizujemy podstawową grupę
    $stmt = $pdo->prepare('INSERT INTO `' . $prefix . $tables["tables"]["user_groups"] . '` 
        (active, name)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            name = VALUES(name)');
    $stmt->execute([
        $group['active'],
        $group['name']
    ]);

    // Pobieramy ID grupy [klucz główny]
    $groupId = $pdo->lastInsertId();
    if (!$groupId) {
        // Jeśli nie została stworzona nowa grupa, pobierz istniejącą
        $stmt = $pdo->prepare('SELECT id FROM `' . $prefix . $tables["tables"]["user_groups"] . '` WHERE name = ?');
        $stmt->execute([$group['name']]);
        $groupId = $stmt->fetchColumn();
    }

    // Wstawiamy tłumaczenia językowe
    foreach ($group['langs'] as $lang) {
        $stmt = $pdo->prepare('INSERT INTO `' . $prefix . $tables["tables"]["user_groups_lang"] . '` 
            (group_id, lang_id, title, description)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description)');
        $stmt->execute([
            $groupId,
            $lang['lang'],
            $lang['title'],
            $lang['description']
        ]);
    }
}

// Wstawianie Kategorii
foreach ($default_categories as $category) {
    
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['categories'] . "` 
        (active, name, slug)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            name = VALUES(name),
            slug = VALUES(slug)"
    );

    $stmt->execute([
        $category['active'],
        $category['name'],
        $category['slug']
    ]);

    // Pobierz ID kategorii (dla nowych i istniejących)
    $category_id = $pdo->lastInsertId();
    if ($category_id == 0) {
        $stmt = $pdo->prepare("SELECT id FROM `" . $prefix . $tables['tables']['categories'] . "` WHERE name = ?");
        $stmt->execute([$category['name']]);
        $category_id = $stmt->fetchColumn();
    }

    // Wstaw wersje językowe
    foreach ($category['langs'] as $lang) {
        $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['categories_lang'] . "` 
            (category_id, lang_id, title, description)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description)"
        );

        $stmt->execute([
            $category_id,
            $lang['lang'],
            $lang['title'],
            $lang['description']
        ]);
    }
}

// Wstawianie Atrybutów
foreach ($default_attributes as $attr) {
    // Wstawienie głównego atrybutu
    $stmt = $pdo->prepare("INSERT INTO `{$prefix}{$tables['tables']['attributes']}` 
        (active, name, slug, priority, created)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            active = VALUES(active), 
            priority = VALUES(priority)"
    );
    $stmt->execute([
        $attr['active'],
        $attr['name'],
        $attr['slug'],
        $attr['priority'],
        date('Y-m-d H:i:s')
    ]);

    // Pobierz ID atrybutu
    $attribute_id = $pdo->lastInsertId();
    if (!$attribute_id) {
        // Jeśli już istniał, znajdź po slug
        $stmt_id = $pdo->prepare("SELECT id FROM `{$prefix}{$tables['tables']['attributes']}` WHERE slug = ?");
        $stmt_id->execute([$attr['slug']]);
        $attribute_id = $stmt_id->fetchColumn();
    }

    // Tłumaczenia atrybutu
    $langStmt = $pdo->prepare("INSERT INTO `{$prefix}{$tables['tables']['attributes_lang']}` 
        (attribute_id, lang_id, title, description, created)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description)"
    );
    foreach ($attr['langs'] as $lang) {
        $langStmt->execute([
            $attribute_id,
            $lang['lang'],
            $lang['title'],
            $lang['description'],
            date('Y-m-d H:i:s')
        ]);
    }

    // Wartości (jeśli istnieją)
    if (!empty($default_attribute_values[$attr['name']])) {
        foreach ($default_attribute_values[$attr['name']] as $val) {
            // Wstawienie wartości
            $valStmt = $pdo->prepare("INSERT INTO `{$prefix}{$tables['tables']['attribute_values']}` 
                (attribute_id, value_key, priority, hex_code, extra_info, created)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    priority = VALUES(priority), 
                    hex_code = VALUES(hex_code),
                    extra_info = VALUES(extra_info)"
            );
            $valStmt->execute([
                $attribute_id,
                $val['value_key'],
                $val['priority'],
                $val['hex_code'] ?? null,
                $val['extra_info'] ?? null,
                date('Y-m-d H:i:s')
            ]);

            // Pobierz ID wartości
            $value_id = $pdo->lastInsertId();
            if (!$value_id) {
                $stmt_val_id = $pdo->prepare("SELECT id FROM `{$prefix}{$tables['tables']['attribute_values']}` 
                    WHERE attribute_id = ? AND value_key = ?");
                $stmt_val_id->execute([$attribute_id, $val['value_key']]);
                $value_id = $stmt_val_id->fetchColumn();
            }

            // Tłumaczenia wartości
            $valLangStmt = $pdo->prepare("INSERT INTO `{$prefix}{$tables['tables']['attribute_values_lang']}` 
                (value_id, lang_id, title)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE title = VALUES(title)"
            );
            foreach ($val['langs'] as $lang) {
                $valLangStmt->execute([
                    $value_id,
                    $lang['lang_id'],
                    $lang['title']
                ]);
            }
        }
    }
}



// Wstawienie domyślnych hooków
foreach ($default_hooks as $hook) {
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_hooks'] . "` 
        (active, location, title, description, created)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            active = VALUES(active), 
            location = VALUES(location),
            description = VALUES(description)"
    );

    $stmt->execute([
        $hook['active'], 
        $hook['location'], 
        $hook['title'], 
        $hook['description'], 
        date('Y-m-d H:i:s')
    ]);
}




// Informacja o zakończeniu
echo "Baza danych została zaktualizowana.\n";