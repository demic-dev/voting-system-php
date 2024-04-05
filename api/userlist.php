<?php

/**
 * Function invoked to register a new user list in the system.
 * 200 if successful sign up, 400 otherwise.
 * @param mixed $user_data The user object sent from the client.
 * @return string status message of the operation.
 */
function create_userlist(mixed $data): mixed
{
    $id = uniqid();
    $name = $data['name'];
    $owner = $_SESSION['data']['id'];
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

    safely_overwrite_json(USERLISTS, $file);

    return $res;
}

function edit_userlist(mixed $data): mixed
{
    $id = $data['id'];
    unset($data['id']);

    array_push($data['users'], $_SESSION['data']['id']);

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
 * When called, this function returns the user's object.
 * 
 * @param string $id The userlist PK to find in the file.
 * @param mixed $file [OPTIONAL]: When performing batch operations. To avoid opening the file multiple times.
 * @return string|null User object.
 */
function get_userlist(mixed $data, mixed $file = NULL): mixed
{
    if (!isset($file)) {
        $file = safely_open_json(USERLISTS);
    }

    if ($res = find_in_file('id', $data['id'], $file)) {
        return $res;
    }

    return NULL;
}

/**
 * When called, this function returns all the userlists made by the selected user.
 * 
 * @param string $id The userlist's owner (Who made the list).
 * @return string An array of userlists.
 */
function get_userlists_by_owner(): mixed
{
    $owner = $_SESSION['data']['id'];
    $file = safely_open_json(USERLISTS);
    $user_file = safely_open_json(USERS);

    $res = array_filter($file, function ($v, $_) use ($owner) {
        return $v['owner'] == $owner;
    }, ARRAY_FILTER_USE_BOTH);

    $res = array_map(function ($userlist) use ($user_file) {
        return array(
            ...$userlist,
            'owner' => get_user(array('id' => $userlist['owner']), $user_file),
            'users' => array_map(function ($user) use ($user_file) {
                return get_user(array('id' => $user), $user_file);
            }, $userlist['users']),
            // add proxies
            'proxies' => array(),
        );
    }, $res);

    return $res;
}

function delete_userlist(mixed $data): bool
{
    $file = safely_open_json(USERLISTS);

    if (($index = array_search($data['id'], array_column($file, 'id'))) && $index !== False) {
        unset($file[$index]);

        return safely_overwrite_json(USERLISTS, $file);
    };

    return false;
}
