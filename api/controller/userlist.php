<?php

/**
 * Creates a new user list with the provided data and stores it in the user lists file.
 *
 * @param mixed $data The data to create the user list with. Should be an associative array containing the following keys:
 *                    - 'name': The name of the user list.
 *                    - 'users': An array of user IDs associated with the user list.
 *                    - 'proxies': An array of proxy IDs associated with the user list.
 * @return mixed Returns an associative array representing the newly created user list if successful, otherwise NULL.
 */
function __create_userlist(string $owner, mixed $data): mixed
{
    try {
        $id = uniqid();
        $name = $data['name'];
        $users = $data['users'];
        $proxies = $data['proxies'];

        array_push($users, $owner);

        $file = safely_open_json(USERLISTS);

        $res = array(
            'id' => $id,
            'name' => $name,
            'owner' => $owner,
            'users' => $users,
            'proxies' => $proxies,
            ...set_data_log(),
        );
        array_push($file, $res);

        if (safely_overwrite_json(USERLISTS, $file)) {
            return $res;
        };

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Edits an existing user list with the provided data and updates it in the user lists file.
 *
 * @param mixed $data The data to edit the user list with. Should be an associative array containing the following keys:
 *                    - 'id': The ID of the user list to edit.
 *                    - Other keys represent the fields to update in the user list.
 * @return mixed Returns an associative array representing the edited user list if successful, otherwise NULL.
 */
function __edit_userlist(string $id, mixed $data): mixed
{
    $file = safely_open_json(USERLISTS);

    if ($res = update_in_file($id, $data, $file)) {
        if (safely_overwrite_json(USERLISTS, $res)) {
            return $res;
        };

        return NULL;
    }

    return NULL;
}

/**
 * Retrieves a user list from the user lists file based on the specified key-value pair.
 *
 * @param string $key The key to search for in the user lists. For example, 'id' or 'name'.
 * @param string $value The value corresponding to the key to search for.
 * @param mixed $file Optional. The user lists file to search in. If not provided, the default user lists file will be used.
 * @return mixed Returns an associative array representing the found user list if successful, otherwise NULL.
 */
function __get_userlist(string $key, string $value, mixed $file = NULL): mixed
{
    if (!isset($file)) {
        $file = safely_open_json(USERLISTS);
    }

    if ($res = find_in_file($key, $value, $file)) {
        return $res;
    }

    return NULL;
}

/**
 * Retrieves user lists owned by the specified owner from the user lists file.
 *
 * @param string $owner The ID of the owner whose user lists are to be retrieved.
 * @return mixed Returns an array containing all user lists owned by the specified owner if found, otherwise an empty array.
 */
function __get_userlists_by_owner(string $owner): mixed
{
    $file = safely_open_json(USERLISTS);

    $res = array_filter($file, function ($v, $_) use ($owner) {
        return $v['owner'] == $owner;
    }, ARRAY_FILTER_USE_BOTH);

    return $res;
}

/**
 * Deletes a user list with the specified ID from the user lists file.
 *
 * @param string $id The ID of the user list to delete.
 * @return bool Returns true if the user list is successfully deleted, otherwise false.
 */
function __delete_userlist(string $id): bool
{
    $file = safely_open_json(USERLISTS);

    if (($index = array_search($id, array_column($file, 'id'))) && $index !== False) {
        unset($file[$index]);

        return safely_overwrite_json(USERLISTS, $file);
    };

    return false;
}
