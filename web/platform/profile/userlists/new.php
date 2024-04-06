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
    <script type="module" src="/web/js/platform/profile/userlists/new.js"></script>

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/profile/userlists/new.css">

    <title data-translation="LABELS.new_userlist.title"></title>
</head>

<body>
    <?php include_once "../../../header.php"; ?>
    <form class="container form__container">
        <h1 class="form__heading" data-translation="LABELS.new_userlist.heading"></h1>
        <div class="form__inputs">
            <div class="row">
                <div>
                    <label for="name" class="form-label" data-translation="LABELS.forms.userlist_name.label"></label>
                    <input type="text" class="form-control" id="name" name="name" required="true">
                </div>
            </div>
            <div class="userlist__container">
                <div class="userlist__wrapper">
                    <h2 class="form__subheading" data-translation="LABELS.new_userlist.selected_users"></h2>
                    <div class="userlist__subsection" id="selected-users">
                    </div>
                </div>
                <div class="userlist__wrapper">
                    <h2 class="form__subheading" data-translation="LABELS.new_userlist.select_users"></h2>
                    <div class="userlist__subsection" id="all-users">
                    </div>
                </div>
            </div>
        </div>
        <div class="cta__container">
            <button type="submit" class="btn btn-primary" data-translation="LABELS.sign_in.submit"></button>
        </div>
    </form>
</body>

</html>