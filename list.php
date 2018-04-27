<?php
//list.php
    require_once 'functions.php';

    $fileList = scandir(__DIR__ . '/files/');//перечень файлов
    $testList = [];//список тестов
    foreach ($fileList as $key => $value) {
        if ($key > 1) {
            $testList[] = get_test_name($value);
        }
    }

?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>List of tests</title>
    <link rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
      <section class="main-container">
        <h1>Список тестов</h1>
        <ol class="file-list">
          <?php foreach ($testList as $test) {
            echo "<li>$test</li>";
          } ?>
        </ol>
        <div class="result">
            <?php
                if ($_SESSION['user']['userName'] === 'admin') {
                    echo '<a href="admin.php">Добавить тест</a>';
                }
            ?>
        </div>
        <div class="form-container">
          <form action="test.php" method="GET" class="file-input-form">
            <label for="test_number" class="label">Выберите номер теста:</label>
            <input type="text" name="test_number">
            <input type="submit" value="Загрузить тест" class="button select-button">
          </form>
          <a href="logout.php">Выход</a>
        </div>
     </section>
  </body>
</html>
