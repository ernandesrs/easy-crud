# EasyCrud
<b>EasyCrud</b> é um projeto de estudo do PHP, ele um é simples componente PHP que visa facilitar os processos mais básicos de manipulação de banco de dados MYSQL: CREATE, READ, UPDATE, DELETE.

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
A utilização também é bastante simples, basta criar uma instância de <b>\ErnandesRS\EasyCrud\EasyCrud</b>, passando para o construtor o nome da tabela e o nome da coluna <i>chave primária</i> da tabela.

Veja abaixo um exemplo para uso em uma tabela <b>users</b>, cuja coluna <i>chave primária</i> seja <b>iduser</b>:

```php

$easy = new \ErnandesRS\EasyCrud\EasyCrud("users", "iduser");

```

O segundo parâmetro é opcional caso a coluna <i>chave primária</i> seja <b>id</b>.

# Exemplos de consulta
Veja abaixo alguns exemplos de como realizar consultas em uma tabela de usuários.

Recuperando um único registro:

```php

$one = $easy->getOne();

```

Recuperando um único registro com algumas colunas específicas:

```php

$one = $easy->getOne("id, first_name, username");

```

Recuperando um registro pela <i>chave primária</i> primária:

```php

$find = $easy->find(10);

```

Recuperando um registro pela <i>chave primária</i> primária com algumas colunas específicas:

```php

$find = $easy->find(10, "id, first_name, username");

```

Obtendo todos registros:

```php

$all = $easy->getAll();

```

Obtendo todos registros com algumas colunas específicas:

```php

$all = $easy->getAll("id, first_name, username");

```

Você pode obter registros que atendam algumas condições com operador AND:

```php

$all = $easy->where("id", "<=" , 5)->getAll();

```

Você pode obter registros com condições utilizando o operador OR:

```php

$all = $easy->where("id", "<=", 3)->orWhere("id", "=" , 10)->getAll();

```

# Exemplos de inserção
Veja abaixo alguns exemplos de como realizar inserções de registro em uma tabela.

```php

$new = $easy->create([
    "first_name" => "John"
    "last_name" => "Marinheiro"
]);

```

# Requisitos

    * PHP 8 ou superior