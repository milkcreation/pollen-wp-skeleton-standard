<?php

/**
 * PresstiFy Wordpress Classic
 *
 * @author Jordy Manner <jordy@presstify.com>
 * @package presstify/wordpress-classic
 * @version 5.5.5
 */

use Dotenv\Dotenv;
use Composer\Util\Filesystem;

/** EN CAS D'URGENCE */
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**/

/** Définition des globales d'environnement */
defined('START_TIME') ?: define('START_TIME', microtime(true));
defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);
defined('ROOT_PATH') ?: define('ROOT_PATH', __DIR__);
defined('VENDOR_PATH') ?: define('VENDOR_PATH', __DIR__ . DS . 'vendor');
/**/

/** VENDOR */
// Chargement des librairies tierces
if (file_exists(VENDOR_PATH . DS . 'autoload.php')) {
    require_once(VENDOR_PATH . DS . 'autoload.php');
}

// Chargement des variables d'environnement des fichiers .env
$env = Dotenv::createImmutable(ROOT_PATH);
$env->load();
$env->required(['DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_HOST']);
/**/

$fs = new Filesystem();
defined('PUBLIC_DIR') ?: define('PUBLIC_DIR', $fs->normalizePath($_ENV['APP_PUBLIC'] ?? 'wp-content/themes/twentytwentyone'));
defined('PUBLIC_PATH') ?: define('PUBLIC_PATH', ROOT_PATH . PUBLIC_DIR);

/** BASE DE DONNEES */
// Nom de la base de données de WordPress
define('DB_NAME', $_ENV['DB_DATABASE']);

// Utilisateur de la base de données MySQL
define('DB_USER', $_ENV['DB_USERNAME']);

// Mot de passe de la base de données MySQL
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

// Hôte de la base de données MySQL
define('DB_HOST', $_ENV['DB_HOST']
    ? $_ENV['DB_HOST'] . (!empty($_ENV['DB_PORT']) ? ':' . $_ENV['DB_PORT'] : '') : '127.0.0.1:3306'
);

// Encodage des caractères de la base de données MySQL
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

// Collaction de base de données
define('DB_COLLATE', $_ENV['DB_COLLATE'] ?? 'utf8mb4_unicode_ci');

// Préfixe des tables de base de données.
$table_prefix = $_ENV['DB_PREFIX'] ?? 'wp_';
/**/

/** CHEMINS */
// Chemin relatif de stockage de Wordpress dans le dossier public
defined('WP_DIR') ?: define('WP_DIR', $_ENV['WP_DIR'] ?? '');

// Urls du site
define('SITE_URL', $_ENV['APP_URL'] ?? 'http://127.0.0.1:8000');
define('WP_HOME', SITE_URL);
define('WP_SITEURL', WP_DIR ? SITE_URL . '/' . WP_DIR : SITE_URL);

// Répertoire de contenu du site
//define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
//define('WP_CONTENT_URL', WP_HOME . PUBLIC_DIR);
/**/

/**
 * Environnement
 * local|development|staging|production
 */
switch($wp_env = $_ENV['APP_ENV'] ?? 'production') {
    default :
        break;
    case 'dev':
        $wp_env = 'development';
        break;
    case 'prod':
        $wp_env = 'production';
        break;
}
defined('WP_ENVIRONMENT_TYPE') ?: define('WP_ENVIRONMENT_TYPE', $wp_env);
/**/

/** DEBOGAGE */
define('SCRIPT_DEBUG', filter_var($_ENV['SCRIPT_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('WP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('WP_DEBUG_LOG', filter_var($_ENV['WP_DEBUG_LOG'] ?? false, FILTER_VALIDATE_BOOLEAN));
// X-Debug
//ini_set('xdebug.var_display_max_depth', 10);
//ini_set('xdebug.var_display_max_children', 512);
//ini_set('xdebug.var_display_max_data', 4096);
/**/

/** SECURITE */
// Clé d'authentification et de salage
define('AUTH_KEY', $_ENV['AUTH_KEY'] ?? '');
define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY'] ?? '');
define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY'] ?? '');
define('NONCE_KEY', $_ENV['NONCE_KEY'] ?? '');
define('AUTH_SALT', $_ENV['AUTH_SALT'] ?? '');
define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT'] ?? '');
define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT'] ?? '');
define('NONCE_SALT', $_ENV['NONCE_SALT'] ?? '');

// Désactivation de l'éditeur Wordpress
if (defined('WP_INSTALLING') && WP_INSTALLING === false) {
    define('DISALLOW_FILE_MODS', filter_var($_ENV['DISALLOW_FILE_MODS'] ?? false, FILTER_VALIDATE_BOOLEAN));
}
/**/

/** MULTISITE */
define('WP_ALLOW_MULTISITE', filter_var($_ENV['WP_ALLOW_MULTISITE'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('MULTISITE', filter_var($_ENV['MULTISITE'] ?? false, FILTER_VALIDATE_BOOLEAN));
if (defined('MULTISITE') && MULTISITE === true) {
    define('DOMAIN_CURRENT_SITE', $_ENV['DOMAIN_CURRENT_SITE'] ?? '');
    define('NOBLOGREDIRECT', $_ENV['NOBLOGREDIRECT'] ?? '%siteurl%');
    define('SUBDOMAIN_INSTALL', filter_var($_ENV['SUBDOMAIN_INSTALL'] ?? false, FILTER_VALIDATE_BOOLEAN));
    define('PATH_CURRENT_SITE', $_ENV['PATH_CURRENT_SITE'] ?? '');
    define('SITE_ID_CURRENT_SITE', filter_var($_ENV['SITE_ID_CURRENT_SITE'] ?? 1, FILTER_VALIDATE_INT));
    define('BLOG_ID_CURRENT_SITE', filter_var($_ENV['BLOG_ID_CURRENT_SITE'] ?? 1, FILTER_VALIDATE_INT));
    define('WP_DEFAULT_THEME', $_ENV['WP_DEFAULT_THEME'] ?? 'twentytwenty');
}
/**/

/** CONFIGURATION COMPLEMENTAIRE */
define('DISABLE_WP_CRON', filter_var($_ENV['DISABLE_WP_CRON'] ?? false, FILTER_VALIDATE_BOOLEAN));
// [...]
/* */

// Répertoire de stockage de Wordpress.
if (!defined('ABSPATH')) {
    define('ABSPATH', WP_DIR ? ROOT_PATH . DS . WP_DIR . DS : ROOT_PATH . DS);
}

// Initialisation de Wordpress.
require_once(ABSPATH . 'wp-settings.php');