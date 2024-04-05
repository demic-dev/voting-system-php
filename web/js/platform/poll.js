import { executeAjaxCall, getRelativeTime } from '../utils.js';
import { getTranslation } from '../translations/index.js';


const url = new URL(window.location.href);
const id = url.searchParams.get("id");

let hasVoted = false;
let isClosed = false;

/**
 * The main button has to be disabled if:
 * - Poll is not started
 * - Poll is ended
 * - User has already voted
 */
function handleSubmitButton() {
    if (hasVoted || isClosed) {
        $("#submit_cta").addClass("d-none");
        $("#voted-response").removeClass("d-none");
        
        if (hasVoted) {
            $("#voted-response").addClass("alert-primary");
            $("#voted-response").text(getTranslation("LABELS.vote.submitted"));
        }
    } else {
        $("#submit_cta").removeClass("d-none");
        $("#voted-response").addClass("d-none");

    }
}

function startCountdown(startDate, endDate) {
    setInterval(function countdown() {
        const now = new Date();
        const container = $(".badge__container");

        if (now >= endDate) {
            isClosed = true;

            container.addClass("is-ended");

            const icon = $(container).children(".bi-clock-fill");
            icon.removeClass("bi-clock-fill");
            icon.addClass("bi-exclamation-circle-fill");

            $("#countdown").text(getTranslation("LABELS.vote.closed_ago", getRelativeTime(endDate)));

            clearInterval(this);
        } else {
            const dateToCount = now < startDate ? startDate : endDate;
            let delta = Math.abs(dateToCount - now) / 1000;

            const days = Math.floor(delta / 86400);
            delta -= days * 86400;
            const hours = Math.floor(delta / 3600) % 24;
            delta -= hours * 3600;
            const minutes = Math.floor(delta / 60) % 60;
            delta -= minutes * 60;
            const seconds = Math.floor(delta % 60);

            const countdownString = getTranslation(
                "LABELS.vote.active_until",
                days,
                String(hours).padStart(2, "0"),
                String(minutes).padStart(2, "0"),
                String(seconds).padStart(2, "0")
            );

            if (now < startDate) {
                container.addClass("isnt-started");
                $("#countdown").text(getTranslation("LABELS.vote.is_not_started", countdownString));
            } else {
                if (isClosed) {
                    isClosed = false;
                    handleSubmitButton();
                }

                container.removeClass("isnt-started");
                $("#countdown").text(countdownString);
            }
        }

        return countdown;
    }(), 1000);
};

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: 'poll',
        method: 'GET',
        data: { id },
        successCallback: function (res) {
            const data = JSON.parse(res.data);

            // rendering the infos
            document.title = data.name;

            $("#heading").text(data.name);
            $("#description").text(data.description);
            $("#public_key").text(data.public_key);

            // rendering the countdown
            const now = new Date();
            const startDate = new Date(data.start_date);
            const endDate = new Date(data.due_date);

            startCountdown(startDate, endDate);

            // rendering the options
            let letterNumber = 65;
            for (const index in data.options) {
                const option = data.options[index];
                const letter = $(`<input type="radio" class="option__letter" required="true" name="option"></input>`);

                $(letter).attr("data-content", String.fromCharCode(letterNumber));
                $(letter).val(option.id);

                const label = $(`<div class="option__label"></div>`);
                label.text(option.text);

                const container = $(`<div class="option__container"></div>`);
                container.append(letter);
                container.append(label);

                $("#options").append(container);
                letterNumber++;
            }

            hasVoted = data.has_voted;
            isClosed = now < startDate || now >= endDate;
            handleSubmitButton();
        },
        errorCallback: function (err) {
            console.log(err.responseText)
        }
    });
});

$("#copy-to-clipboard").on('click', function (e) {
    const publicKey = $("#public_key").html();
    const icon = $("#copy-to-clipboard-icon");

    navigator.clipboard.writeText(publicKey).then(function () {
        $(icon).toggleClass('bi-clipboard-fill bi-clipboard-check-fill');

        setTimeout(() => {
            $(icon).toggleClass('bi-clipboard-check-fill bi-clipboard-fill');
        }, 1500);
    }, function (err) {
        $(icon).toggleClass('bi-clipboard-fill bi-clipboard-x-fill');
        setTimeout(() => {
            $(icon).toggleClass('bi-clipboard-x-fill bi-clipboard-fill');
        }, 1500);
    });
});

$("form").on("submit", function (e) {
    e.preventDefault();

    const publicKey = $("#public_key").html();
    const option = $("form").serializeArray()[0].value;

    const crypt = new JSEncrypt();
    crypt.setPublicKey(publicKey);
    const encryptedVote = crypt.encrypt(option);

    if ($("#submit_cta").is(e.originalEvent.submitter)) {
        executeAjaxCall({
            apiName: "add-vote",
            method: "POST",
            data: { poll: id, option: encryptedVote },
            successCallback: function (res) {
                hasVoted = true;
                handleSubmitButton();
            },
            errorCallback: function (err) {
                console.log(err);
            }
        });
    }



});