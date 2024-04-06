<?php

/**
 * Retrieves an item from the JSON file based on the specified key-value pair.
 *
 * @param string $key The key to search for in the JSON file.
 * @param string $value The value corresponding to the key to search for.
 * @param string $filepath The file path of the JSON file.
 * @param ?callable $mapping_callback Optional. The callback function used to map each item. If not provided, a default mapping callback is used.
 * @return mixed Returns an associative array representing the found item if successful, otherwise NULL.
 */
function get_item_from_file(string $key, string $value, string $filepath, ?callable $mapping_callback = NULL): mixed
{
    try {
        if ($mapping_callback === NULL) {
            $mapping_callback = function ($item) {
                return $item;
            };
        }

        if (($file = safely_open_json($filepath)) && $res = find_in_file($key, $value, $file)) {
            return $mapping_callback($res);
        };

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Retrieves items from the JSON file based on the provided filter callback and optionally applies a mapping callback to each item.
 *
 * @param string $filepath The file path of the JSON file.
 * @param callable $filter_callback The callback function used to filter items. Should accept three parameters: key, value, and the entire file array.
 * @param ?callable $mapping_callback Optional. The callback function used to map each item. If not provided, a default mapping callback is used.
 * @return ?array Returns an array containing the filtered and optionally mapped items if successful, otherwise NULL.
 */
function get_items_from_file_bulk(
    string $filepath,
    callable $filter_callback,
    ?callable $mapping_callback = NULL
): ?array {
    try {
        if ($mapping_callback === NULL) {
            $mapping_callback = function ($_, $v, $__) {
                return $v;
            };
        }

        if ($file = safely_open_json($filepath)) {
            $res = [];
            foreach ($file as $k => $v) {
                if ($filter_callback($k, $v, $file)) {
                    $res[] = $mapping_callback($k, $v, $file);
                }
            }

            return $res;
        }

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}
