<?php
   require_once 'functions.php';

   $testResults = '';//переменная для вывода результатов теста
   $fileName = '';
   $data = [];//массив для декодированного json-файла
   $fields = [];//
   $lastTest = count(scandir('files/')) - 2;//максимальный номер теста
   $user = readUserName('guest');
   if ((empty($_GET['test_number'])) || ($_GET['test_number'] > $lastTest)) {
       http_response_code(404);
       echo '<div class="form-container"><h1>Cтраница не найдена!</h1></div>';
       exit(1);
   }
   if (isset($_GET['test_number'])) {
       $fileName = "test_" . htmlspecialchars($_GET['test_number']) . ".json";
       $data = get_json_data($fileName);
   }
   if (isset($_POST['userName'])) {
       $userName = $_POST['userName'];
       $fields = array_values($_POST);
       $sum = 0;//сумма для правильных ответов
       $c = 0;//число ответов
       foreach ($fields as $k => $v)  {
           if ($v == 'true') {
              $sum++;
           }
           if ($v != $userName) {
              $c++;
           }
       }
       $testResults = "<h2>Правильных ответов: $sum из $c</h2>";
       if ($c > 0) {
           $testRating = round(100 * $sum / $c, 0);
       } else {
           $testRating = 0;
       }
   }
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
   <meta charset="utf-8">
   <title>test</title>
   <link rel="stylesheet" href="css/styles.css"/>
 </head>
 <body>
   <section class="main-container">
       <h1>Тест: <?php echo $data['testName']; //вывод названия теста ?></h1>
       <div class="form-container">
           <form action="<?php $_SERVER['PHP_SELF'];?>" method="post" class="test-form">
               <?php $q = 0;//инициализация счетчика вопросов для создания уникальных имен групп radio-переключателей
               foreach ($data['questions'] as $question): ?>
                   <fieldset class="question">
                       <legend class="question-legend"><?php echo $question['question']; $q++; ?></legend>
                       <?php foreach ($question['answers'] as $answer_key => $answer): ?>
                           <label class="test-answer"><?php echo $answer['answer']; ?>
                               <input type="radio" name="<?php echo "q$q"; ?>" value="<?php echo $answer['isRight']; ?>" /><br>
                           </label>
                       <?php endforeach; ?>
                   </fieldset>
               <?php endforeach; ?>
               <input type="text" class="input-user-name" name="userName" value="<?php echo $user; ?>" hidden>
               <input type="submit" value="Результаты теста" class="button result-button" />
           </form>
           <a href="logout.php">Выход</a>
       </div>
       <div class="form-container">
           <?php
               if ($testResults) {
                   echo $testResults;
                   if ($testRating > 80) {
                       echo '<img src="sertificate.php?userName=' . $userName . '" alt="picture">';
                   }
               }
           ?>
       </div>
   </section>
 </body>
</html>
