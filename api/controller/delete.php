<?php

/**
 * Deletes an item with the specified ID from the JSON file.
 *
 * @param string $id The ID of the item to delete.
 * @param string $filepath The file path of the JSON file.
 * @return mixed Returns true if the item is successfully deleted, otherwise false.
 */
function delete_item_from_file(string $id, string $filepath): mixed
{
    $file = safely_open_json($filepath);

    if (($index = array_search($id, array_column($file, 'id'))) && $index !== False) {
        unset($file[$index]);

        return safely_overwrite_json($filepath, $file);
    };

    return NULL;
}
