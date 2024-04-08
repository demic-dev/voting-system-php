<?php

require_once "config.php";
require_once "utils.php";

require_once "./controller/index.php";

require_once "./controller/get.php";
require_once "./controller/update.php";
require_once "./controller/delete.php";


require_once "auth.php";
require_once "poll.php";
require_once "user.php";
require_once "userlist.php";
require_once "vote.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

date_default_timezone_set('Europe/Paris');

/* REMOVING ERRORS IN API RESPONSE */
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_NOTICE);

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
                $messages = array(
                    '404' => "API_RESPONSES.self.404",
                );
                $res = compose_api_response(true, NULL, 'get_self', [], $_GET[API_NAME]);
                break;

            case 'user':
                $params_safety = [['email', 'is_email']];
                $res = compose_api_response(true, $payload, 'get_user', $params_safety, $_GET[API_NAME]);
                break;

            case 'users-without-self':
                $res = compose_api_response(true, $payload, 'get_users_without_self', [], $_GET[API_NAME]);
                break;

                //endregion

                //region userlists

            case 'userlist':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'get_userlist', $params_safety, $_GET[API_NAME]);
                break;

            case 'userlists-by-self':
                $res = compose_api_response(true, NULL, 'userlists_by_self', [], $_GET[API_NAME]);
                break;

                //endregion

                //region polls

            case 'poll':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'get_poll', $params_safety, $_GET[API_NAME]);
                break;

            case 'polls-by-self':

                $res = compose_api_response(true, NULL, 'get_polls_by_self', [], $_GET[API_NAME]);
                break;
            case 'polls-per-user':

                $res = compose_api_response(true, NULL, 'get_polls_per_user', [], $_GET[API_NAME]);
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

                $res = compose_api_response(false, $payload, 'login', $params_safety, $api_name);
                break;

            case 'sign-out':
                $res = compose_api_response(true, NULL, 'logout', [], $api_name);
                break;

                //endregion

                //region users

            case 'sign-up':
                $params_safety = [['name', 'is_string'], ['surname', 'is_string'], ['email', 'is_email'], ['password', 'is_string']];
                $res = compose_api_response(false, $payload, 'create_user', $params_safety, $api_name);
                break;

            case 'edit-self':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'edit_self', [], $api_name);
                break;

                //endregion

                //region userlists

            case 'create-userlist':
                $res = compose_api_response(true, $payload, 'create_userlist', [], $api_name);
                break;

            case 'edit-userlist':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'edit_userlist', $params_safety, $api_name);
                break;

            case 'delete-userlist':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'delete_userlist', $params_safety, $api_name);
                break;

                //endregion

                //region polls

            case 'create-poll':
                $params_safety = [['name', 'is_string'], ['description', 'is_string'], ['start_date', 'is_string'], ['due_date', 'is_string']];
                $res = compose_api_response(true, $payload, 'create_poll', $params_safety, $api_name);
                break;

            case 'update-poll':
                $params_safety = [['name', 'is_string'], ['description', 'is_string'], ['start_date', 'is_string'], ['due_date', 'is_string']];
                $res = compose_api_response(true, $payload, 'edit_poll', [], $api_name);
                break;

            case 'delete-poll':
                $params_safety = [['id', 'is_string']];
                $res = compose_api_response(true, $payload, 'delete_poll', [], $api_name);
                break;

                //endregion

                //region voting

            case 'close-poll':
                $params_safety = [['id', 'is_string'], ['privateKey', 'is_string']];
                $res = compose_api_response(true, $payload, 'close_voting', [], $api_name);
                break;

            case 'add-vote':
                $res = compose_api_response(true, $payload, 'add_vote', [], $api_name);
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
