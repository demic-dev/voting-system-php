<?php

//region GET

function api_userlist_by_owner(mixed $data)
{
    // $user_file = safely_open_json(USERS);

    // $res = array_map(function ($userlist) use ($user_file) {
    //     return array(
    //         ...$userlist,
    //         'owner' => get_user(array('id' => $userlist['owner']), $user_file),
    //         'users' => array_map(function ($user) use ($user_file) {
    //             return get_user(array('id' => $user), $user_file);
    //         }, $userlist['users']),
    //         // add proxies
    //         'proxies' => array(),
    //     );
    // }, $res);
}

//endregion

//region POST

function api_edit_userlist(mixed $data)
{
    $id = $data['id'];
    unset($data['id']);

    array_push($data['users'], $_SESSION['data']['id']);

    return __edit_userlist($id, $data);
}

//endregion