# EasyCrud
<b>EasyCrud</b> é um projeto de estudo de PHP, ele é simples componente PHP que visa facilitar os processos mais básicos de manipulação de banco de dados MYSQL: CREATE, READ, UPDATE, DELETE.

# Instalação
A forma mais fácil de instalar é via composer:

> composer require ernandesrs/easy-crud

# Configuração
A configuração é bastante simples, você só precisa de uma constante contendo um array com as informações de conexão e configuração do banco de dados, segue abaixo um exemplo:

```php

define("CONF_EASY_CRUD", [
    "dbname" => "database name",
    "host" => "localhost",
    "user" => "database username",
    "pass" => "database password",
    "options" => [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]
]);

```

# Utilização
A utilização também é bastante simples, basta criar uma instância de <b>\ErnandesRS\EasyCrud\EasyCrud</b> passando para o construtor o nome da tabela e o nome da coluna <i>primary key</i> da tabela.

## Utilização rápida
Veja abaixo um exemplo para uso em uma tabela <b>users</b>, cuja coluna <i>primary key</i> seja <b>iduser</b>:

```php

$easy = new \ErnandesRS\EasyCrud\EasyCrud("users", "iduser");

```

O segundo parâmetro é opcional caso a coluna <i>primary key</i> seja <b>id</b>.

## Exemplos de consulta
Veja abaixo alguns exemplos de como realizar consultas em uma tabela.

```php

$easy = new \ErnandesRS\EasyCrud\EasyCrud("users");

// Obtém um
$one = $easy->getOne();

// Obtém um com apenas algumas colunas
$one = $easy->getOne("id, first_name, username");

// Obtém todos
$all = $easy->getAll();

// Obtém todos com apenas algumas colunas
$all = $easy->getAll("id, first_name, username");

// Obtém muitos com condição AND
$many = $easy->where("id", ">", "3")->where("id", "<", "10")->getAll();

// Obtém muitos com condição OR
$many = $easy->where("id", ">", "3")->orWhere("id", "=", "10")->getAll();

// Obtém com primary key(id)
$find = $easy->find(10);

// Obtém com primary key(id) com apenas algumas colunas
$find = $easy->find(10, "id, first_name, username");

```

# Requisitos

    * PHP 8 ou superior