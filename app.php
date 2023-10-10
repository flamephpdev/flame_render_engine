<?php

/**
 * This is a faker plugin to use the FlameCore's (https://github.com/flamephpdev/flamecore)
 * FlamePHP Package written by Martin Binder (https://mrtn.vip)
 * Please do not edit the files under flamephp
 * If anything goes wrong, write an email to bndr@mrtn.vip
 * or open an issue on github (https://github.com/flamephpdev/flame_render_engine)
 */

$config = require_once __DIR__ . '/config.php';

// Set the application renderer root
define('FLAMEPHP_RENDER_ENGINE_ROOT', $config['plugin_root'] ?: dirname(__DIR__));

// Set the mode of this tool
define('FLAMEPHP_MODE_DEV', $config['developer_mode'] ?: false);

// Set the include folder root
define('FLAMEPHP_VIEWS_FOLDER', $config['views_path'] ?: FLAMEPHP_RENDER_ENGINE_ROOT . '/views');

// Include the whole engine
require_once __DIR__ . '/flamecore_requirements/index.php';

/**
 * Thanks for using my tool 
 * Hope it helps your development ;)
 */
