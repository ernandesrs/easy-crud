<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/Model/UserModel.php";

/**
 * Defina esta constante com um array contendo estas informações de acesso e configuração do seu banco de dados,
 * que a classe \ErnandesRS\EasyCrud\Crud irá utilizá-lo para obter estas informações de acesso automáticamente
 */
define("CONF_EASY_CRUD", [
    "dbname" => "lapi",
    "host" => "localhost",
    "user" => "root",
    "pass" => "",
    "options" => [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]
]);

$user = (new UserModel())
    ->findByEmail("visitor@mail.com", "id, first_name, email");
    // ->find(10, "id, first_name");
    // ->where("id", ">", 2)
    // ->where("id", "<", 8)
    // ->orWhere("email", "=", "ernandesrsouza@gmail.com")
    // ->getAll();
var_dump($user);