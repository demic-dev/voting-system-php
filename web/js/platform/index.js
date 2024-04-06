import { getCurrentLanguage } from '../translations/index.js';
import { executeAjaxCall } from '../utils.js';

$(document).ready(function (e) {
    executeAjaxCall({
        apiName: "polls-per-user",
        method: "GET",
        successCallback: function (res) {
            const data = JSON.parse(res.data);
            const dateFormat = new Intl.DateTimeFormat(getCurrentLanguage(), { dateStyle: 'medium' });
            const timeFormat = new Intl.DateTimeFormat(getCurrentLanguage(), { timeStyle: "short", hour12: false, });

            if (data.active_polls.length > 0) {
                $("#active_polls").empty();
            }
            for (const index in data.active_polls) {
                const poll = data.active_polls[index];
                const container = $(`<div class="poll"></div>`);

                const detailsContainer = $(`<div class="poll_details"></div>`);
                const title = $(`<a class="poll__heading"></a>`);
                title.attr("href", `/web/platform/poll.php?id=${poll.id}`);
                title.text(poll.name);
                const description = $(`<p class="poll__description"></p>`);
                description.text(poll.description);

                detailsContainer.append(title);
                detailsContainer.append(description);

                const infoContainer = $(`<div class="poll__info__container"></div>`);
                let end_date = $(`<div class="poll__info__detail__label"></div>`);
                end_date.text(dateFormat.format(new Date(poll.due_date)));
                end_date = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-calendar-date-fill"></i>
                    </div>
                `).append(end_date);
                let end_time = $(`<div class="poll__info__detail__label"></div>`);
                end_time.text(timeFormat.format(new Date(poll.due_date)));
                end_time = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                `).append(end_time);
                let owner = $(`<div class="poll__info__detail__label"></div>`);
                owner.text(poll.owner.surname);
                owner = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-person-fill"></i>
                    </div>
                `).append(owner);
                let voted_by = $(`<div class="poll__info__detail__label"></div>`);
                voted_by.text(`${poll.voted_by}/${poll.users}`);
                voted_by = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-shuffle"></i>
                    </div>
                `).append(voted_by);

                infoContainer.append(end_date);
                infoContainer.append(end_time);
                infoContainer.append(owner);
                infoContainer.append(voted_by);

                container.append(detailsContainer);
                container.append(infoContainer);

                $("#active_polls").append(container);
            }

            if (data.ended_polls.length > 0) {
                $("#past_polls").empty();
            }
            for (const index in data.ended_polls) {
                const poll = data.ended_polls[index];
                const container = $(`<div class="poll is-past"></div>`);

                const detailsContainer = $(`<div class="poll_details"></div>`);
                const title = $(`<a class="poll__heading is-past"></a>`);
                title.attr("href", `/web/platform/poll.php?id=${poll.id}`);
                title.text(poll.name);
                const description = $(`<p class="poll__description"></p>`);
                description.text(poll.description);

                detailsContainer.append(title);
                detailsContainer.append(description);

                const infoContainer = $(`<div class="poll__info__container is-past"></div>`);
                let end_date = $(`<div class="poll__info__detail__label"></div>`);
                end_date.text(dateFormat.format(new Date(poll.due_date)));
                end_date = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-calendar-date-fill"></i>
                    </div>
                `).append(end_date);
                let end_time = $(`<div class="poll__info__detail__label"></div>`);
                end_time.text(timeFormat.format(new Date(poll.due_date)));
                end_time = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                `).append(end_time);
                let owner = $(`<div class="poll__info__detail__label"></div>`);
                owner.text(poll.owner.surname);
                owner = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-person-fill"></i>
                    </div>
                `).append(owner);
                let voted_by = $(`<div class="poll__info__detail__label"></div>`);
                voted_by.text(`${poll.voted_by}/${poll.users}`);
                voted_by = $(`
                    <div class="poll__info__detail__container">
                        <i class="bi bi-shuffle"></i>
                    </div>
                `).append(voted_by);

                infoContainer.append(end_date);
                infoContainer.append(end_time);
                infoContainer.append(owner);
                infoContainer.append(voted_by);

                container.append(detailsContainer);
                container.append(infoContainer);

                $("#past_polls").append(container);

                // <div class="poll is-past">
                //     <div class="poll__details">
                //         <a href="/" class="poll__heading is-past">Lorem ipsum dolor sit amet...</a>
                //         <div class="poll__info__container is-past">
                //             <div class="poll__info__detail__container">
                //                 <i class="bi bi-calendar-date-fill"></i>
                //                 <div class="poll__info__detail__label">13 march 2024</div>
                //             </div>
                //             <div class="poll__info__detail__container">
                //                 <i class="bi bi-clock-fill"></i>
                //                 <div class="poll__info__detail__label">17:30</div>
                //             </div>
                //             <div class="poll__info__detail__container">
                //                 <i class="bi bi-person-fill"></i>
                //                 <div class="poll__info__detail__label">Michelino</div>
                //             </div>
                //             <div class="poll__info__detail__container">
                //                 <i class="bi bi-shuffle"></i>
                //                 <div class="poll__info__detail__label">3/9 voted</div>
                //             </div>
                //         </div>
                //     </div>
                // </div>
            }
        }
    });
});