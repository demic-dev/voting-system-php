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
                        $(elt).append("<button type=\"button\" class=\"user__icon add-user\"><i class=\"bi bi-plus\"></i></button>");

                        if (current.users.includes(user.id)) {
                            // bi-trash-fill
                            $("#selected-users").append(elt);
                        } else {
                            $("#all-users").append(elt);
                        }

                    }
                },
                errorCallback: function (err) { }
            })
        },
        errorCallback: function (err) {
            console.log(err);
        }
    });
});

$("#selected-users").delegate("button", "click", function (e) {
    const selectedUser = $(e.currentTarget).parent();

    $("#all-users").append(selectedUser);
});

$("#all-users").delegate("button", "click", function (e) {
    const selectedUser = $(e.currentTarget).parent();

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
        },
        errorCallback: function (err) {
            console.log(err)
            $("#response").removeClass("d-none");
            $("#response").addClass("alert-danger");

            $("#response").text(getTranslation(err.responseText?.message));
        }
    });
});