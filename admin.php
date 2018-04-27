<?php
    require_once __DIR__ . '/functions.php';

    if ((!isAuthorized()) || (!isAdmin())) {
        http_response_code(403);
        echo '<div class="form-container"><h1>Доступ запрещен!</h1></div>';
        die;
    }
    $maxFileSize = 100*1024; //ограничение размера файла
    $validFileType = ["json"];//разрешенные форматы файлов
    $errorArray = [];//массив для записи ошибок
    $path = __DIR__ . '/files/'; //путь для нового файла
    $fileName = '';
    // если есть отправленные файлы
    $result = '';//для вывода результата на страницу
    if ($_FILES) {
        // валидация размера файла
        $fileName = $_FILES["user_file"]["name"];
        $fileSize = $_FILES["user_file"]["size"];
        $tmpName = $_FILES["user_file"]["tmp_name"];
        if ($fileSize > $maxFileSize) {
            $errorArray[] = "Размер файла превышает допустимый!";
        }
        // валидация формата файла
        $info = pathinfo($fileName);
        $format = $info['extension'];
        if(!in_array($format, $validFileType)) {
            $errorArray[] = "Недопустимый формат файла!";
        }
        // если не было ошибок
        if (empty($errorArray)) {
            // проверяем загружен ли файл
            if (is_uploaded_file($tmpName)) {
                // сохраняем файл
                if (move_uploaded_file($tmpName, $path.$fileName)) {
                    writeLog('файл ' . $path . $fileName . ' сохранен');
                    redirect('list.php');//редирект на список тестов
                } else {
                    // Если файл не переместился
                    $errorArray[] = 'Ошибка сохранения';
                }
            } else {
                // Если файл не загрузился
                $errorArray[] = 'Ошибка загрузки';
            }
        } else {
            $result = 'Ошибки:<br>' . implode(';', $errorArray);
        }
    } else {
        $result = 'файл не выбран';
    }
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
      <section class="main-container">
        <h1>Загрузка файлов</h1>
        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data" class="file-input-form">
              <input type="file" name="user_file"><br>
              <input type="submit" value="Загрузить" class="button select-button"><br>
            </form><br>
            <a href="logout.php">Выход</a>
        </div>
        <div class="result">
            <?php echo $result; ?>
        </div>
    </section>
  </body>
</html>
