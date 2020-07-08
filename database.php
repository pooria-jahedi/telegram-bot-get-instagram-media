<?php
require "vendor/autoload.php";
//If you want the errors to be shown
error_reporting(E_ALL);
ini_set('display_errors', '1');
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" =>"127.0.0.1",
    "database" => "vesam_other_bot",
    "username" => "root",
    "password" => 'Savecode1374_',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);
//Make this Capsule instance available globally.
$capsule->setAsGlobal();
// Setup the Eloquent ORM.
$capsule->bootEloquent();
