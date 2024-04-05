<?php

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
