import { executeAjaxCall } from '../../../utils.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "polls-by-self",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);

            if (data.length > 0) {
                $("#polls__container").empty();
            }
            for (const index in data) {
                const poll = data[index];

                const editLink = $(`
                        <a href="./edit.php?id=${poll.id}" class="action">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    `);

                const deleteButton = $(`
                        <button type="button" class="action danger" data-action="delete" id="${poll.id}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    `);

                const closeButton = $(`
                    <button type="button" class="action success" data-action="close" id="${poll.id}">
                        <i class="bi bi-magic"></i>
                    </button>
                `);

                if (poll.closed) {
                    editLink.addClass('d-none');
                    closeButton.addClass('d-none');
                }

                const ctasContainer = $(`<div class="ctas__container"></div>`);

                $(ctasContainer).append(editLink);
                $(ctasContainer).append(deleteButton);
                $(ctasContainer).append(closeButton);

                const container = $(`
                        <div class="content__container" id=${poll.id}></div>
                    `);

                $(container).text(poll.name);
                $(container).append(ctasContainer);

                $("#polls__container").append(container);
            }
        }
    });
})

$("#polls__container").delegate("button", "click", function (e) {
    const id = e.currentTarget.id;
    const action = $(e.currentTarget).data("action");

    switch (action) {
        case 'delete':
            executeAjaxCall({
                apiName: "delete-poll",
                method: "POST",
                data: { id },
                successCallback: function (res) {
                    window.location.reload();
                }
            });
            break;
        case 'close':
            const privateKey = localStorage.getItem(`pk-${id}`);

            executeAjaxCall({
                apiName: "close-poll",
                method: "POST",
                data: { id, privateKey },
                successCallback: function (res) {
                    window.location.href = `/web/platform/poll.php?id=${id}`;
                }
            });
            break;
    }
});