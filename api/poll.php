<?php

/**
 * @source ./controller/get.php
 * Since the bulk get and the normal get requires two different set of params,
 * this function wraps them in order to not repeat code and increase consistency.
 * 
 * @param mixed $poll The poll to parse
 * @return mixed The poll parsed
 */
function __parse_poll(mixed $poll): mixed
{
    $userlist = get_userlist(array('id' => $poll['userlist']));
    $proxy_0 = $userlist['proxies'][$_SESSION['data']['id']][0];
    $proxy_1 = $userlist['proxies'][$_SESSION['data']['id']][1];

    $poll['userlist'] = $userlist;
    $poll['owner'] = get_user(array('id' => $poll['owner']));

    $poll['has_voted'] = in_array($_SESSION['data']['id'], $poll['voted_by']);
    $poll['has_proxy_0'] = in_array($proxy_0, $poll['voted_by']);
    $poll['has_proxy_1'] = in_array($proxy_1, $poll['voted_by']);

    $poll['users'] = count($userlist['users']);
    $poll['voted_by'] = count($poll['voted_by']);


    return $poll;
}

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

//region GET

/**
 * Retrieves a poll from the polls file based on the specified poll ID and maps additional data such as owner information and whether the current user has voted.
 *
 * @param mixed $data The data containing the ID of the poll to retrieve.
 * @return mixed Returns an associative array representing the found poll with additional mapped data if successful, otherwise NULL.
 */
function get_poll(mixed $data): mixed
{
    $mapping_callback = function ($poll) {
        return __parse_poll($poll);
    };

    if (($poll = get_item_from_file('id', $data['id'], POLLS, $mapping_callback))) {
        if (in_array($_SESSION['data']['id'], array_column($poll['userlist']['users'], 'id'))) {
            return $poll;
        }

        return 401;
    }

    return 404;
}

/**
 * Retrieves polls owned by the currently authenticated user and maps additional data such as owner information and whether the current user has voted.
 *
 * @return mixed Returns an array containing polls owned by the currently authenticated user with additional mapped data if successful, otherwise NULL.
 */
function get_polls_by_self(): mixed
{
    $filter_callback = function ($_, $v) {
        return $v['owner'] === $_SESSION['data']['id'];
    };
    $mapping_callback = function ($_, $poll) {
        return __parse_poll($poll);
    };

    return get_items_from_file_bulk(POLLS, $filter_callback, $mapping_callback);
}

/**
 * Retrieves polls belonging to the currently authenticated user, categorized into active and ended polls, and maps additional data such as owner information and whether the current user has voted.
 *
 * @return mixed Returns an array containing active and ended polls belonging to the currently authenticated user with additional mapped data if successful, otherwise NULL.
 */
function get_polls_per_user(): mixed
{
    $user_id = $_SESSION['data']['id'];

    $filter_callback_active = function ($_, $v) use ($user_id) {
        // I search for the polls where the user is in the list and that are active.
        return ($userlist = get_userlist(array('id' => $v['userlist'])))
            && in_array($user_id, array_column($userlist['users'], 'id'))
            && strtotime(date($v['due_date'])) >= time();
    };
    $filter_callback_ended = function ($_, $v) use ($user_id) {
        // I search for the polls where the user is in the list and that are inactive.
        return ($userlist = get_userlist(array('id' => $v['userlist'])))
            && in_array($user_id, array_column($userlist['users'], 'id'))
            && strtotime(date($v['due_date'])) < time();
    };

    $mapping_callback = function ($_, $poll) {
        return __parse_poll($poll);
    };

    $active_polls = get_items_from_file_bulk(POLLS, $filter_callback_active, $mapping_callback);
    $ended_polls = get_items_from_file_bulk(POLLS, $filter_callback_ended, $mapping_callback);

    /* TO-DO: USERS DETAILS (authorised_users) */

    return array(
        'active_polls' => $active_polls,
        'ended_polls' => $ended_polls,
    );
}

//endregion

//region POST

/**
 * Creates a new poll with the provided data and stores it in the polls file.
 *
 * @param mixed $data The data to create the poll with. Should be an associative array containing the following keys:
 *                    - 'name': The name of the poll.
 *                    - 'description': The description of the poll.
 *                    - 'options': An array containing options for the poll.
 *                    - 'public_key': The public key associated with the poll.
 *                    - 'start_date': The start date of the poll in ISO format (e.g., 2024-03-14T08:20:40.345Z).
 *                    - 'due_date': The due date of the poll in ISO format (e.g., 2024-03-14T08:20:40.345Z).
 *                    - 'userlist': The ID of the user list associated with the poll.
 * @return mixed Returns an associative array representing the newly created poll if successful, otherwise NULL.
 */
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

    if ($file = safely_open_json(POLLS)) {
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
            'voted_by' => [],
            'proxies' => [],
            'votes' => [],
            'closed' => false,
            ...set_data_log(),
        );
        array_push($file, $res);
        if (safely_overwrite_json(POLLS, $file)) {
            return $res;
        }

        return 500;
    }

    return 500;
}

/**
 * Edits an existing poll with the provided data and updates it in the polls file.
 *
 * @param mixed $data The data to edit the poll with. Should be an associative array containing the following keys:
 *                    - 'id': The ID of the poll to edit.
 *                    - Other keys represent the fields to update in the poll.
 * @return mixed Returns the edited poll if successful, otherwise NULL.
 */
function edit_poll(mixed $data): mixed
{
    $id = $data['id'];
    unset($data['id']);

    if ($old = get_item_from_file('id', $id, POLLS)) {
        if ($old['owner'] !== $_SESSION['data']['id']) {
            return 401;
        }

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

        return update_item_from_file($id, $data, POLLS) ?? 404;
    }

    return 404;
}

/**
 * Deletes the poll with the specified ID.
 *
 * @param mixed $data The data containing the ID of the poll to delete.
 * @return mixed Returns true if the poll is successfully deleted, otherwise false.
 */
function delete_poll(mixed $data): mixed
{
    if (($poll = get_item_from_file('id', $data['id'], POLLS)) && $poll['owner'] !== $_SESSION['data']['id']) {
        return 401;
    }

    return delete_item_from_file($data['id'], POLLS) ?? 404;
}

//endregion