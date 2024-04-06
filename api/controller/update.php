<?php

/**
 * Updates an item with the specified ID in the JSON file with the provided data.
 *
 * @param string $id The ID of the item to update.
 * @param mixed $data The data to update the item with. Should be an associative array representing the updated fields.
 * @param string $filepath The file path of the JSON file.
 * @return mixed Returns the updated item if successful, otherwise NULL.
 */
function update_item_from_file(string $id, mixed $data, string $filepath): mixed
{
    $file = safely_open_json($filepath);

    $index = -1;
    if ($updated_file = update_in_file($id, $data, $file, $index)) {
        safely_overwrite_json($filepath, $updated_file);

        return $updated_file[$index];
    }

    return NULL;
}
