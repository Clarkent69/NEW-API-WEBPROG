<?php
require_once __DIR__ . '/../functions.php';

ini_set("soap.wsdl_cache_enabled", "0");

$options = array(
    'uri' => "http://localhost/API/api/"
);

$server = new SoapServer(null, $options);
$server->setClass('UserServices');
$server->handle();
?>
