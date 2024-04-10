<?php

//region GET

/**
 * Retrieves a user list from the user lists file based on the specified user list ID.
 *
 * @param mixed $data The data containing the ID of the user list to retrieve.
 * @return mixed Returns an associative array representing the found user list if successful, otherwise NULL.
 */
function get_userlist(mixed $data): mixed
{
    $mapping_callback = function ($userlist) {
        return array(
            ...$userlist,
            'owner' => get_item_from_file('id', $userlist['owner'], USERS),
            'users' => get_items_from_file_bulk(USERS, function ($_, $user, $__) use ($userlist) {
                return in_array($user['id'], $userlist['users']);
            }),
        );
    };

    return get_item_from_file('id', $data['id'], USERLISTS, $mapping_callback);
}

/**
 * Retrieves user lists owned by the currently authenticated user.
 *
 * @return mixed Returns an array containing user lists owned by the currently authenticated user if successful, otherwise NULL.
 */
function userlists_by_self()
{
    $filter_callback = function ($k, $v, $_) {
        return $v['owner'] === $_SESSION['data']['id'];
    };

    $mapping_callback = function ($_, $userlist) {
        return array(
            ...$userlist,
            'owner' => get_item_from_file('id', $userlist['owner'], USERS),
            'users' => get_items_from_file_bulk(USERS, function ($_, $user, $__) use ($userlist) {
                return in_array($user['id'], $userlist['users']);
            }),
        );
    };

    return get_items_from_file_bulk(USERLISTS, $filter_callback, $mapping_callback);
}

//endregion

//region POST

/**
 * Creates a new user list with the provided data and stores it in the user lists file.
 *
 * @param mixed $data The data to create the user list with. Should be an associative array containing the following keys:
 *                    - 'name': The name of the user list.
 *                    - 'users': An array of user IDs associated with the user list.
 *                    - 'proxies': An array of proxy IDs associated with the user list.
 * @return mixed Returns an associative array representing the newly created user list if successful, otherwise NULL.
 */
function create_userlist(mixed $data): mixed
{
    try {
        $id = uniqid();
        $name = $data['name'];
        $users = $data['users'];
        $owner = $_SESSION['data']['id'];
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
 * @return mixed Returns the edited user list if successful, otherwise NULL.
 */
function edit_userlist(mixed $data): mixed
{
    $id = $data['id'];
    unset($data['id']);

    array_push($data['users'], $_SESSION['data']['id']);

    if ($userlist = get_item_from_file('id', $id, USERLISTS)) {
        if ($userlist['owner'] !== $_SESSION['data']['id']) {
            return 401;
        }

        return update_item_from_file($id, $data, USERLISTS);
    }

    return 404;
}

/**
 * Deletes the user list with the specified ID.
 *
 * @param mixed $data The data containing the ID of the user list to delete.
 * @return mixed Returns true if the user list is successfully deleted, otherwise false.
 */
function delete_userlist(mixed $data): mixed
{
    if ($data['owner'] !== $_SESSION['data']['id']) {
        return 401;
    }

    return delete_item_from_file($data['id'], USERLISTS);
}

//endregion