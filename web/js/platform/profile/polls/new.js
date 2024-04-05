import { executeAjaxCall } from '../../../utils.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "userlists-by-owner",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);
            for (const index in data) {
                const userlist = data[index];
                const option = $(`<option value=${userlist.id}></option>`);
                $(option).text(userlist.name);
                $("#userlist").append(option);
            }
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

    const crypt = new JSEncrypt({ default_key_size: 1024 });

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

    data.public_key = crypt.getPublicKey();


    executeAjaxCall({
        apiName: "create-poll",
        method: "POST",
        data,
        successCallback: function (res) {
            const data = JSON.parse(res.data);
            localStorage.setItem(`pk-${data.id}`, crypt.getPrivateKey());


            window.location.href = "/web/platform/";
        },
        errorCallback: function (err) {
            $("#response").removeClass("d-none");
            $("#response").addClass("alert-danger");

            $("#response").text(getTranslation(err.responseText?.message));
        }
    });
});