<?php

/**
 * Adds a vote to the specified poll for the currently authenticated user.
 *
 * @param mixed $data The data containing the ID of the poll and the selected option.
 * @return mixed Returns the updated poll with the added vote if successful, otherwise NULL.
 */
function add_vote(mixed $data): mixed
{
    $id = $data['poll'];
    $proxy = $data['proxy'] ?? NULL;
    $user = $_SESSION['data']['id'];
    $option = $data['option'];

    if ($poll = get_item_from_file('id', $id, POLLS)) {
        $userlist = get_item_from_file('id', $poll['userlist'], USERLISTS);
        if (
            in_array($user, $poll['voted_by']) ||
            !in_array($user, $userlist['users']) ||
            strtotime(date($poll['due_date'])) < time()
        ) {
            return NULL;
        }

        array_push($poll['votes'], $option);
        if ($proxy !== NULL) {
            array_push($poll['voted_by'], $proxy);
            array_push($poll['proxies'], $user);
        } else {
            array_push($poll['voted_by'], $user);
            array_push($poll['proxies'], $proxy);
        }

        return update_item_from_file($id, $poll, POLLS);
    };

    return NULL;
}

/**
 * Closes the voting for the specified poll by decrypting the votes and updating the vote counts for each option.
 *
 * @param mixed $data The data containing the ID of the poll and the private key for decryption.
 * @return mixed Returns the updated poll with the vote counts incremented if successful, otherwise NULL.
 */
function close_voting(mixed $data): mixed
{
    if ($poll = get_item_from_file('id', $data['id'], POLLS)) {
        if ($poll['closed']) {
            return NULL;
        }

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

        if (strtotime(date($poll['due_date'])) >= time()) {
            $poll['due_date'] = (new DateTime())->format('c');
        }

        $poll['closed'] = true;

        return update_item_from_file($data['id'], $poll, POLLS);
    };

    return NULL;
}
