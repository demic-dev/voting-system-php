<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/2.3.1/jsencrypt.min.js" integrity="sha512-zDvrqenA0eFJZCxBsryzUZcvihvNlEXbteMv62yRxdhR4s7K1aaz+LjsRyfk6M+YJLyAJEuuquIAI8I8GgLC8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="/web/js/main.js"></script>
    <script type="module" src="/web/js/platform/poll.js"></script>

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/poll.css">

    <title></title>
</head>

<body>
    <?php include_once "../header.php"; ?>
    <form class="poll">
        <div class="heading__container">
            <h1 class="heading" id="heading"></h1>
            <div class="status__container">
                <div class="badge__container">
                    <i class="bi bi-clock-fill"></i>
                    <span id="countdown"></span>
                </div>
            </div>
        </div>

        <div class="alert report__container d-none" id="poll_stats__container" role="alert">
        </div>

        <div class="alert alert-light keycode__container" role="alert">
            <span class="keycode__label" data-translation="LABELS.vote.public_key"></span>
            <code class="keycode__value" id="public_key"></code>
            <button type="button" class="btn btn-light" id="copy-to-clipboard">
                <i class="bi bi-clipboard-fill" id="copy-to-clipboard-icon"></i>
            </button>
        </div>

        <div class="description" id="description"></div>
        <div class="options__container" id="options"></div>
        <div class="alert d-none" id="voted-response" role="alert"></div>
        <div class="ctas__container">
            <button type="submit" class="cta proxy d-none" id="proxy_1_cta"></button>
            <button type="submit" data-translation="LABELS.vote.main_cta" class="cta" id="submit_cta"></button>
            <button type="submit" class="cta proxy d-none" id="proxy_0_cta"></button>
        </div>
    </form>
</body>

</html>