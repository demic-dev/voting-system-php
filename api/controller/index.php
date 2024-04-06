<?php

/**
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Controller for the basics operations to the files.                  *
 * The functions here, directly talks with the file.                   *
 *                                                                     *
 * They expect to receive the data with the same file structure,       *
 * so the input will be already sanitised.                             *
 *                                                                     *
 * Every direct call could potentially break the file structure and,   *
 * consequently, the site page.                                        *
 *                                                                     *
 * These controllers starts with two __ at the beginning.              *
 *                                                                     *
 * A function can return:                                              *
 * - The new/updated data;                                             *
 * - NULL if the resource is not found;                                *
 * - False if the resource is found but the action is not successful;  *
 * - True if there is nothing to return and the action is successful;  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
 */

/**
 * Safely opens and reads the contents of a JSON file.
 *
 * @param string $filepath The file path of the JSON file to open.
 * @return mixed Returns the decoded JSON data as an associative array if successful, otherwise NULL.
 */
function safely_open_json(string $filepath): mixed
{
    try {
        $file = file_get_contents($filepath);
        $data = json_decode($file, true);

        return $data;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Safely overwrites the contents of a JSON file with the provided data.
 *
 * @param string $filepath The file path of the JSON file to overwrite.
 * @param mixed $data The data to write to the JSON file.
 * @return bool Returns true if the JSON file is successfully overwritten, otherwise false.
 */
function safely_overwrite_json(string $filepath, mixed $data)
{
    try {
        $jsonEncoded = json_encode($data);
        if (file_put_contents($filepath, $jsonEncoded)) {
            return true;
        }

        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Generates data log entries for a newly created or updated item.
 *
 * @param ?bool $only_update Optional. Specifies whether to generate data log entries only for updates. Defaults to false.
 * @return mixed Returns an associative array containing data log entries with creation and update timestamps if not only updating, otherwise with only update timestamp.
 */
function set_data_log(?bool $only_update = false): mixed
{
    $now = (new DateTime())->format(DateTime::ATOM);
    if ($only_update) {
        return array(
            UPDATED_AT => $now,
        );
    }

    return array(
        CREATED_AT => $now,
        UPDATED_AT => $now,
    );
}

/**
 * Searches for a specific value in the file content array based on the specified key.
 *
 * @param string $key The key to search for in the file content array.
 * @param string $value The value corresponding to the key to search for.
 * @param mixed $filecontent The content array of the file where the search is performed.
 * @return mixed Returns the found item if successful, otherwise NULL.
 */
function find_in_file(string $key, string $value, mixed $filecontent): mixed
{
    $idx = array_search($value, array_column($filecontent, $key));

    if ($idx !== False) {
        return $filecontent[$idx];
    }

    return NULL;
}

/**
 * Updates an item in the file content array with the provided data based on the specified ID.
 *
 * @param string $id The ID of the item to update.
 * @param mixed $data The data to update the item with. Should be an associative array representing the updated fields.
 * @param mixed $filecontent The content array of the file where the item is stored.
 * @param mixed|null &$index Optional. Reference to store the index of the updated item. Defaults to NULL.
 * @return mixed Returns the updated file content array if successful, otherwise NULL.
 */
function update_in_file(string $id, mixed $data, mixed $filecontent, mixed &$index = NULL): mixed
{
    $idx = array_search($id, array_column($filecontent, 'id'));

    if ($idx !== False) {
        $filecontent[$idx] = array(
            ...$filecontent[$idx],
            ...set_data_log(true),
            ...$data,
        );

        $index = $idx;
        return $filecontent;
    }

    return NULL;
}
