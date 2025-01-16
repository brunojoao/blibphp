<?php

namespace Blib\Sql;

class BlibSql
{
    /**
     * Generates an SQL INSERT statement with placeholders and corresponding parameters.
     *
     * This function constructs an SQL INSERT statement for a given table with specified data.
     * It ensures that the table name and data are valid, and throws exceptions for various error conditions.
     *
     * @param string $table The name of the table to insert into. It must be a non-empty string containing only alphanumeric characters and underscores.
     * @param array $data An associative array of column-value pairs to be inserted. The keys are the column names and the values are the values to be inserted.
     *
     * @return array An associative array containing the generated SQL statement and the parameters for the placeholders.
     *               - 'sql': The generated SQL INSERT statement as a string.
     *               - 'parameters': An associative array of placeholders and their corresponding values.
     *
     * @throws \Exception If the data array is empty.
     * @throws \Exception If the table name is empty or invalid.
     * @throws \Exception If the placeholders for the VALUES clause are empty.
     */
    public static function insert(string $table, array $data = []): array
    {
        if (empty($data)) {
            throw new \Exception("Empty data - " . __FUNCTION__);
        } elseif (empty($table)) {
            throw new \Exception("Table name null - " . __FUNCTION__);
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \Exception("Table name invalid - " . __FUNCTION__);
        }

        $fields = array_keys($data);
        $placeholders = array_map(function ($field) {
            return ':' . $field;
        }, $fields);

        $parameters = [];
        foreach ($data as $key => $value) {
            $parameters[':' . $key] = $value;
        }

        if (empty($placeholders)) {
            throw new \Exception("Wrong placeholders - " . __FUNCTION__);
        }

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        return compact('sql', 'parameters');
    }

    /**
     * Generates an SQL UPDATE statement with placeholders and corresponding parameters.
     *
     * This function constructs an SQL UPDATE statement for a given table with specified data and criteria.
     * It ensures that the table name and data are valid, and throws exceptions for various error conditions.
     *
     * @param string $table The name of the table to update. It must be a non-empty string containing only alphanumeric characters and underscores.
     * @param array $data An associative array of column-value pairs to be updated. The keys are the column names and the values are the new values for those columns.
     * @param array $criteria An associative array of column-value pairs to specify the conditions for the update. The keys are the column names and the values are the values for the conditions.
     *
     * @return array An associative array containing the generated SQL statement and the parameters for the placeholders.
     *               - 'sql': The generated SQL UPDATE statement as a string.
     *               - 'parameters': An associative array of placeholders and their corresponding values.
     *
     * @throws \Exception If the data array is empty.
     * @throws \Exception If the table name is empty or invalid.
     * @throws \Exception If the criteria array is empty.
     * @throws \Exception If the placeholders for the SET or WHERE clauses are empty.
     */
    public static function update(string $table, array $data = [], array $criteria = []): array
    {
        if (empty($data)) {
            throw new \Exception("Empty data - " . __FUNCTION__);
        } elseif (empty($table)) {
            throw new \Exception("Table name null - " . __FUNCTION__);
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \Exception("Invalid table name - " . __FUNCTION__);
        }

        if (empty($criteria)) {
            throw new \Exception("Empty criteria - " . __FUNCTION__);
        }

        $fields = array_keys($data);
        $setPlaceholders = array_map(function ($field) {
            return $field . ' = :' . $field;
        }, $fields);

        $criteriaPlaceholders = array_map(function ($field) {
            return $field . ' = :where_' . $field;
        }, array_keys($criteria));

        $parameters = [];
        foreach ($data as $key => $value) {
            $parameters[':' . $key] = $value;
        }
        foreach ($criteria as $key => $value) {
            $parameters[':where_' . $key] = $value;
        }

        if (empty($setPlaceholders) || empty($criteriaPlaceholders)) {
            throw new \Exception("Placeholders wrong - " . __FUNCTION__);
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            implode(', ', $setPlaceholders),
            implode(' AND ', $criteriaPlaceholders)
        );

        return compact('sql', 'parameters');
    }

    /**
     * Converts a date string to SQL format.
     *
     * This function takes a date string and converts it to the SQL format (YYYY-MM-DD).
     * If the string contains a date and time, it can be adjusted to the complete SQL date and time format (YYYY-MM-DD HH:MM:SS).
     *
     * @param string $field The date string to be converted. It can be in the format DD/MM/YYYY or with different separators.
     * @param bool $presetNow (optional) Determines whether the current date and time should be used to complete the string if it is incomplete. Default is true.
     * @param bool $isDateTime (optional) Determines whether the string should be treated as a date and time. If true, the function will ensure the string is in the complete date and time format. Default is false.
     *
     * @return string The converted date in SQL format (YYYY-MM-DD) or, if $isDateTime is true, in the complete SQL date and time format (YYYY-MM-DD HH:MM:SS).
     */
    public static function forceDateToSql(string $field, bool $presetNow = true, bool $isDateTime = false): string
    {
        //TODO: add DateTime detector and convert
        $field = str_replace("/", "-", $field);
        if (strpos($field, 'T') !== false) {
            $field = str_replace('T', ' ', $field);
        }
        if ($isDateTime && strlen($field) < 19) {
            $l = strlen($field);
            $c = $presetNow ? date("Y-m-d h:i:s") : date("Y-m-d 00:00:00");
            $cut = substr($c, $l);
            $field .= $cut;
        }
        if (strpos(substr($field, 0, 4), '-')) {
            $e = explode('-', $field);
            $dateTime = $isDateTime ? " " . trim(substr($e[2], 4)) : "";
            $field = substr($e[2], 0, 4) . "-" . $e[1] . "-" . $e[0] . $dateTime;
        }
        return trim($field);
    }
}
