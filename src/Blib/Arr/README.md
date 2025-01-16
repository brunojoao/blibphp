# BlibArray Library

BlibArray is a PHP library that provides utility methods for working with arrays. This documentation describes the `changes` method, which is the primary function of this library.

## Installation

To include this library in your project, add it to your `composer.json` file:

```json
"require": {
    "blib/blibphp": "^1.0"
}
```

Then, run:

```bash
composer install
```

## Namespace

The class `BlibArray` is located in the namespace `Blib\Array`.

```php
use Blib\Arr\BlibArray;
```

## Method: `changes`

### Description
The `changes` method compares two arrays (old and new) and returns the differences between them. If the structure or size of the arrays do not match, an exception is thrown.

### Parameters
- **`array $old`** *(optional)*: The original array.
- **`array $new`** *(optional)*: The modified array.

### Returns
- **`array`**: A multidimensional array containing only the modified elements from `$new`.

### Usage Example

```php
use Blib\Arr\BlibArray;

$old = [
    "name" => "Bruno",
    "lastname" => "Garcia",
    "age" => 300, // changed
    "address" => [
        "street" => "moon",
        "number" => 1000, // changed
        "zip" => "9999999999"
    ],
    "one" => [
        "two_one" => [
            "third_one" => "no",
            "third_two" => "no", // changed
        ],
        "two_two" => "no"
    ],
    "x" => [
        "y1" => [
            "z1" => "no",
            "z2" => "no",
        ],
        "y2" => "no" // changed
    ],
    "any" => "any"
];

$new = [
    "name" => "Bruno",
    "lastname" => "Garcia",
    "age" => 28, // changed
    "address" => [
        "street" => "moon",
        "number" => 50, // changed
        "zip" => "9999999999"
    ],
    "one" => [
        "two_one" => [
            "third_one" => "no",
            "third_two" => "yes", // changed
        ],
        "two_two" => "no"
    ],
    "x" => [
        "y1" => [
            "z1" => "no",
            "z2" => "no"
        ],
        "y2" => "yes" // changed
    ],
    "any" => "any"
];

$result = BlibArray::changes($old, $new);
print_r($result);
```

### Output

```php
Array
(
    [age] => 28
    [address] => Array
        (
            [number] => 50
        )

    [one] => Array
        (
            [two_one] => Array
                (
                    [third_two] => yes
                )
        )

    [x] => Array
        (
            [y2] => yes
        )
)
```

### Notes
- Nested arrays are compared recursively.

