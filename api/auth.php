<?php

/**
 * If the correct email and password are passed, authenticate the user and start a new session.
 * @param mixed $data The user info object.
 * @return bool Whether it has started a session or not.
 */
function login(mixed $data): mixed
{
    $file = safely_open_json(USERS);

    if ($user = find_in_file('email', $data['email'], $file)) {
        if (password_verify($data['password'], $user['password'])) {
            $res = array(...$user, 'password' => NULL);
            unset($res['password']);
            session_start();
            $_SESSION['data'] = $res;

            return true;
        }
    }

    return 401;
}


function logout(): bool
{
    return session_unset() && session_destroy();
}
