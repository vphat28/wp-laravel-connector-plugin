<?php
/*
Plugin Name: Simple CRM
*/

namespace simpleCRM;

define('PLUGIN_PATH', __DIR__);

require_once PLUGIN_PATH . '/classes/class-laravel-connector.php';

use simpleCRM\classes\LaravelConnector;
new LaravelConnector();