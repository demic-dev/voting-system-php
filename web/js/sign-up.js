import { executeAjaxCall, isEmailValid } from './utils.js';
import { getTranslation } from './translations/index.js';

$("form :input").on("input", function (e) {
    switch (e.target.id) {
        case 'name':
        case 'surname':
            if (e.target.value === "") {
                $(e.target).removeClass("is-valid");
            } else {
                $(e.target).addClass("is-valid");
            }
            break;
        case 'email':
            if (e.target.value === "") {
                $(e.target).removeClass("is-invalid");
                $(e.target).removeClass("is-valid");
            } else if (!isEmailValid(e.target.value)) {
                $(e.target).removeClass("is-valid");
                $(e.target).addClass("is-invalid");
            } else {
                $(e.target).removeClass("is-invalid");
                $(e.target).addClass("is-valid");
            }
            break;
        case 'password':
        case 'confirm-password':
            if (e.target.value === "") {
                $(e.target).removeClass("is-invalid");
                $(e.target).removeClass("is-valid");
            } else if (e.target.value.length < 6) {
                $(e.target).removeClass("is-valid");
                $(e.target).addClass("is-invalid");
            } else {
                $(e.target).removeClass("is-invalid");
                $(e.target).addClass("is-valid");
            }
            break;
        case 'confirm-password':
            if ($("#password").val() != $(e.target).val()) {
                $(e.target).removeClass("is-valid");
                $(e.target).addClass("is-invalid");
            } else {
                $(e.target).removeClass("is-invalid");
                $(e.target).addClass("is-valid");
            }
            break;
    }

});


$("form").on('submit', function (e) {
    e.preventDefault();

    const data = $("form").serializeArray().reduce((prev, cur) => ({ ...prev, [cur.name]: cur.value }), {});

    executeAjaxCall({
        apiName: "sign-up",
        method: "POST",
        data,
        successCallback: function (res) {
            $("#spinner").addClass("d-none");
            $("#response-container").removeClass("d-none");
            $("#response-container").addClass("alert-success");
            $("#response-container").removeClass("alert-danger");
            $("#response-container").text(getTranslation(res?.message));
            setTimeout(() => {
                window.location.href = "sign-in.html";
            }, 1500);
        },
        errorCallback: function (err) {
            $("#spinner").addClass("d-none");
            $("#submit").prop("disabled", false);
            $("#response-container").removeClass("d-none");
            $("#response-container").removeClass("alert-success");
            $("#response-container").addClass("alert-danger");
            $("#response-container").text(getTranslation(err?.responseJSON?.message));
        }
    });

    $("#spinner").removeClass("d-none");
    $("#submit").prop("disabled", true);

    return false;
});