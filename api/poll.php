<?php

function __create_options(array $options): mixed
{
    return array_map(function ($k, $v) {
        return array(
            'id' => uniqid(),
            'text' => $v,
            'count' => 0
        );
    }, array_keys($options), $options);
}

function create_poll(mixed $data): mixed
{
    $id = uniqid();
    $name = $data['name'];
    $description = $data['description'];
    $options = __create_options($data['options']);
    $publickey = $data['public_key'];
    // ISO DATE STRING: 2024-03-14T08:20:40.345Z
    $start_date = $data['start_date'];
    $due_date = $data['due_date'];
    $userlist = $data['userlist'];

    $file = safely_open_json(POLLS);

    $res = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'options' => $options,
        'owner' => $_SESSION['data']['id'],
        'public_key' => $publickey,
        'start_date' => $start_date,
        'due_date' => $due_date,
        'userlist' => $userlist,
        'voted_by' => array(),
        'votes' => [],
        ...set_data_log(),
    );
    array_push($file, $res);
    safely_overwrite_json(POLLS, $file);

    return $res;
}

function get_poll_by_id(mixed $data, mixed $file = NULL): mixed
{
    if (!isset($file)) {
        $file = safely_open_json(POLLS);
    }
    if ($res = find_in_file('id', $data['id'], $file)) {
        $users_file = safely_open_json(USERS);

        $res['owner'] = find_in_file('id', $res['owner'], $users_file);
        $res['has_voted'] = in_array($_SESSION['data']['id'], $res['voted_by']);

        return $res;
    }

    return NULL;
}

function get_poll_by_owner(): mixed
{
    $file = safely_open_json(POLLS);
    $userlists_file = safely_open_json(USERLISTS);
    $users_file = safely_open_json(USERS);
    $owner = $_SESSION['data']['id'];

    $res = [];

    foreach ($file as $_ => $value) {
        if ($value['owner'] === $owner) {
            $value['owner'] = find_in_file('id', $value['owner'], $users_file);
            $value['userlist'] = find_in_file('id', $value['userlist'], $userlists_file);

            array_push($res, $value);
        }
    }

    /* TO-DO: USERS DETAILS (authorised_users) */

    return $res;
}

function get_polls_by_user(): mixed
{
    $file = safely_open_json(POLLS);
    $userlists_file = safely_open_json(USERLISTS);
    $users_file = safely_open_json(USERS);
    $user_id = $_SESSION['data']['id'];

    $active_polls = [];
    $ended_polls = [];

    foreach ($file as $_ => $value) {
        if (($userlist = find_in_file('id', $value['userlist'], $userlists_file)) && in_array($user_id, $userlist['users'])) {
            $value['owner'] = find_in_file('id', $value['owner'], $users_file);
            if (strtotime(date($value['due_date'])) >= time()) {
                array_push($active_polls, $value);
            } else {
                array_push($ended_polls, $value);
            }
        }
    }

    /* TO-DO: USERS DETAILS (authorised_users) */

    return array(
        'active_polls' => $active_polls,
        'ended_polls' => $ended_polls,
    );
}

function edit_poll(mixed $data): mixed
{
    $file = safely_open_json(POLLS);


    $id = $data['id'];
    unset($data['id']);

    if ($old = find_in_file('id', $id, $file)) {
        foreach ($data as $k => $v) {
            // I'm looking for difference between the old options and the new one. I add/remove only the different ones.
            if ($k === 'options') {
                if (count($data[$k]) > count($old[$k])) {
                    $diff_options = array_udiff($data[$k], $old[$k], function ($first, $second) {
                        $option1 = is_string($first) ? $first : $first['text'];
                        $option2 = is_string($second) ? $second : $second['text'];

                        return $option1 <=> $option2;
                    });
                    $data[$k] = array_merge($old[$k], __create_options($diff_options));
                } elseif (count($data[$k]) < count($old[$k])) {
                    $diff_options = array_udiff($old[$k], $data[$k], function ($first, $second) {
                        $option1 = is_string($first) ? $first : $first['text'];
                        $option2 = is_string($second) ? $second : $second['text'];

                        return $option1 <=> $option2;
                    });

                    $data[$k] = __create_options(array_filter($old[$k], function ($v) use ($diff_options) {
                        return !in_array($v['text'], array_column($diff_options, 'text'));
                    }));
                } else {
                    unset($data[$k]);
                }
            } elseif ($v === $old[$k]) {
                unset($data[$k]);
            }
        }
    }

    if ($res = update_in_file($id, $data, $file)) {
        return safely_overwrite_json(POLLS, $res) ? $res : NULL;
    }

    return NULL;
}

function delete_poll(mixed $data): mixed
{
    $file = safely_open_json(POLLS);

    if (($index = array_search($data['id'], array_column($file, 'id'))) && $index !== False) {
        unset($file[$index]);

        return safely_overwrite_json(POLLS, $file);
    };

    return false;
}
