import { getCurrentLanguage, getTranslation } from "./translations/index.js";

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

    $.ajax({
        url: url + ((options.method === 'GET') ? `?API_NAME=${options.apiName}` : ''),
        type: options.method,
        method: options.method,
        contentType: "application/json",
        dataType: "json",
        data: options.method === 'GET' ? $.param(options.data) : JSON.stringify({
            ...options.data,
            "API_NAME": options.apiName,
        }),
        processData: false,
    }).done(function (res) {
        if (options.successCallback) {
            options.successCallback(res);
        }

        const message = getTranslation(res.message);
        if (message) {
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance($("#toast"));
            $("#toast").addClass('text-bg-success');
            $("#toast-body").text(message);
            toastBootstrap.show();
        }
    })
        .fail(function (err) {
            if (options.errorCallback) {
                options.errorCallback(err);
            }

            const message = getTranslation(JSON.parse(err.responseText).message);
            console.log(message)
            if (message) {
                const toastBootstrap = bootstrap.Toast.getOrCreateInstance($("#toast"));
                $("#toast-body").text(message);
                $("#toast").addClass('text-bg-danger');
                toastBootstrap.show();
            }
        });
}

/**
 * Get relative time based on the Intl library.
 * @source https://stackoverflow.com/a/53800501
 * @param {Date} d1 
 * @param {Date} d2 
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


/**
 * Renders a user item with optional proxy selection.
 * 
 * @param {Object} user - The user object.
 * @param {boolean} shouldRenderProxies - Indicates whether proxies should be rendered.
 * @param {Array<number>} proxies - An array containing the IDs of proxies.
 * @returns {HTMLElement} - The jQuery wrapper element containing the user item.
 */
function renderUser(allUsers, selectedUsers, user, shouldRenderProxies, proxies) {
    /**
     * Renders a proxy select element.
     * 
     * @param {number} n - The index.
     * @param {Array<Object>} users - An array of user objects.
     * @param {number} proxy - The ID of the selected proxy.
     * @returns {HTMLElement} - The jQuery select element.
     */
    function renderProxySelect(n, users, proxy) {
        const select = $(`<select class="form-select" data-index="${n - 1}"></select>`);
        const defaultOption = $(`<option value="-1"></option>`);
        defaultOption.text(getTranslation("LABELS.new_userlist.proxy_select", n));
        defaultOption.attr("selected", true);
        select.append(defaultOption);

        for (const index in users) {
            const user = users[index];

            const option = $(`<option value="${user.id}"></option>`);
            option.text(`${user.name} ${user.surname}`);
            select.append(option);

            if (proxy === user.id) {
                option.prop("selected", true);
            }
        }

        return select;
    };

    const wrapper = $(`<div class="user_item__wrapper" id="${user.id}"></div>`);
    const elt = $(`<div class="user_item__container"></div>`).text(`${user.name} ${user.surname}`);
    wrapper.append(elt);

    elt.append(`<button type=\"button\" class=\"user__icon\"><i class=\"bi ${user.id in selectedUsers ? "bi-trash-fill" : "bi-plus"}\"></i></button>`);

    if (shouldRenderProxies) {
        const proxyContainer = $(`<div class="proxy__container" id=${user.id}></div>`);

        const usersArray = Object
            .keys(selectedUsers)
            // I filter all the users that: are not the current one AND (are not already a proxy of other users BUT are proxy of the current one)
            .filter(u =>
                u != user.id &&
                (
                    !(Object.values(selectedUsers)).find(proxies => proxies.includes(u)) ||
                    selectedUsers[user.id].includes(u)
                )
            )
            .map(u => allUsers[u]);

        proxyContainer.append(renderProxySelect(1, usersArray.filter(u => u.id !== proxies[1]), proxies[0]));
        proxyContainer.append(renderProxySelect(2, usersArray.filter(u => u.id !== proxies[0]), proxies[1]));

        wrapper.append(proxyContainer);
    }

    return wrapper;
};

/**
 * Renders users in HTML based on the provided data.
 * 
 * @param {Object} allUsers - An object containing all user data.
 * @param {Object} selectedUsers - An object containing selected user data.
 * @param {string} id - The ID of the HTML element where the users will be rendered.
 * @param {boolean} shouldRenderProxies - Indicates whether proxies should be rendered.
 */
export function renderUsersInHTML(allUsers, selectedUsers, id, shouldRenderProxies) {
    $(id).empty();
    if (shouldRenderProxies) {
        Object
            .keys(selectedUsers)
            .forEach(userID => {
                $(id).append(
                    renderUser(
                        allUsers,
                        selectedUsers,
                        allUsers[userID],
                        !Object.values(selectedUsers).some(p => p.includes(userID)),
                        selectedUsers[userID]
                    )
                );
            });
    } else {
        Object
            .keys(allUsers)
            .filter(user => !selectedUsers.hasOwnProperty(user))
            .forEach(user => {
                $(id).append(
                    renderUser(
                        allUsers,
                        selectedUsers,
                        allUsers[user]
                    ),
                    false
                );
            });
    }
};