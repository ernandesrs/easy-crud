# Installation
The easiest way to install is via composer:

> composer require ernandesrs/easy-crud

# Configuration
The configuration is quite simple, you just need to create a database and define the constant below in your project settings. It has the necessary information and configurations to access your database.

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

# Usage
Usage is also quite simple, just create an instance of <b>EasyCrud</b>, passing the name of the table and the name of the table's <i>primary key</i> column to the constructor.

See below an example for use in a <b>users</b> table, whose <i>primary key</i> column is <b>iduser</b>:

```php
<?php

require __DIR__ . "/../vendor/autoload.php";

$user = new \ErnandesRS\EasyCrud\EasyCrud("users", "iduser");

```

The second parameter is optional if the column <i>primary key</i> is <b>id</b>.

# Query examples
Below are some examples of how to query a user table.

### Retrieving a single record

```php

$user = $user->getOne();
var_dump($user);

```

### Retrieving a single record with some specific columns

```php

$user = $user->getOne("id, first_name, username");
var_dump($user);

```

### Retrieving a record by <i>primary key</i>

```php

$user = $user->find(10);
var_dump($user);

```

### Retrieving a record by <i>primary key</i> with some specific columns

```php

$user = $user->find(10, "id, first_name, username");
var_dump($user);

```

### Getting all records

```php

$all = $user->getAll();
var_dump($all);

```

### Getting all records with some specific columns

```php

$all = $user->getAll("id, first_name, username");
var_dump($all);

```

### Get records that meet some conditions with the AND operator

```php

$all = $user->where("id", "<=" , 5)->getAll();
var_dump($all);

```

### Get records with conditions using the OR operator

```php

$all = $user->where("id", "<=", 3)->orWhere("id", "=" , 10)->getAll();
var_dump($all);

```

# Insert example
See below an example of how to perform record insertions in a table.

```php

$new = $user->create([
    "first_name" => "John"
    "last_name" => "Marinheiro"
]);
var_dump($new);

```

# Update example
Below is an example of how to update a record.

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

# Exclusion example
See below for an example of how to delete a record.

```php

$user = (new EasyCrud("users"))->find(54);
if($user) {
    $user->delete();
}

```