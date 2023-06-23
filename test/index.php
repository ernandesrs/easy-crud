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

// $user = (new UserModel())->find(67);
// var_dump(
//     $user->delete()
// );

// var_dump(
//     (new UserModel())->limit()->getAll()
// );

var_dump(
    // (new UserModel())->limit(3)->whereNull("email_verified_at")->getAll(),
    // (new UserModel())->limit(3)->whereNotNull("email_verified_at")->getAll(),
    // (new UserModel())->limit(4)->where("id", "=", 10)->orWhereNull("email_verified_at")->getAll(),
    // (new UserModel())->limit(4)->where("id", "=", 2)->orWhereNotNull("email_verified_at")->getAll(),
    // (new UserModel())->limit(4)->where("email_verified_at", "=", "2023-06-19 18:06:43")->orWhereNull("email_verified_at")->getAll(),
    (new UserModel())->limit(10)->offset(10)->getAll()
);