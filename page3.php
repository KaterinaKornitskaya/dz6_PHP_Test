<?php
session_start();
$userName = $_SESSION['userName'];
$score1 = $_SESSION['scoreTest1'];
$score2 = $_SESSION['scoreTest2'];
$totalScore = $score1 + $score2;

echo "<p> Привет, $userName</p>";
echo "<p> Вашы баллы за первый тест: $score1</p>";
echo "<p> Вашы баллы за второй тест: $score2</p>";
echo "<p> Общий балл: $totalScore</p>";

//// Проверка на отправку формы
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    // если форма отправлена - удаляем сесионные переменные
//    unset($_SESSION['scoreTest1']);
//    unset($_SESSION['scoreTest2']);
//}

echo <<<FRM
<form action="page1.php" method="POST">
<input type="submit" value="Пройти тест еще раз?">
</form>
FRM;





?>


