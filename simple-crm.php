<?php
/*
 * Plugin Name: Simple CRM
 * Text Domain: simple-crm
 */

namespace simpleCRM;

define('PLUGIN_PATH', __DIR__);

require_once PLUGIN_PATH . '/classes/class-laravel-connector.php';
require_once PLUGIN_PATH . '/classes/class-user-controller.php';

use simpleCRM\classes\LaravelConnector;
use simpleCRM\classes\UserController;

new LaravelConnector();
new UserController();
