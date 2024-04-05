/* Here, there are all the functions that are executed in every page of the website. */
import { getTranslation } from './translations/index.js';

/**
 * When the page is loaded, the function iterate through
 * every element with the data-translation tag and it loads
 * the translations from the file.
 */
function setAllTranslationsLabel() {
    $('[data-translation]').each(function (_, element) {
        $(element).html(getTranslation($(element).data('translation')));
    });
}

/**
 * If there is no language set in the local storage, it's
 * set a default value based on the user's main language.
 */
function setDefaultLanguage() {
    if (!localStorage.getItem("lang")) {
        localStorage.setItem("lang", navigator?.language || navigator?.userLanguage);
    }
}

$(document).ready(function () {
    setDefaultLanguage();
    setAllTranslationsLabel();

    const activeLanguage = localStorage.getItem("lang");
    $(`#${activeLanguage}__lang`).addClass("is-selected");

    $("#languages_selector :button").on("click", function (e) {
        const selectedLanguage = e.target.id.split("_")[0];
        localStorage.setItem("lang", selectedLanguage);
        location.reload();
    });
})