import { executeAjaxCall, renderUsersInHTML } from '../../../utils.js';

const allUsersID = "#all-users";
const selectedUsersID = "#selected-users";

// map idUser => user object
const users = {};
// map idUser => proxy array
const selectedUsers = {};

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "users-without-self",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            Object
                .values(JSON.parse(res.data))
                .forEach(user => {
                    users[user.id] = user;
                });

            renderUsersInHTML(users, selectedUsers, allUsersID, false);
        }
    });
});

// select proxies
$(selectedUsersID).delegate("select", "change", function (e) {
    const id = $(e.currentTarget).parent().attr("id");
    const index = $(e.currentTarget).data("index");
    const selectedProxy = e.target.value;

    if (selectedProxy == -1) {
        selectedUsers[id][index] = "";
    } else {
        selectedUsers[selectedProxy] = new Array(2);
        selectedUsers[id][index] = selectedProxy;
    }

    renderUsersInHTML(users, selectedUsers, selectedUsersID, true);
});

/* Everytime I remove a user from the list, I rerender everything because in this way my list on the right is updated and in order */
$(selectedUsersID).delegate("button", "click", function (e) {
    const container = $($(e.currentTarget).parent()).parent();
    const id = container.attr("id");

    delete selectedUsers[id];
    Object.keys(selectedUsers).forEach(userID => {
        selectedUsers[userID] = selectedUsers[userID].filter(p => !p.includes(id));
    });

    renderUsersInHTML(users, selectedUsers, allUsersID);
    renderUsersInHTML(users, selectedUsers, selectedUsersID, true);
});

/* Every time I select a new user to add to the list, I rerender everything because in this way my previous selects on the left's list are updated with the new user added. */
$(allUsersID).delegate("button", "click", function (e) {
    const container = $($(e.currentTarget).parent()).parent();
    const id = container.attr("id");

    selectedUsers[id] = new Array(2);

    renderUsersInHTML(users, selectedUsers, allUsersID);
    renderUsersInHTML(users, selectedUsers, selectedUsersID, true);
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
    const usersToAdd = $(selectedUsersID).children();
    for (let i = 0; i < usersToAdd.length; i++) {
        users.push($(usersToAdd[i]).attr("id"));
    }

    data.users = users;
    data.proxies = selectedUsers;

    executeAjaxCall({
        apiName: "create-userlist",
        method: "POST",
        data,
        successCallback: function (res) {
            window.location.href = "/web/platform/profile/userlists/";
        },
    });
});