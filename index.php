<?php
    require_once 'functions.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName = $_POST['userName'];
        $password = $_POST['password'];
        if (login($userName, $password)) {
            header('Location: admin.php');
        } else {
            if (isAuthorized()) {
                saveUserName($userName);
                header('Location: list.php');
            } else {
                $errors[] = 'Invalid user data';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/styles.css"/>
</head>
<body>
    <section class="main-container">
        <h1>Авторизация</h1>
        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data" class="file-input-form">
                <input type="text" name="userName" placeholder="Имя" class="input-user-name">
                <input type="password"  name="password" placeholder="Пароль" class="input-user-name">
                <input type="submit" value="Вход" class="button select-button">
            </form><br>
        </div>
        <div class="result">
            <?php printErrors(); ?>
        </div>
    </section>
</body>
</html>
