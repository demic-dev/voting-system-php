import { executeAjaxCall, getRelativeTime } from '../utils.js';
import { getTranslation } from '../translations/index.js';

const mainSubmitSelectorId = "#submit_cta";
const proxySubmit0SelectorId = "#proxy_0_cta";
const proxySubmit1SelectorId = "#proxy_1_cta";

const url = new URL(window.location.href);
const id = url.searchParams.get("id");

let hasVoted = false;
let hasVotedProxy0 = false;
let hasVotedProxy1 = false;

function startCountdown(startDate, endDate) {
    setInterval(function countdown() {
        const now = new Date();
        const container = $(".badge__container");

        const isExpired = now >= endDate;
        const isNotOpen = now < startDate;

        if (isExpired) {
            container.addClass("is-ended");

            const icon = $(container).children(".bi-clock-fill");
            icon.removeClass("bi-clock-fill");
            icon.addClass("bi-exclamation-circle-fill");

            $("#countdown").text(getTranslation("LABELS.vote.closed_ago", getRelativeTime(endDate)));
            $(".ctas__container").addClass("d-none");

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

            if (isNotOpen) {
                container.addClass("isnt-started");
                $(".ctas__container").addClass("d-none");
                $("#countdown").text(getTranslation("LABELS.vote.is_not_started", countdownString));
            } else {
                $(".ctas__container").removeClass("d-none");
                if (!hasVoted) {
                    $(mainSubmitSelectorId).removeClass("d-none");
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
        apiName: "self",
        method: "GET",
        successCallback: function (resUser) {
            const self = JSON.parse(resUser.data);
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

                    // proxies
                    const proxies = data.userlist.proxies[self.id] || [];

                    proxies.forEach((proxy, index) => {
                        if (!!proxy) {
                            const selectorId = `#proxy_${index}_cta`;
                            const user = data.userlist.users.find(u => u.id === proxy);

                            $(selectorId).removeClass("d-none");
                            $(selectorId).val(proxy);
                            $(selectorId).text(getTranslation("LABELS.vote.proxy_cta", `${user.name} ${user.surname}`));
                        }
                    });

                    // rendering ctas

                    const isClosed = data.closed;

                    hasVoted = data.has_voted;
                    hasVotedProxy0 = data.has_proxy_0;
                    hasVotedProxy1 = data.has_proxy_1;

                    if (hasVoted && !isClosed) {
                        $("#voted-response").addClass("alert-primary");
                        $("#voted-response").text(getTranslation("LABELS.vote.submitted"));

                        $(mainSubmitSelectorId).addClass("d-none");
                        $("#voted-response").removeClass("d-none");
                    }

                    if (hasVotedProxy0) {
                        $(proxySubmit0SelectorId).remove();
                    }

                    if (hasVotedProxy1) {
                        $(proxySubmit1SelectorId).remove();
                    }

                    // rendering the countdown and handle the state
                    const startDate = new Date(data.start_date);
                    const endDate = new Date(data.due_date);

                    startCountdown(startDate, endDate);

                    const totalVotesMarked = data.options.reduce(function (prev, current) {
                        return prev + current.count;
                    }, 0);

                    const max = data.options.reduce(function (prev, current) {
                        return (prev > current.count) ? prev : current.count;
                    }, -1);

                    if (isClosed) {
                        const affluence = Math.floor((totalVotesMarked * 100) / data.users);
                        $("#poll_stats__container").addClass('alert-success');
                        $("#poll_stats__container").append(`
                            <h4 class="alert-heading">
                            ${getTranslation("LABELS.vote.poll_closed", affluence, totalVotesMarked, data.users)}
                            </h4>
                        `);
                    } else {
                        const affluence = Math.floor((data.voted_by * 100) / data.users);
                        $("#poll_stats__container").addClass('alert-primary');
                        $("#poll_stats__container").append(`
                            <h3 class="alert-heading">
                                ${getTranslation("LABELS.vote.poll_stats", data.voted_by, data.users, affluence)}
                            </h3>
                        `);
                    }
                    $("#poll_stats__container").removeClass('d-none');

                    // rendering the options
                    let letterNumber = 65;
                    for (const index in data.options) {
                        const option = data.options[index];
                        const letter = $(`<input type="radio" class="option__letter" required="true" name="option"></input>`);

                        $(letter).attr("data-content", String.fromCharCode(letterNumber));
                        $(letter).val(option.id);

                        const label = $(`<div class="option__label"></div>`);
                        label.text(option.text);

                        if (isClosed) {
                            label.append('<hr />');
                            letter.attr("disabled", true);

                            const votesDetails = $('<div></div>');
                            const percentage = Math.floor((option.count * 100) / totalVotesMarked) || 0;
                            votesDetails.html(getTranslation("LABELS.vote.option_detail", option.count, totalVotesMarked, percentage));
                            label.append(votesDetails);

                            if (option.count === max) {
                                label.addClass('winner');
                                letter.addClass('winner');
                            }
                        }

                        const container = $(`<div class="option__container"></div>`);
                        container.append(letter);
                        container.append(label);

                        $("#options").append(container);
                        letterNumber++;
                    }
                }
            });
        }
    })
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

    const data = { poll: id, option: encryptedVote };

    if ($(proxySubmit0SelectorId).is(e.originalEvent.submitter)) {
        data.proxy = $(proxySubmit0SelectorId).val();
    } else if ($(proxySubmit1SelectorId).is(e.originalEvent.submitter)) {
        data.proxy = $(proxySubmit1SelectorId).val();
    }

    executeAjaxCall({
        apiName: "add-vote",
        method: "POST",
        data,
        successCallback: function (res) {
            if ($(proxySubmit0SelectorId).is(e.originalEvent.submitter)) {
                $(proxySubmit0SelectorId).remove();
            } else if ($(proxySubmit1SelectorId).is(e.originalEvent.submitter)) {
                $(proxySubmit1SelectorId).remove();

            } else {
                $("#voted-response").addClass("alert-primary");
                $("#voted-response").text(getTranslation("LABELS.vote.submitted"));

                $(mainSubmitSelectorId).remove();
                $("#voted-response").removeClass("d-none");
            }


        }
    });
});