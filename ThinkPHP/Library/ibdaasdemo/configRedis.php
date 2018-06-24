<?php
// require __DIR__.'/vendor/autoload.php';
require __DIR__.'/vendor/predis/predis/autoload.php';
Predis\Autoloader::register();

$Reids = new Predis\Client();
// $client = new Predis\Client('tcp://127.0.0.1:6378');
// $client->set('foo', 'bar');
// echo $value = $client->get('foo');