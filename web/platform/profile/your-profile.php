<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="module" src="/web/js/main.js"></script>
    <script type="module" src="/web/js/platform/profile/your_profile.js"></script>

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/profile/userlists/new.css">

    <title data-translation="LABELS.your_profile.title"></title>
</head>

<body>
    <?php include_once "../../header.php"; ?>
    <form class="container form__container">
        <h1 class="form__heading" data-translation="LABELS.your_profile.title"></h1>
        <div class="form__inputs">
            <div class="row">
                <label for="name" class="form-label" data-translation="LABELS.forms.name.label"></label>
                <input type="text" class="form-control" id="name" name="name" required="true">
                <div class="invalid-feedback" data-translation="LABELS.forms.name.error"></div>
            </div>
            <div class="row">
                <label for="surname" class="form-label" data-translation="LABELS.forms.surname.label"></label>
                <input type="text" class="form-control" id="surname" name="surname" required="true">
                <div class="invalid-feedback" data-translation="LABELS.forms.surname.error"></div>
            </div>
            <div class="row">
                <label for="email" class="form-label" data-translation="LABELS.forms.email.label"></label>
                <input type="text" class="form-control" id="email" name="email" required="true" readonly="true">
            </div>
            <div class="row">
                <label for="password" class="form-label" data-translation="LABELS.forms.password.label"></label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="invalid-feedback" data-translation="LABELS.forms.password.error"></div>
            </div>
            <div class="row">
                <label for="confirm_password" class="form-label" data-translation="LABELS.forms.confirm_password.label"></label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                <div class="invalid-feedback" data-translation="LABELS.forms.confirm_password.error"></div>
            </div>
        </div>
        <div class="cta__container">
            <button type="submit" class="btn btn-primary" data-translation="LABELS.sign_in.submit"></button>
        </div>

        <div id="response" class="alert d-none" role="alert"></div>

    </form>
</body>

</html>