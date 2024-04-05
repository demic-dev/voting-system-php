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

    <link rel="stylesheet" href="/web/css/globals.css">
    <link rel="stylesheet" href="/web/css/platform/profile/index.css">

    <title data-translation="LABELS.account.title"></title>
</head>

<body>
    <?php include_once "../../header.php"; ?>
    <main class="container">
        <h1 class="heading">
            <span data-translation="LABELS.account.heading"></span>
            <?php echo $_SESSION['data']['name'] . " " . $_SESSION['data']['surname'] ?>
        </h1>
        <div class="links__container">
            <div class="links__column">
                <a href="./polls/new.php" data-translation="LABELS.account.new_poll"></a>
                <a href="./polls/" data-translation="LABELS.account.your_polls"></a>
            </div>
            <div class="links__column">
                <a href="./userlists/new.php" data-translation="LABELS.account.new_userlist"></a>
                <a href="./userlists/" data-translation="LABELS.account.your_userlists"></a>
            </div>
            <div class="links__column">
                <a href="./your-profile.php" data-translation="LABELS.account.your_profile"></a>
                <a href="./sign-out.php" data-translation="LABELS.account.sign_out"></a>
            </div>
        </div>
    </main>
</body>

</html>