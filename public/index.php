<?php

define('BASE_DIR', dirname(__FILE__) . '/..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/view');
define('MODEL_DIR', SOURCE_DIR . '/model');

if ($_SERVER['REQUEST_METHOD'] == "GET"):
    if ($_SERVER['REQUEST_URI'] == "/") {
        require_once(VIEW_DIR . "/list_anime.php");
    } elseif (preg_match('/^\/anime\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
        require_once(VIEW_DIR . "/anime.php");
    } elseif (preg_match('/^\/edit_anime\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
        require_once(VIEW_DIR . "/edit_anime.php");
    } elseif (preg_match('/^\/add_anime\/?$/', $_SERVER['REQUEST_URI'], $matches)) {
        require_once(VIEW_DIR . "/edit_anime.php");
    } else {
        header("location: /");
    }
endif;
