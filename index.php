<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Test</h1>


<?php
// Запускаем сессию
session_start();
// Если форма отправлена, проверяем, ввел ли пользователь имя
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!empty($_POST["userName"])){
        // Если имя введено, сохраняем его в сессии
        $_SESSION['userName'] = htmlentities($_POST["userName"]);
        // перенаправляем на вторую страницу
        header("Location: page1.php");
        exit;
    }
    else{
        // Если имя не введено, отображаем сообщение об ошибке
        $error = "Пожалуйста, введите свое имя";
    }

    if(!empty($error)){
        echo "<p style='color: red'>$error</p>";
    }
}
?>

<form action="index.php" method="post">
    <label>
        <input type="text" name="userName" placeholder="Введите свое имя, пожалуйста.">
    </label>
    <input type="submit" value="Продолжить">
</form>

</body>
</html>