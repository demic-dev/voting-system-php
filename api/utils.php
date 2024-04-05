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
 * Opens in a safe context the file and returns it.
 * @param string $filepath The file path.
 * @return mixed|null The file array if exist.
 */
function safely_open_json(string $filepath): mixed
{
    try {
        $file = file_get_contents($filepath);
        $data = json_decode($file, true);

        return $data;
    } catch (Exception $e) {
        return NULL;
    }
}

/**
 * Opens in a safe context the file and updates it.
 * @param string $filepath The file path.
 * @param mixed $data The updated file data.
 * @return bool The update outcome.
 */
function safely_overwrite_json(string $filepath, mixed $data)
{
    try {
        $jsonEncoded = json_encode($data);
        if (file_put_contents($filepath, $jsonEncoded)) {
            return true;
        }

        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * When called, the function returns the updated time as log in the database.
 * @param bool $only_update flag to specify if it has to return only the update time.
 * @return mixed the array with the iso date of the update.
 */
function set_data_log(bool $only_update = false): mixed
{
    $now = (new DateTime())->format(DateTime::ATOM);
    if ($only_update) {
        return array(
            UPDATED_AT => $now,
        );
    }

    return array(
        CREATED_AT => $now,
        UPDATED_AT => $now,
    );
}

/**
 * When invoked it search for the user object inside the array passed.
 * @param string $key The PK of the user to find.
 * @param string $value Value of the PK of the user to find.
 * @param mixed $filecontent The array fetched from the json file.
 * @return mixed|null The object if the element is found, NULL otherwise.
 */
function find_in_file(string $key, string $value, mixed $filecontent): mixed
{
    $idx = array_search($value, array_column($filecontent, $key));

    if ($idx !== False) {
        return $filecontent[$idx];
    }

    return NULL;
}

/**
 * Search for the user in the file and it updates his info.
 * 
 * @param string $id The PK.
 * @param mixed $data The data to update.
 * @param mixed $filecontent The file array.
 * @return mixed|null The updated object.
 */
function update_in_file(string $id, mixed $data, mixed $filecontent): mixed
{
    $idx = array_search($id, array_column($filecontent, 'id'));

    if ($idx !== False) {
        $filecontent[$idx] = array(
            ...$filecontent[$idx],
            ...set_data_log(true),
            ...$data,
        );

        return $filecontent;
    }

    return NULL;
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
