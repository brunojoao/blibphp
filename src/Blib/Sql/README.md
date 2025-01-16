# BlibSql

BlibSql is a PHP library that provides methods to generate SQL statements dynamically. It includes functionality for creating INSERT and UPDATE statements with placeholders and corresponding parameters, as well as converting date strings to SQL format.

## Table of Contents

- Installation
- Usage
  - Insert Statement
  - Update Statement
  - Date Conversion
- Methods
  - insert
  - update
  - forceDateToSql
- Exceptions

## Installation

To include BlibSql in your project, you can use Composer:

```bash
composer require blib/blibsql
```

Or you can manually include the `BlibSql.php` file in your project.

## Usage

### Insert Statement

To generate an INSERT statement, use the `insert` method:

```php
use Blib\BlibSql;

$table = 'users';
$data = [
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password' => 'secret'
];

try {
    $result = BlibSql::insert($table, $data);
    echo $result['sql']; // INSERT INTO users (username, email, password) VALUES (:username, :email, :password)
    print_r($result['parameters']); // Array ( [:username] => john_doe [:email] => john@example.com [:password] => secret )
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

### Update Statement

To generate an UPDATE statement, use the `update` method:

```php
use Blib\BlibSql;

$table = 'users';
$data = [
    'email' => 'john_new@example.com'
];
$criteria = [
    'username' => 'john_doe'
];

try {
    $result = BlibSql::update($table, $data, $criteria);
    echo $result['sql']; // UPDATE users SET email = :email WHERE username = :where_username
    print_r($result['parameters']); // Array ( [:email] => john_new@example.com [:where_username] => john_doe )
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

### Date Conversion

To convert a date string to SQL format, use the `forceDateToSql` method:

```php
use Blib\BlibSql;

$dateString = '16/01/2025';

$sqlDate = BlibSql::forceDateToSql($dateString);
echo $sqlDate; // 2025-01-16

$dateTimeString = '16/01/2025 15:00:00';

$sqlDateTime = BlibSql::forceDateToSql($dateTimeString, true, true);
echo $sqlDateTime; // 2025-01-16 15:00:00
```

## Methods

### insert

Generates an SQL INSERT statement with placeholders and corresponding parameters.

**Parameters**:

- `string $table`: The name of the table to insert into.
- `array $data`: An associative array of column-value pairs to be inserted.

**Returns**:

- `array`: An associative array containing the generated SQL statement and the parameters for the placeholders.

**Throws**:

- `\Exception` if the data array is empty.
- `\Exception` if the table name is empty or invalid.
- `\Exception` if the placeholders for the VALUES clause are empty.

### update

Generates an SQL UPDATE statement with placeholders and corresponding parameters.

**Parameters**:

- `string $table`: The name of the table to update.
- `array $data`: An associative array of column-value pairs to be updated.
- `array $criteria`: An associative array of column-value pairs to specify the conditions for the update.

**Returns**:

- `array`: An associative array containing the generated SQL statement and the parameters for the placeholders.

**Throws**:

- `\Exception` if the data array is empty.
- `\Exception` if the table name is empty or invalid.
- `\Exception` if the criteria array is empty.
- `\Exception` if the placeholders for the SET or WHERE clauses are empty.

### forceDateToSql

Converts a date string to SQL format.

**Parameters**:

- `string $field`: The date string to be converted.
- `bool $presetNow` (optional): Determines whether the current date and time should be used to complete the string if it is incomplete. Default is true.
- `bool $isDateTime` (optional): Determines whether the string should be treated as a date and time. If true, the function will ensure the string is in the complete date and time format. Default is true.

**Returns**:

- `string`: The converted date in SQL format.

## Exceptions

BlibSql methods throw `\Exception` in the following cases:

- If the data array is empty.
- If the table name is empty or invalid.
- If the criteria array is empty (for `update` method).
- If the placeholders for the VALUES, SET, or WHERE clauses are empty.
