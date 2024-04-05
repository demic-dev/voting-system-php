<?php

/**
 * Function invoked to register a new user in the system.
 * 200 if successful sign up, 400 otherwise.
 * @param mixed $data The user object sent from the client.
 * @return string|null Created user object.
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
 * @return mixed Return the user object of the authenticated user.
 */
function get_self(): mixed
{
    return get_user(array('id' => $_SESSION['data']['id']));
}

/**
 * Invoked to get the user data, given his email.
 * @param string $key PK of the user to get.
 * @param string $value PK's value of the user to get.
 * @param mixed $users_file [OPTIONAL] the users array in case of batch operations, to not open the file _n_ times.
 * @return mixed user's object if present, NULL otherwise.
 */
function get_user(mixed $data, mixed $users_file = NULL): mixed
{

    try {
        if (!isset($users_file)) {
            $users_file = safely_open_json(USERS);
        }

        if ($res = find_in_file('id', $data['id'], $users_file)) {
            unset($res['password']);
            return $res;
        }

        return NULL;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Invoked to update user's info on the db.
 * @param string $id id of the user to update.
 * @param mixed $data fields to update.
 * @return mixed update user object.
 */
function edit_user(mixed $data): mixed
{
    $id = $data['id'];
    unset($data['id']);

    $file = safely_open_json(USERS);

    if ($data['password'] === "") {
        unset($data['password']);
    }

    unset($data['confirm_password']);

    if ($res = update_in_file($id, $data, $file)) {
        safely_overwrite_json(USERS, $res);

        return $res;
    }

    return NULL;
}

function edit_self(mixed $data): mixed
{
    $payload = array(...$data, 'id' => $_SESSION['data']['id']);

    if (edit_user($payload)) {
        $res = get_user($payload);
        $_SESSION['data'] = $res;

        return $res;
    }


    return NULL;
}


function get_users_without_self(): mixed
{
    $file = safely_open_json(USERS);

    foreach ($file as $index => $user) {
        if ($user['id'] === $_SESSION['data']['id']) {
            unset($file[$index]);
        } else {
            unset($user['password']);
        }
    }

    return $file;
}
