import { executeAjaxCall } from '../../utils.js';
import { getTranslation } from '../../translations/index.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "self",
        method: "GET",
        data: undefined,
        successCallback: function (res) {
            const data = JSON.parse(res.data);
            
            $("#name").val(data.name);
            $("#surname").val(data.surname);
            $("#email").val(data.email);
        },
        errorCallback: function (err) {
            console.log(err);
        }
    });
});

$("form :input").on("input", function (e) {
    switch (e.target.id) {
        case 'name':
        case 'surname':
            if (e.target.value === "") {
                $(e.target).addClass("is-invalid");
            } else {
                $(e.target).removeClass("is-invalid");
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

$("form").on("submit", function (e) {
    e.preventDefault();

    const data = $("form").serializeArray().reduce((prev, cur) => ({ ...prev, [cur.name]: cur.value }), {});

    executeAjaxCall({
        apiName: "edit-self",
        method: "POST",
        data,
        successCallback: function (res) {
            window.location.href = "../";
        },
        errorCallback: function (err) {
            console.log(err)
        }
    });
});