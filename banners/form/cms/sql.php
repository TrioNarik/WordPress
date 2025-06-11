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

$hooks_default  = $tables['default_hooks'];             // HOOKS
$langs_default  = $tables['default_languages'];         // LANGUAGES
$user_groups    = $tables['default_groups'];            // USER GROUPS






try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

$sql = [];

// ================= CMS Dashboard =====================
// -- Języki
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['cms_languages'] . '` (
    `id` INT(3) AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `code` CHAR(2) NOT NULL UNIQUE,
    `name` VARCHAR(15) NOT NULL,
    `iso` CHAR(2) NOT NULL,
    `currency` CHAR(3) NOT NULL,
    `flag` VARCHAR(8)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["cms_hooks"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 0,
    `location` ENUM("BO", "FO") DEFAULT "FO",
    `hook_name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["cms_user_groups"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `active` TINYINT(1) DEFAULT 1,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables["tables"]["cms_user_groups_lang"] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `' . $prefix . $tables["tables"]["cms_user_groups"] . '`(`id`),
    FOREIGN KEY (`lang_id`) REFERENCES `' . $prefix . $tables["tables"]["cms_languages"] . '`(`id`)
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';



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
    FOREIGN KEY (`group_id`) REFERENCES `' . $prefix . $tables['tables']['cms_user_groups'] . '`(`id`)
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
    `total` DECIMAL(10,2) NOT NULL,
    `status` ENUM("pending", "paid", "failed") DEFAULT "pending",
    `payment_method_id` INT(3),
    `coupon_code` VARCHAR(50),
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8mb4;';

// -- Zamówienia
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . $prefix . $tables['tables']['orders'] . '` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT DEFAULT NULL,
    `user_id` INT DEFAULT NULL,
    `total` DECIMAL(10,2) NOT NULL,
    `status` ENUM("pending", "paid", "failed", "cancelled") DEFAULT "pending",
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
    `price` DECIMAL(10,2) NOT NULL,
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
    `price` DECIMAL(10,2) NOT NULL,
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


// Utwórz tabele
foreach ($sql as $query) {
    try {
        $pdo->exec($query);
    } catch (PDOException $e) {
        echo "Błąd SQL: " . $e->getMessage() . "\n";
    }
}


// Wstawienie domyślnych języków
foreach ($langs_default as $lang) {
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

// Wstawianie Grup użytkowników
foreach ($user_groups as $group) {
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_user_groups'] . "`
        (active, name)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE
            active = VALUES(active),
            name = VALUES(name)");

    $stmt->execute([
        $group['active'],
        $group['name']
    ]);

    // Pobieranie ID ostatnio wstawionej grupy
    $groupId = $pdo->lastInsertId();

    // Wstawianie tłumaczeń dla danej grupy do tabeli cms_user_groups_lang
    foreach ($group['langs'] as $translation) {
        $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_user_groups_lang'] . "`
            (group_id, lang_id, title, description)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description)");

        $stmt->execute([
            $groupId,
            $translation['lang'],
            $translation['title'],
            $translation['description']
        ]);
    }
}


// Wstawienie domyślnych hooków
foreach ($hooks_default as $hook) {
    $stmt = $pdo->prepare("INSERT INTO `" . $prefix . $tables['tables']['cms_hooks'] . "` 
        (active, location, hook_name, description, created)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            active = VALUES(active), 
            location = VALUES(location), 
            description = VALUES(description)"
    );

    $stmt->execute([
        $hook['active'], 
        $hook['location'], 
        $hook['hook_name'], 
        $hook['description'], 
        date('Y-m-d H:i:s')
    ]);
}



// Informacja o zakończeniu
echo "Baza danych została zaktualizowana.\n";