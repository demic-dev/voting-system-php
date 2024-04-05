<?php

/**
 * Parse the API response based on the HTTP code.
 * @param int $code The HTTP Status Code.
 * @param string $message OPTIONAL The custom message to return.
 * @return array The associative array with code and message.
 */
function get_code_and_message(int $code, string|null $message = '')
{
    if (!$message) {
        switch ($code) {
            case 200:
                $message = 'Action successfully performed.';
                break;
            case 400:
                $message = 'Error: Bad Request.';
                break;
            case 401:
                $message = 'Error: You must be logged in to view this.';
                break;
            case 403:
                $message = 'Error: You are not able to view this.';
                break;
            case 404:
                $message = 'Error: API Not Found.';
                break;
            case 500:
                $message = 'Error: Internal Server Error';
                break;
        }
    }

    http_response_code($code);
    return array(
        'code' => $code,
        'message' => $message,
    );
}

/**
 * @param bool $is_protected Is the API available only to authenticated users?
 * @param mixed $params The params of the function.
 * @param mixed $callback The function that perform the action requested from the API.
 * @param mixed $params_safety_check The array of mandatory params with their type: [[name, func], [name, func], ...].
 * @param string $message OPTIONAL - A custom message to return
 * @return mixed The response with the format { code: number, message: string, data: mixed }
 */
function compose_api_response(bool $is_protected, mixed $params, mixed $callback, array $params_safety_check = NULL, mixed $messages = array()): mixed
{
    try {
        session_start();
        /* Start the session if the API is authenticated. */
        if ($is_protected && !isset($_SESSION['data'])) {
            return get_code_and_message(401);
        }

        /* Perform the check on the params passed to the function. If some data is missing, then return a 400 error. */
        if (isset($params_safety_check) && !check_params($params, $params_safety_check)) {
            return get_code_and_message(400, $messages['400']);
        }

        /* The actual action performed. */
        $res = $callback($params);

        if (is_array($res)) {
            return array(
                ...get_code_and_message(200, $messages['200']),
                'data' => json_encode($res),
            );
        } else if (is_numeric($res)) {
            return get_code_and_message($res, $messages[$res]);
        } else if ($res === True) {
            return get_code_and_message(200, $messages['200']);
        } else if ($res === NULL) {
            return get_code_and_message(404, $messages['404']);
        }
    } catch (Exception $e) {
        return array(
            ...get_code_and_message(500),
            'data' => json_encode($e),
        );
    }
}
