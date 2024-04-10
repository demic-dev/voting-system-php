<header class="header__container">
    <div class="languages_selector__container" id="languages_selector">
        <button type="button" id="fr-FR__lang" class="languages_selector__content">🇫🇷 FR</button>
        <button type="button" id="en-GB__lang" class="languages_selector__content">🇺🇸 EN</button>
        <button type="button" id="it-IT__lang" class="languages_selector__content">🇮🇹 IT</button>
    </div>
    <a class="logo__container" href="/web/platform/">TrustBallot</a>
    <a class="profile__link" href="/web/platform/profile/">
        <?php
        session_start();
        if (count($_SESSION) > 0) {
            echo $_SESSION['data']['name'] . " " . $_SESSION['data']['surname'];
        } else {
            session_destroy();
            header('Location: /web/sign-in.html');
        }
        ?>
    </a>
</header>