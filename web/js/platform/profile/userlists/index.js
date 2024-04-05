import { executeAjaxCall } from '../../../utils.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "userlists-by-self",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);

            for (const index in data) {
                const userlist = data[index];

                const editLink = $(`
                    <a href="./edit.php?id=${userlist.id}" class="action">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                `);

                const deleteButton = $(`
                    <button type="button" class="action" id="${userlist.id}">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                `);

                const ctasContainer = $(`<div class="ctas__container"></div>`);

                $(ctasContainer).append(editLink);
                $(ctasContainer).append(deleteButton);

                const userlistContainer = $(`
                    <div class="content__container"></div>
                `);

                $(userlistContainer).text(userlist.name);
                $(userlistContainer).append(ctasContainer);

                $("#userlists__container").append(userlistContainer);
            }
        },
        errorCallback: function (err) {
            // Silent error, shown only in console.
            console.log(err);
        }
    });
})

$("#userlists__container").delegate("button", "click", function (e) {
    const id = e.currentTarget.id;

    executeAjaxCall({
        apiName: "delete-userlist",
        method: "POST",
        data: { id },
        successCallback: function (res) {
            window.location.reload();
        },
        errorCallback: function (err) {
            console.log(err);
        }
    });
});