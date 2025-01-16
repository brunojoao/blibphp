<?php

namespace Blib;

class Sql
{
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

    public static function forceDateToSql(string $field, bool $presetNow = true, bool $isDateTime = false): string
    {
        $field = str_replace("/", "-", $field);
        if (strpos($field, 'T') !== false) {
            $field = str_replace('T', ' ', $field);
        }
        if ($isDateTime && strlen($field) < 19) {
            $l = strlen($field);
            $c = $presetNow ? date("Y-m-d h:i:s") : date("Y-m-d 00:00:00") ;
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
