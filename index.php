<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'globals.php';
require_once 'config/db.php';
require_once 'config/cache.php';
require_once 'model/Customer.php';
require_once 'controller/CustomerController.php';
require_once 'routes/api.php';