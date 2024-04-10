<?php

//region GET

/**
 * Retrieves a user from the users file based on the specified user ID and removes the sensitive fields.
 *
 * @param mixed $data The data containing the user ID to retrieve.
 * @return mixed Returns an associative array representing the found user if successful, otherwise NULL.
 */
function get_user(mixed $data): mixed
{
    $mapping_callback = function ($user) {
        unset($user['password']);

        return $user;
    };

    return get_item_from_file('id', $data['id'], USERS, $mapping_callback);
}

/**
 * Retrieves the user data for the currently authenticated user.
 *
 * @return mixed Returns an associative array representing the user data if successful, otherwise NULL.
 */

function get_self(): mixed
{
    $data = array('id' => $_SESSION['data']['id']);
    return get_user($data);
}

/**
 * Retrieves user data for all users except the currently authenticated user.
 *
 * @return mixed Returns an array containing user data for all users except the currently authenticated user if successful, otherwise NULL.
 */
function get_users_without_self(): mixed
{
    $filter_callback = function ($k, $v, $_) {
        return $v['id'] !== $_SESSION['data']['id'];
    };

    $mapping_callback = function ($_, $v, $__) {
        unset($v['password']);
        return $v;
    };

    return get_items_from_file_bulk(USERS, $filter_callback, $mapping_callback);
}

//endregion

//region POST

/**
 * Creates a new user with the provided data and stores it in the users file.
 *
 * @param mixed $data The data to create the user with. Should be an associative array containing the following keys:
 *                    - 'name': The first name of the user.
 *                    - 'surname': The last name of the user.
 *                    - 'email': The email address of the user.
 *                    - 'password': The password of the user.
 * @return mixed Returns an associative array representing the newly created user if successful, otherwise NULL.
 */
function create_user(mixed $data): mixed
{
    try {
        $id = uniqid();
        $name = $data['name'];
        $surname = $data['surname'];
        $email = strtolower($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $users_file = safely_open_json(USERS);

        if (!find_in_file('email', $email, $users_file)) {
            $res = array(
                'id' => $id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'password' => $password,
                ...set_data_log(),
            );
            array_push($users_file, $res);

            safely_overwrite_json(USERS, $users_file);

            unset($res['password']);
            return $res;
        }

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Edits the user data for the currently authenticated user.
 *
 * @param mixed $data The data to edit the user with. Should be an associative array containing the following keys:
 *                    - 'id': The ID of the currently authenticated user.
 *                    - Other keys represent the fields to update in the user.
 *                    - 'password': Optional. The new password of the user. Leave empty if not updating.
 *                    - 'confirm_password': Optional. Unused field, included for form submission.
 * @return mixed Returns the edited user data if successful, otherwise NULL.
 */
function edit_self(mixed $data): mixed
{
    $id = $data['id'];
    unset($data['id']);

    if ($id !== $_SESSION['data']['id']) {
        return 401;
    }

    if ($data['password'] === "") {
        unset($data['password']);
    } else {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    unset($data['confirm_password']);

    return update_item_from_file($id, $data, USERS);
}

/**
 * Deletes the user with the specified ID.
 *
 * @param mixed $data The data containing the ID of the user to delete.
 * @return mixed Returns true if the user is successfully deleted, otherwise false.
 */

function delete_user(mixed $data): mixed
{
    if ($data['id'] !== $_SESSION['data']['id']) {
        return 401;
    }

    return delete_item_from_file($data['id'], USERS);
}


//endregion