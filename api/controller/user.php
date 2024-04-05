<?php

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
function __create_user(mixed $data): mixed
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
 * Retrieves a user from the users file based on the specified user ID.
 *
 * @param mixed $data The data containing the user ID to retrieve.
 * @param mixed $users_file Optional. The users file to search in. If not provided, the default users file will be used.
 * @return mixed Returns an associative array representing the found user if successful, otherwise NULL.
 */
function __get_user(string $id, mixed $users_file = NULL): mixed
{

    try {
        if (!isset($users_file)) {
            $users_file = safely_open_json(USERS);
        }

        if ($res = find_in_file('id', $id, $users_file)) {
            unset($res['password']);
            return $res;
        }

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Edits an existing user with the provided data and updates it in the users file.
 *
 * @param mixed $data The data to edit the user with. Should be an associative array containing the following keys:
 *                    - 'id': The ID of the user to edit.
 *                    - Other keys represent the fields to update in the user.
 *                    - 'password': Optional. The new password of the user. Leave empty if not updating.
 *                    - 'confirm_password': Optional. Unused field, included for form submission.
 * @return mixed Returns an associative array representing the edited user if successful, otherwise NULL.
 */
function __edit_user(string $id, mixed $data): mixed
{
    // $id = $data['id'];
    // unset($data['id']);

    $file = safely_open_json(USERS);

    // if ($data['password'] === "") {
    //     unset($data['password']);
    // }

    // unset($data['confirm_password']);

    if ($res = update_in_file($id, $data, $file)) {
        safely_overwrite_json(USERS, $res);

        return $res;
    }

    return NULL;
}

/**
 * Deletes a user with the specified ID from the users file.
 *
 * @param string $id The ID of the user to delete.
 * @return mixed Returns true if the user is successfully deleted, otherwise false.
 */
function __delete_user(string $id): mixed
{
    $file = safely_open_json(USERS);

    if (($index = array_search($id, array_column($file, 'id'))) && $index !== False) {
        unset($file[$index]);

        return safely_overwrite_json(USERS, $file);
    };

    return NULL;
}
