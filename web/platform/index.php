<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="/web/js/main.js"></script>
    <script type="module" src="/web/js/platform/index.js"></script>

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/index.css">

    <title data-translation="LABELS.dashboard.title"></title>
</head>

<body>
    <?php
    include_once "../header.php";
    ?>
    <div class="dashboard">
        <div class="dashboard__section">
            <div class="heading__container">
                <h3 class="heading" data-translation="LABELS.dashboard.active_polls"></h3>
            </div>
            <div class="polls" id="active_polls">
            </div>
        </div>
        <div class="dashboard__section">
            <div class="heading__container">
                <h3 class="heading" data-translation="LABELS.dashboard.past_polls"></h3>
            </div>
            <div class="polls" id="past_polls">
            </div>
        </div>
    </div>
</body>

</html>