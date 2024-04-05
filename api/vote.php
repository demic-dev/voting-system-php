<?php

function add_vote(mixed $data): mixed
{
    $poll_id = $data['poll'];
    $user_id = $_SESSION['data']['id'];
    // $proxy_user_id = $data['proxy_user_id'];
    $option = $data['option'];

    $polls_file = safely_open_json(POLLS);

    if (($poll_object = find_in_file('id', $poll_id, $polls_file)) && !in_array($user_id, $poll_object['voted_by'])) {
        array_push($poll_object['votes'], $option);
        if (!in_array($user_id, $poll_object['voted_by'])) {
            array_push($poll_object['voted_by'], $user_id);
        }
        // Add proxy handling

        return ($file = update_in_file($poll_id, $poll_object, $polls_file)) ?
            safely_overwrite_json(POLLS, $file) : NULL;
    }

    return NULL;
}

function close_voting(mixed $data): mixed
{
    $file = safely_open_json(POLLS);

    if ($poll = get_poll_by_id($data, $file)) {
        $private_key = $data['privateKey'];
        $decrypted_answer = "";

        foreach ($poll['votes'] as $_ => $value) {
            $encrypted_answer = base64_decode($value);

            if (openssl_private_decrypt($encrypted_answer, $decrypted_answer, $private_key)) {
                $index = array_search($decrypted_answer, array_column($poll['options'], 'id'));
                $poll['options'][$index]['count']++;
            } else {
                return NULL;
            }
        }

        return ($res = update_in_file($data['id'], $poll, $file)) ? safely_overwrite_json(POLLS, $res) : NULL;
    }

    return NULL;
}
