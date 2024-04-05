import * as translations from './translations.js';

export function getCurrentLanguage() {
    const selectedLang = localStorage.getItem("lang");

    return selectedLang || navigator?.language || navigator?.userLanguage;
}

export function getTranslation(labelString, ...variables) {
    try {
        const language = getCurrentLanguage().split("-")[0];

        const subLabels = labelString.split(".");
        let result = translations.default;
        for (const index in subLabels) {
            result = result?.[subLabels[index]];
        }
        if (variables.length) {
            let bareTranslation = result[language];

            for (const index in variables) {
                bareTranslation = bareTranslation.replace("~", variables[index]);
            }

            return bareTranslation || labelString;
        }

        return result?.[language] || labelString;
    } catch (e) {
        return labelString;
    }
}
