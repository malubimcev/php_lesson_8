<?php
    require_once 'functions.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName = trim((string)$_POST['userName']);
        $password = trim((string)$_POST['password']);
        if ((!empty($userName)) && login($userName, $password)) {
            if (isAdmin()) {
                redirect('admin.php');
            }
        } else {
            if (isAuthorized()) {
                redirect('list.php');
            } else {
                $errors[] = 'Пользователь не авторизован';
                header('HTTP/1.0 401 Unauthorized');
                printErrors();
                $errors[] = 'Пользователь не авторизован';
                exit;
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
