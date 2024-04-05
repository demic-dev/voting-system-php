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
    <script type="module" src="/web/js/platform/profile/polls/edit.js"></script>

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/profile/polls/new.css">

    <title></title>
</head>

<body>
    <?php include_once "../../../header.php"; ?>
    <form class="form__container">
        <div class="form__section">
            <h1 class="form__heading" data-translation="LABELS.edit_poll.details_heading"></h1>
            <div class="form__input_container">
                <div>
                    <label class="form-label" for="name" data-translation="LABELS.forms.name.label"></label>
                    <input class="form-control" type="text" id="name" name="name" required="true" autofocus>
                </div>
                <div>
                    <label class="form-label" for="description" data-translation="LABELS.forms.description.label"></label>
                    <textarea class="form-control" id="description" name="description" required="true"></textarea>
                </div>
                <div>
                    <label class="form-label" for="userlist" data-translation="LABELS.forms.description.select_userlist"></label>
                    <select class="form-select" id="userlist" name="userlist">
                        <option selected disabled></option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="start_date" data-translation="LABELS.forms.start_date.label"></label>
                    <input class="form-control" type="datetime-local" id="start_date" name="start_date" required="true">
                    <div class="invalid-feedback" data-translation="LABELS.forms.start_date.error"></div>
                </div>
                <div>
                    <label class="form-label" for="due_date" data-translation="LABELS.forms.due_date.label"></label>
                    <input class="form-control" type="datetime-local" id="due_date" name="due_date" required="true">
                    <div class="invalid-feedback" data-translation="LABELS.forms.due_date.error"></div>
                </div>
                <div>
                    <label class="form-label" for="options" data-translation="LABELS.forms.options.label"></label>
                    <div class="options__container" id="options__container">
                        <input class="form-control" type="text" id="option_0" name="option_0">
                        <input class="form-control" type="text" id="option_1" name="option_1">
                    </div>
                </div>
            </div>

        </div>

        <div class="submit__container">
            <button type="submit" class="btn btn-primary" data-translation="LABELS.sign_in.submit"></button>
        </div>

        <div id="response" class="alert d-none" role="alert"></div>

    </form>
</body>

</html>