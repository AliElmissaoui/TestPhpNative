<?php
//config.php
require_once 'vendor/autoload.php';
use Smarty\Smarty;
return [
    'pdo' => new PDO('mysql:host=localhost;dbname=hospital_management;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]),
    'smarty' => (function () {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/templates');
        $smarty->setCompileDir(__DIR__ . '/templates_c');
        $smarty->setCacheDir(__DIR__ . '/cache');
        $smarty->setConfigDir(__DIR__ . '/configs');
        return $smarty;
    })(),
];
