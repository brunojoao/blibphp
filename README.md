Blib\Sql

This is a PHP class created to generate dynamic SQL instructions securely, using placeholders to protect against SQL injection. It includes methods to generate INSERT and UPDATE queries, as well as a method to handle dates in SQL format (Y-m-d H:i:s).
Table of Contents

    Installation

    General Usage

    Available Methods
        1. insert
        2. update
        3. forceDateToSql

    Error Handling

    Usage Examples

    Important Notes

Installation

To use this class, simply download the file and include it in your PHP project using require or Composer's autoload (if you are using it).
Directly in the Project

Save the PHP file containing the Sql class in a directory in your project. For example:
plaintext
Copiar

/src/Blib/Sql.php

Then, add the following in your PHP script to load the class:
php
Copiar

require_once __DIR__ . '/src/Blib/Sql.php';

Via Composer (optional)

If you are using Composer to manage your project, you can follow your own autoload structure to automatically load connections and classes.
General Usage

    Before using the methods of the Sql class, make sure the provided data (table name, values, and criteria) are correctly formatted.

    All methods return a structure in the form of an array with the following items:
        sql: Generated SQL string.
        parameters: Associative array of parameters with values (ideal for passing to the prepare() method of PDO).

Basic Example:
php
Copiar

use Blib\Sql;

$query = Sql::insert('users', [
    'name' => 'Bruno',
    'email' => 'bruno@example.com'
]);

var_dump($query);
/*
array(2) {
  ["sql"]=>
  string(52) "INSERT INTO users (name, email) VALUES (:name, :email)"
  ["parameters"]=>
  array(2) {
    [":name"]=> string(5) "Bruno"
    [":email"]=> string(18) "bruno@example.com"
  }
}
*/

Available Methods
1. insert

Generates an SQL INSERT in the format:
sql
Copiar

INSERT INTO table_name (field1, field2, ...) VALUES (:field1, :field2, ...)

Parameters:

    string $table: Name of the table in the database.

    array $data: Data to be inserted as key (field) and value.

Return:

    array: Contains:
        sql: Generated SQL query string.
        parameters: An associative array with parameter values.

How to use:
php
Copiar

$query = Sql::insert('users', [
    'username' => 'bruno123',
    'password' => 'secure123'
]);

// Generated SQL:
echo $query['sql']; 
// INSERT INTO users (username, password) VALUES (:username, :password)

// Parameters:
print_r($query['parameters']);
/*
Array
(
    [:username] => bruno123
    [:password] => secure123
)
*/

2. update

Generates an SQL UPDATE in the format:
sql
Copiar

UPDATE table_name SET field1 = :field1, field2 = :field2 WHERE condition1 = :where_condition1

Parameters:

    string $table: Name of the table to update.

    array $data: Fields to update (key: value).

    array $criteria: Update criteria (key: value).

Return:

    array: Contains:
        sql: Generated SQL query string.
        parameters: Associative array of parameter values.

How to use:
php
Copiar

$query = Sql::update('users', [
    'username' => 'bruno_updated'
], [
    'id' => 1
]);

// Generated SQL:
echo $query['sql']; 
// UPDATE users SET username = :username WHERE id = :where_id

// Parameters:
print_r($query['parameters']);
/*
Array
(
    [:username] => bruno_updated
    [:where_id] => 1
)
*/

3. forceDateToSql

Converts dates to the standard SQL format (Y-m-d H:i:s). It can also fill in missing parts of the date, such as the time, if not provided.
Parameters:

    $field: String with the input value (accepts formats like d/m/Y or d-m-Y).

    $presetNow: true to use the current date/time as a base (fill in missing data like day or time).

    $isDateTime: true to include the time in the format as well.

Return:

    string: Date formatted in the SQL standard.

How to use:
php
Copiar

// Basic example
$date = Sql::forceDateToSql("16/01/2025", true, false);
echo $date; 
// Output: 2025-01-16

// With time
$dateTime = Sql::forceDateToSql("16/01/2025", true, true);
echo $dateTime;
// Output: 2025-01-16 00:00:00

Error Handling

The methods in the class check the following conditions before executing the logic:

    Empty or invalid table name: If the table is invalid (e.g., contains disallowed characters like - or space), an exception will be thrown.

    Empty data or criteria arrays: If the data ($data) or criteria ($criteria) array is empty, an exception will be thrown.

    Incorrect placeholders: If the generated placeholders or parameters are not valid, the process will be stopped.

Example:
php
Copiar

try {
    $query = Sql::insert('###', [
        'field1' => 'value'
    ]);
} catch (\Exception $e) {
    echo $e->getMessage();
    // Output: "Table name invalid - insert"
}

Usage Examples
Using with PDO

Since the class returns arrays with the correct parameter values and SQL, you can use it directly with PDO:
php
Copiar

use Blib\Sql;

// PDO connection setup
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');

// Generate INSERT query
$query = Sql::insert('users', [
    'name' => 'Bruno',
    'email' => 'bruno@example.com'
]);

// Prepare the SQL
$stmt = $pdo->prepare($query['sql']);

// Execute with parameters
$stmt->execute($query['parameters']);
echo "User successfully inserted.";

Important Notes

    SQL Security: All generated queries use secure placeholders (:param) to protect against SQL injection.

    Type Validation: If you provide values outside the expected range, exceptions will be thrown.

    Date Formats: Always provide dates in a format supported by the forceDateToSql function (e.g., d/m/Y, Y-m-d).
