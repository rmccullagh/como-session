<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Como\Cavalry\Session;

$session = new Session();

$session->set('key', 'value');


var_dump($session);

