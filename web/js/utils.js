import { getCurrentLanguage } from "./translations/index.js";

export function isEmailValid(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
}

export function executeAjaxCall(options = {
    apiName: 'ping',
    method: 'GET',
    data: {},
    successCallback: function (res) { },
    errorCallback: function (err) { },
}) {
    const url = "http://localhost:9000/api/index.php";

    if (options.method === "GET") {
        $.ajax({
            url: url + `?API_NAME=${options.apiName}`,
            type: options.method,
            method: options.method,
            contentType: "application/json",
            dataType: "json",
            data: $.param(options.data),
            processData: false,
        })
            .done(options.successCallback)
            .fail(options.errorCallback);
    } else if (options.method === "POST") {

        $.ajax({
            url,
            type: options.method,
            method: options.method,
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({
                ...options.data,
                "API_NAME": options.apiName,
            }),
            processData: false,
        })
            .done(options.successCallback)
            .fail(options.errorCallback);
    }
}

/**
 * Get relative time based on the Intl library.
 * @source https://stackoverflow.com/a/53800501
 * @param {*} d1 
 * @param {*} d2 
 * @returns 
 */
export function getRelativeTime(d1, d2 = new Date()) {
    const rtf = new Intl.RelativeTimeFormat(getCurrentLanguage(), { numeric: 'auto' })
    const units = {
        year: 24 * 60 * 60 * 1000 * 365,
        month: 24 * 60 * 60 * 1000 * 365 / 12,
        day: 24 * 60 * 60 * 1000,
        hour: 60 * 60 * 1000,
        minute: 60 * 1000,
        second: 1000
    }
    const elapsed = d1 - d2

    // "Math.abs" accounts for both "past" & "future" scenarios
    for (var u in units)
        if (Math.abs(elapsed) > units[u] || u == 'second')
            return rtf.format(Math.round(elapsed / units[u]), u)
}