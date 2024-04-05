import { executeAjaxCall } from '../../../utils.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "get-polls-by-owner",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);

            for (const index in data) {
                const poll = data[index];

                const editLink = $(`
                        <a href="./edit.php?id=${poll.id}" class="action">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    `);

                const deleteButton = $(`
                        <button type="button" class="action" id="${poll.id}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    `);

                const ctasContainer = $(`<div class="ctas__container"></div>`);

                $(ctasContainer).append(editLink);
                $(ctasContainer).append(deleteButton);

                const container = $(`
                        <div class="content__container"></div>
                    `);

                $(container).text(poll.name);
                $(container).append(ctasContainer);

                $("#polls__container").append(container);
            }
        },
        errorCallback: function (err) {
            // Silent error, shown only in console.
            console.log(err);
        }
    });
})

$("#polls__container").delegate("button", "click", function (e) {
    const id = e.currentTarget.id;

    executeAjaxCall({
        apiName: "delete-poll",
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