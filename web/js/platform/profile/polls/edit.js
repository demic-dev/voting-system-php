import { executeAjaxCall } from '../../../utils.js';
import { getTranslation } from '../../../translations/index.js';

const url = new URL(window.location.href);
const id = url.searchParams.get("id");

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "userlists-by-self",
        method: "GET",
        data: undefined,
        successCallback: function (resUserlists) {
            executeAjaxCall({
                apiName: "poll",
                method: "GET",
                data: { id },
                successCallback: function (resPoll) {
                    const poll = JSON.parse(resPoll.data);

                    document.title = getTranslation("LABELS.edit_poll.title", poll.name);

                    $("#name").val(poll.name);
                    $("#description").val(poll.description);
                    $("#start_date").val(poll.start_date);
                    $("#due_date").val(poll.due_date);

                    const userlists = JSON.parse(resUserlists.data);

                    // userlist select
                    for (const index in userlists) {
                        const userlist = userlists[index];
                        const option = $(`<option value=${userlist.id}></option>`);

                        if (userlist.id === poll.userlist) {
                            option.attr("selected", true);
                        }

                        $(option).text(userlist.name);
                        $("#userlist").append(option);
                    }

                    // poll answers
                    let lastFieldIndex = 0;
                    for (const index in poll.options) {
                        const option = poll.options[index];

                        const inputField = $("#options__container").children()[index];

                        if (inputField) {
                            $(inputField).val(option.text);
                        } else {
                            lastFieldIndex = index;
                            const newInput = $(`<input class="form-control" type="text" id="option_${~~index + 1}" name="option_${~~index + 1}">`);
                            newInput.val(option.text);
                            $("#options__container").append(newInput);
                        }
                    }

                    if (lastFieldIndex !== 0) {
                        $("#options__container").append($(`<input class="form-control" type="text" id="option_${~~lastFieldIndex + 1}" name="option_${~~lastFieldIndex + 1}">`));
                    }



                },
                errorCallback: function (err) {
                    console.log(err.responseText);
                }
            });
        },
        errorCallback: function (err) {
            // Silent error, shown only in console.
            console.log(err);
        }
    });
});


/* Dynamically add the input field */
$(".options__container").delegate("[id^=option_]", "blur", function (e) {
    const lastTwo = [$("#options__container").children().last()[0], $("#options__container").children().last().prev()[0]];
    if (e.target.value === "" && $("#options__container").children().length > 1 && lastTwo.includes(e.target)) {
        $("#options__container").children().last().remove();
    } else if (e.target.value !== "" && e.target === $("#options__container").children().last()[0]) {
        const index = $("#options__container").children().last().attr("id").split("_")[1];
        $("#options__container").append($(`<input class="form-control" type="text" id="option_${~~index + 1}" name="option_${~~index + 1}">`));
    }
});

// Handling the tab next...
// $(".options__container").delegate("[id^=option_]", "keyup", function (e) {
//     if (e.which == 9) {
//         console.log(e.target);
//     }
// });

$("form :input").on("input", function (e) {
    switch (e.target.id) {
        case 'name':
        case 'description':
            if (e.target.value === "") {
                $(e.target).removeClass("is-invalid");
                $(e.target).removeClass("is-valid");
            } else {
                $(e.target).addClass("is-valid");
            }
            break;
        case 'start_date':
            if (e.target.value === "") {
                $(e.target).removeClass("is-valid");
                $(e.target).removeClass("is-invalid");
            } else if (new Date(e.target.value) > new Date()) {
                $(e.target).removeClass("is-invalid");
                $(e.target).addClass("is-valid");
            } else {
                $(e.target).removeClass("is-valid");
                $(e.target).addClass("is-invalid");
            }
            break;
        case 'due_date':
            if (e.target.value === "") {
                $(e.target).removeClass("is-valid");
                $(e.target).removeClass("is-invalid");
            } else if (new Date(e.target.value) > new Date($("#start_date").val())) {
                $(e.target).removeClass("is-invalid");
                $(e.target).addClass("is-valid");
            } else {
                $(e.target).removeClass("is-valid");
                $(e.target).addClass("is-invalid");
            }
            break;
    }
});

$("form").on('submit', function (e) {
    e.preventDefault();

    const data = $("form").serializeArray().reduce((prev, cur) => {
        if (cur.name.startsWith("option_")) {
            if (cur.value !== "") {
                const options = [...prev?.options || [], cur.value];
                return ({ ...prev, 'options': options });
            } else {
                return prev;
            }
        }

        return ({ ...prev, [cur.name]: cur.value });
    }, {});

    executeAjaxCall({
        apiName: "update-poll",
        method: "POST",
        data: { ...data, id },
        successCallback: function (res) {
            window.location.href = "/web/platform/profile/polls/";
        },
        errorCallback: function (err) {
            $("#response").removeClass("d-none");
            $("#response").addClass("alert-danger");

            $("#response").text(getTranslation(err.responseText?.message));
        }
    });
});