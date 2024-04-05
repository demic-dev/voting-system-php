<?php

/**
 * Check if the params are well defined as they should.
 * @param mixed $data The params.
 * @param array $keys The array of mandatory params with their type: [[name, func], [name, func], ...].
 * @return bool True if the set of params is ok. Otherwise false.
 */
function check_params(mixed $data, array $keys): bool
{
    foreach ($keys as $key) {
        if (!(isset($data[$key[0]]) && $key[1]($data[$key[0]]))) {
            return false;
        }
    }
    return true;
}

/**
 * Used for PARAM_SAFETY_FUNCTION, checks if is an email or not
 * @param string $email The email string to check.
 * @return bool If it's an email or not.
 */
function is_email(string $email): bool
{
    return !!filter_var($email, FILTER_VALIDATE_EMAIL);
}
