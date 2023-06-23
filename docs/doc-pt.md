# Instalação
A forma mais fácil de instalar é via composer:

> composer require ernandesrs/easy-crud

# Configuração
A configuração é bastante simples, você só precisa criar um banco de dados e definir nas configurações do seu projeto a constante abaixo. Ela possui as infomações e configurações necessárias para acesso ao seu banco de dados.

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
A utilização também é bastante simples, basta criar uma instância de <b>EasyCrud</b>, passando para o construtor o nome da tabela e o nome da coluna <i>chave primária</i> da tabela.

Veja abaixo um exemplo para uso em uma tabela <b>users</b>, cuja coluna <i>chave primária</i> seja <b>iduser</b>:

```php
<?php

require __DIR__ . "/../vendor/autoload.php";

$user = new \ErnandesRS\EasyCrud\EasyCrud("users", "iduser");

```

O segundo parâmetro é opcional caso a coluna <i>chave primária</i> seja <b>id</b>.

# Exemplos de consulta
Veja abaixo alguns exemplos de como realizar consultas em uma tabela de usuários.

### Recuperando um único registro

```php

$user = $user->getOne();
var_dump($user);

```

### Recuperando um único registro com algumas colunas específicas

```php

$user = $user->getOne("id, first_name, username");
var_dump($user);

```

### Recuperando um registro pela <i>chave primária</i>

```php

$user = $user->find(10);
var_dump($user);

```

### Recuperando um registro pela <i>chave primária</i> com algumas colunas específicas

```php

$user = $user->find(10, "id, first_name, username");
var_dump($user);

```

### Obtendo todos registros

```php

$all = $user->getAll();
var_dump($all);

```

### Obtendo todos registros com algumas colunas específicas

```php

$all = $user->getAll("id, first_name, username");
var_dump($all);

```

### Obter registros que atendam algumas condições com o operador AND

```php

$all = $user->where("id", "<=" , 5)->getAll();
var_dump($all);

```

### Obter registros com condições utilizando o operador OR

```php

$all = $user->where("id", "<=", 3)->orWhere("id", "=" , 10)->getAll();
var_dump($all);

```

### Limitando resultados

```php

$all = $user->limit(10)->getAll();
var_dump($all);

```

### Outros métodos

```php

public function whereNull(string $field);
public function whereNotNull(string $field);
public function orWhereNull(string $field);
public function orWhereNotNull(string $field);
public function offset(int $offset);

```

# Exemplo de inserção
Veja abaixo um exemplo de como realizar inserções de registro em uma tabela.

```php

$new = $user->create([
    "first_name" => "John"
    "last_name" => "Marinheiro"
]);
var_dump($new);

```

# Exemplo de atualização
Veja abaixo um exemplo de como atualizar um registro.

```php

$user = (new EasyCrud("users"))->find(54);
if($user) {
    $user->update([
        "first_name" => "New Name",
        "last_name" => "New Last Name",
        "username" => "newusername",
        "gender" => "m"
    ]);
 }

```

# Exemplo de exclusão
Veja abaixo um exemplo de como excluir um registro.

```php

$user = (new EasyCrud("users"))->find(54);
if($user) {
    $user->delete();
}

```