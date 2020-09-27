# sy/db

Database layer based on PDO

## Installation

Install the latest version with

```bash
$ composer require sy/db
```

## Basic Usage

```php
<?php

use Sy\Db\Gate;

// connection
$this->gate = new Gate('sqlite:' . __DIR__ . '/database.db');

// create table
$this->gate->execute('
	CREATE TABLE test_table (
		id INTEGER PRIMARY KEY,
		name TEXT NOT NULL
	)
');

// insert
$this->gate->insert('test_table', ['id' => 1, 'name' => 'hello']);

// select
$res = $this->gate->queryAll('SELECT * FROM test_table', PDO::FETCH_ASSOC);
print_r($res);
```

Output

```
Array
(
    [0] => Array
        (
            [id] => 1
            [name] => hello
        )
)
```