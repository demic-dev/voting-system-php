<?php

require_once "config.php";
require_once "utils.php";
require_once "api_wrapper.php";

require_once "auth.php";
require_once "poll.php";
require_once "user.php";
require_once "userlist.php";
require_once "vote.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

date_default_timezone_set('Europe/Paris');

/* REMOVING ERRORS IN API RESPONSE */
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$res = NULL;
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

switch ($REQUEST_METHOD) {
    case 'GET':
        $payload = $_GET; // json_decode(file_get_contents('php://input'), true);
        unset($payload[API_NAME]);

        switch ($_GET[API_NAME]) {
            case 'ping':
                $res = "GET APIs seems to work :P";
                http_response_code(200);
                break;

                //region user

            case 'self':
                $res = compose_api_response(true, NULL, 'get_self');
                break;

            case 'user':
                $params_safety = [['email', 'is_email']];
                $res = compose_api_response(true, $payload, 'get_user', $params_safety);
                break;

            case 'users-without-self':
                $res = compose_api_response(true, $payload, 'get_users_without_self', [], array());
                break;

                //endregion

                //region userlists

            case 'userlist':
                $params_safety = [['id', 'is_string']];
                $messages = array();
                $res = compose_api_response(true, $payload, 'get_userlist', $params_safety, $messages);
                break;

            case 'userlists-by-owner':
                $res = compose_api_response(true, NULL, 'get_userlists_by_owner', [], array());
                break;

                //endregion

                //region polls

            case 'get-polls-by-user':
                $messages = array();
                $res = compose_api_response(true, NULL, 'get_polls_by_user', [], $messages);
                break;

            case 'get-polls-by-owner':
                $messages = array();
                $res = compose_api_response(true, NULL, 'get_poll_by_owner', [], $messages);
                break;

            case 'get-poll':
                $messages = array();
                $res = compose_api_response(true, $payload, 'get_poll_by_id', [], $messages);
                break;

                //endregion

            default:
                $res = get_code_and_message(404);
                break;
        }
        break;
    case 'POST':
        $payload = json_decode(file_get_contents('php://input'), true);
        $api_name = $payload[API_NAME];
        unset($payload[API_NAME]);

        switch ($api_name) {
            case 'ping':
                $res = "POST APIs seems to work :P";
                http_response_code(200);
                break;

                //region auth

            case 'sign-in':
                $params_safety = [['email', 'is_email'], ['password', 'is_string']];
                $messages = array(
                    '200' => "API_RESPONSES.auth_success",
                    '400' => "Email not valid.",
                    '401' => "Email or password incorrect.",
                );
                $res = compose_api_response(false, $payload, 'login', $params_safety, $messages);
                break;

            case 'sign-out':
                $res = compose_api_response(true, NULL, 'logout', [], array());
                break;

                //endregion

                //region users

            case 'sign-up':
                $params_safety = [['name', 'is_string'], ['surname', 'is_string'], ['email', 'is_email'], ['password', 'is_string']];
                $messages = array(
                    '200' => "API_RESPONSES.sign-up.200",
                    '400' => "API_RESPONSES.sign-up.400",
                    '404' => "API_RESPONSES.sign-up.404",
                );
                $res = compose_api_response(false, $payload, 'create_user', $params_safety, $messages);
                break;

            case 'edit-self':
                $messages = array(
                    '200' => "User updated successfully.",
                );
                $res = compose_api_response(true, $payload, 'edit_self', [], $messages);
                break;

            case 'edit-user':
                $messages = array(
                    '200' => "User updated successfully.",
                );
                $res = compose_api_response(true, $payload, 'edit_user', [], $messages);
                break;

                //endregion

                //region userlists

            case 'new-userlist':
                $messages = array(
                    '200' => "API_RESPONSES.new_userlist.200",
                    '400' => "API_RESPONSES.new_userlist.400",
                );
                $res = compose_api_response(true, $payload, 'create_userlist', [], $messages);
                break;

            case 'delete-userlist':
                $params_safety = [['id', 'is_string']];
                $messages = array(
                    '200' => 'API_RESPONSES.delete_userlist.200',
                    '400' => "API_RESPONSES.delete_userlist.400",
                    '401' => "API_RESPONSES.delete_userlist.401",
                    '404' => "API_RESPONSES.delete_userlist.404",
                );
                $res = compose_api_response(true, $payload, 'delete_userlist', $params_safety, $messages);
                break;

            case 'edit-userlist':
                $params_safety = [['id', 'is_string']];
                $messages = array(
                    '200' => 'API_RESPONSES.edit_userlist.200',
                    '400' => "API_RESPONSES.edit_userlist.400",
                    '401' => "API_RESPONSES.edit_userlist.401",
                    '404' => "API_RESPONSES.edit_userlist.404",
                );
                $res = compose_api_response(true, $payload, 'edit_userlist', $params_safety, $messages);
                break;

                //endregion

                //region polls

            case 'create-poll':
                $params_safety = [['name', 'is_string'], ['description', 'is_string'], ['start_date', 'is_string'], ['due_date', 'is_string']];
                $messages = array(
                    '200' => "API_RESPONSES.create_poll.200",
                    '400' => "API_RESPONSES.create_poll.400",
                    '401' => "API_RESPONSES.create_poll.401",
                    '404' => "API_RESPONSES.create_poll.404",
                );
                $res = compose_api_response(true, $payload, 'create_poll', $params_safety, $messages);
                break;

            case 'update-poll':
                $params_safety = [['name', 'is_string'], ['description', 'is_string'], ['start_date', 'is_string'], ['due_date', 'is_string']];
                $messages = array(
                    '200' => "API_RESPONSES.update_poll.200",
                    '400' => "API_RESPONSES.update_poll.400",
                    '401' => "API_RESPONSES.update_poll.401",
                    '404' => "API_RESPONSES.update_poll.404",
                );
                $res = compose_api_response(true, $payload, 'edit_poll', [], $messages);
                break;

            case 'delete-poll':
                $params_safety = [['id', 'is_string']];
                $messages = array(
                    '400' => "API_RESPONSES.update_poll.400",
                    '401' => "API_RESPONSES.update_poll.401",
                    '404' => "API_RESPONSES.update_poll.404",
                );
                $res = compose_api_response(true, $payload, 'delete_poll', [], $messages);
                break;

            case 'close-poll':
                $params_safety = [['id', 'is_string'], ['privateKey', 'is_string']];
                $messages = array(
                    '400' => "API_RESPONSES.update_poll.400",
                    '401' => "API_RESPONSES.update_poll.401",
                    '404' => "API_RESPONSES.update_poll.404",
                );
                $res = compose_api_response(true, $payload, 'close_voting', [], $messages);
                break;

            case 'add-vote':
                $params_safety = [];
                $messages = array();
                $res = compose_api_response(true, $payload, 'add_vote', [], $messages);
                break;

                //endregion

            default:
                $res = get_code_and_message(404);
                break;
        }
        break;
    default:
        die();
        break;
}

header('Content-type: application/json');
echo json_encode($res);
return;
