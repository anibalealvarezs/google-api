<?php

namespace Anibalealvarezs\GoogleApi\Google\Helpers;

use JetBrains\PhpStorm\NoReturn;

class Helpers
{
    /**
     * @param object $object
     * @return array
     */
    public static function getJsonableArray(
        object $object
    ): array {
        $array = [];
        foreach (get_class_vars(get_class($object)) as $key => $value) {
            if (!is_null($object->$key)) {
                if (is_object($object->$key)) {
                    if (enum_exists(get_class($object->$key))) {
                        $array[$key] = $object->$key->name;
                        continue;
                    }
                    $array[$key] = self::getJsonableArray($object->$key);
                    continue;
                } elseif (is_array($object->$key)) {
                    $array[$key] = [];
                    foreach ($object->$key as $item) {
                        $array[$key][] = (is_object($item) ? self::getJsonableArray($item) : $item);
                    }
                    continue;
                }
                $array[$key] = $object->$key;
            }
        }
        return $array;
    }

    /**
     * @param object $object
     * @param string $key
     */
    public static function nullifyOtherProperties(
        object &$object,
        string $key
    ): void {
        foreach (get_class_vars(get_class($object)) as $k => $value) {
            if ($k !== $key) {
                $object->$k = null;
            }
        }
    }

    /**
     * @param object $object
     * @param array $keys
     * @return string|int|null
     */
    public static function getFirstNotNullPropertyFrom(
        object $object,
        array $keys
    ): string|int|null {
        foreach ($keys as $key => $value) {
            if (property_exists($object, $key) && !is_null($object->$key)) {
                return $key;
            }
        }
        return null;
    }

    /**
     * @param string $string
     * @return void
     */
    #[NoReturn]
    public static function printJsonObject(
        string $string
    ): void {
        header('Content-Type: application/json');
        die($string);
    }

    /**
     * @param string $id
     * @param array $ids
     * @return string
     */
    public static function getFirstValid(
        string $id,
        array $ids
    ): string {
        if (in_array($id, $ids)) {
            return self::getFirstValid($id . '_1', $ids);
        }
        return $id;
    }

    /**
     * @param int $columnIndex
     * @return string
     */
    public static function getColumnName(
        int $columnIndex
    ): string {
        $columnName = '';
        while ($columnIndex > 0) {
            $mod = ($columnIndex - 1) % 26;
            $columnName = chr(65 + $mod) . $columnName;
            $columnIndex = floor(($columnIndex - $mod) / 26);
        }
        return $columnName;
    }

    /**
     * @param string $columnName
     * @return int
     */
    public static function getColumnIndex(
        string $columnName
    ): int {
        $columnIndex = 0;
        $length = strlen($columnName);
        for ($i = 0; $i < $length; $i++) {
            $columnIndex += (ord($columnName[$i]) - 64) * pow(26, $length - $i - 1);
        }
        return $columnIndex;
    }

    /**
     * @param string $sheetTitle
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @return string
     */
    public static function getRange(
        string $sheetTitle,
        string|int $startColumnIndex,
        int $startRowIndex,
        string|int $endColumnIndex,
        int $endRowIndex
    ): string {
        if (is_int($startColumnIndex)) {
            $startColumnIndex = self::getColumnName($startColumnIndex);
        }
        if (is_int($endColumnIndex)) {
            $endColumnIndex = self::getColumnName($endColumnIndex);
        }
        return ($sheetTitle ? '\'' . $sheetTitle . '\'!' : "") . $startColumnIndex . $startRowIndex . ':' . $endColumnIndex . $endRowIndex;
    }

    /**
     * @param array $sheets
     * @return array
     */
    public static function extractPivotTables(
        array $sheets
    ): array {
        $list = [];
        foreach ($sheets as $sheet) {
            foreach ($sheet['data'] as $data) {
                if (isset($data['rowData'])) {
                    foreach ($data['rowData'] as $row) {
                        if (isset($row['values'])) {
                            foreach ($row['values'] as $value) {
                                if (isset($value['pivotTable'])) {
                                    if (!isset($list[$sheet['properties']['sheetId']])) {
                                        $list[$sheet['properties']['sheetId']] = [];
                                    }
                                    $list[$sheet['properties']['sheetId']][] = $value['pivotTable'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $list;
    }

    /**
     * @param array $sheets
     * @param int $sourceSheet
     * @return array
     */
    public static function extractCellsFormat(
        array $sheets,
        int $sourceSheet
    ): array {
        $list = [];
        foreach ($sheets as $sheet) {
            if ($sheet['properties']['sheetId'] === $sourceSheet) {
                foreach ($sheet['data'] as $data) {
                    if (isset($data['rowData'])) {
                        foreach ($data['rowData'] as $row) {
                            $currentRow = [];
                            if (isset($row['values'])) {
                                foreach ($row['values'] as $value) {
                                    if (isset($value['userEnteredFormat'])) {
                                        $currentRow[] = $value['userEnteredFormat'];
                                        continue;
                                    }
                                    $currentRow[] = null;
                                }
                            }
                            $list[] = $currentRow;
                        }
                    }
                }
                break;
            }
        }

        return $list;
    }

    /**
     * @param int $column
     * @return string
     */
    public static function indexToA1Notation(int $column): string
    {
        if ($column <= ord('Z') - ord('A')) {
            return chr(ord('A') + $column);
        }
        return self::indexToA1Notation(intval($column / 26) - 1) . chr(ord('A') + ($column % 26));
    }
}
