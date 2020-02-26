<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'rgpksmnv_m4' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'rgpksmnv' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'fR4LT2' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ':c|IYcRyikpDS#5}JgeZ&]GHdr8?],BuX}Ph~]ke@MnPn-SgAg-[HO?v_8:s[ex9' );
define( 'SECURE_AUTH_KEY',  '^Su-)6`7M7eqWME|t%P=Z/@x8GDXQTpvQ<I}t$:7<I~H5cmcfbgS,$gy6U|{h>-h' );
define( 'LOGGED_IN_KEY',    'DKz}$6yIE<[{y: t*az|.V=dzN[P>>j*qhy5z=Nnl5js;QyVd;^[hYDRIjF/>=$u' );
define( 'NONCE_KEY',        'YLq~0){/s-5lqs[,2P,:N~-~n>t9Vun36Cbsk(k^UOvtB5),q0fzc]K+^,7BfW9j' );
define( 'AUTH_SALT',        ' 9yO]LlN?hD@6`+2:DPAc+vG+heslDmwR@&OC#Z{N!l|aAdP&&(?!Vt{f_}!sWJe' );
define( 'SECURE_AUTH_SALT', ':#ACRojy*Jc5Uja!hYl4z0&.;H,n3i8*%}#}fx.)5-ZyOukwSnvy Eo ,})++##L' );
define( 'LOGGED_IN_SALT',   '#piPb?l}UUD{8yG3 e;|y|J|by|=yrs{>^CX0DWC |gCNoVPp>cT^k,-MlM(e*^-' );
define( 'NONCE_SALT',       '=AjM|01SX/2x^_*Bngqr=wn0bX6~~U.#. s2f_Ha5/UO)e]-KHjm]J!+E=RKV>cq' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
