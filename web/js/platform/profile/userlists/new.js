import { executeAjaxCall } from '../../../utils.js';
import { getTranslation } from '../../../translations/index.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "users-without-self",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);

            for (const index in data) {
                // Implemented this workaround insead of $("#all-users").html(resulttostring); to prevent XSS
                const user = data[index];
                const elt = $(`<div class="user_item__container" id="${user.id}"></div>`).text(`${user.name} ${user.surname}`);
                $(elt).append("<button type=\"button\" class=\"user__icon add-user\"><i class=\"bi bi-plus\"></i></button>");
                $("#all-users").append(elt);
            }
        },
        errorCallback: function (err) {
            console.log(err);
        }
    });
});

$("#all-users").delegate("button", "click", function (e) {
    const selectedUser = $(e.currentTarget).parent();

    $("#selected-users").append(selectedUser);
})

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

    executeAjaxCall({
        apiName: "new-userlist",
        method: "POST",
        data,
        successCallback: function (res) {
            window.location.href = "/web/platform/profile/userlists/";
        },
        errorCallback: function (err) {
            $("#response").removeClass("d-none");
            $("#response").addClass("alert-danger");

            $("#response").text(getTranslation(res.responseText?.message));
        }
    });
});