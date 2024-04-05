<?php

/**
 * @return mixed Return the user object of the authenticated user.
 */
function get_self(): mixed
{
    return __get_user($_SESSION['data']['id']);
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
