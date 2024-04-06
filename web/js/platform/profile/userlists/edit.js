import { executeAjaxCall } from '../../../utils.js';
import { getTranslation } from '../../../translations/index.js';

const url = new URL(window.location.href);
const id = url.searchParams.get("id");

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "users-without-self",
        method: "GET",
        data: undefined,
        successCallback: function (resUsers) {
            const usersData = JSON.parse(resUsers.data);
            executeAjaxCall({
                apiName: "userlist",
                method: "GET",
                data: { id },
                successCallback: function (resCurrent) {
                    const current = JSON.parse(resCurrent.data);

                    $("#name").val(current.name);

                    // Implemented this workaround insead of $("#all-users").html(resulttostring); to prevent XSS
                    for (const index in usersData) {
                        const user = usersData[index];

                        const elt = $(`<div class="user_item__container" id="${user.id}"></div>`).text(`${user.name} ${user.surname}`);
                        const icon = $(`<i class=\"bi bi-plus\"></i>`);
                        const button = $(`<button type="button" class="user__icon add-user"></button>`);
                        button.append(icon);
                        $(elt).append(button);

                        if (!!current.users.find(u => u.id === user.id)) {
                            icon.toggleClass('bi-plus bi-trash-fill');
                            $("#selected-users").append(elt);
                        } else {
                            $("#all-users").append(elt);
                        }

                    }
                }
            })
        }
    });
});

// remove from selected
$("#selected-users").delegate("button", "click", function (e) {
    const selectedUser = $(e.currentTarget).parent();

    $(e.currentTarget).children('i').toggleClass('bi-trash-fill bi-plus');
    $("#all-users").append(selectedUser);
});

// add to selected
$("#all-users").delegate("button", "click", function (e) {
    const selectedUser = $(e.currentTarget).parent();

    $(e.currentTarget).children('i').toggleClass('bi-plus bi-trash-fill');
    $("#selected-users").append(selectedUser);
});

$("form :input").on("input", function (e) {
    switch (e.target.id) {
        case 'name':
            if (e.target.value === "") {
                $(e.target).removeClass("is-invalid");
                $(e.target).removeClass("is-valid");
            } else {
                $(e.target).addClass("is-valid");
            }
            break;
    }
});

$("form").on('submit', function (e) {
    e.preventDefault();

    const data = $("form").serializeArray().reduce((prev, cur) => ({ ...prev, [cur.name]: cur.value }), {});

    const users = [];
    const selectedUsers = $("#selected-users").children();
    for (let i = 0; i < selectedUsers.length; i++) {
        users.push($(selectedUsers[i]).attr("id"));
    }

    data.users = users;
    data.proxies = [];
    data.id = id;

    executeAjaxCall({
        apiName: "edit-userlist",
        method: "POST",
        data,
        successCallback: function (res) {
            window.location.href = "/web/platform/profile/userlists/";
        }
    });
});