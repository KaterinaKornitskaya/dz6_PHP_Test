<?php
session_start();
$userName = $_SESSION['userName'];
$score1 = $_SESSION['scoreTest1'];
echo "<p> Привет, $userName</p>";
echo "<p> Вашы баллы за первый тест: $score1</p>";

$questionsArr = [];
$filename = "test2.txt";
$content = file_get_contents($filename);

if ($content == false) {
    echo "<p>Не удалось открыть файл.</p>";
} else {
    $questionsArr = explode("|", $content);
}

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем ответы пользователя

    $userAnswers = isset($_POST['answer']) ? $_POST['answer'] : [];

    $score = 0; // Счетчик правильных ответов
    foreach ($questionsArr as $index => $question) {
        // Разбиваем строку вопроса
        $parts = explode(";", $question);
        $correctAnswers = explode(",", $parts[5]); // Верные ответы
        $userAnswer = isset($userAnswers[$index]) ? $userAnswers[$index] : [];  // Ответ пользователя

        // Сравниваем массивы ответов
        sort($correctAnswers); // Сортируем для сравнения
        sort($userAnswer);
        if ($correctAnswers === $userAnswer) {
            $score++;
        }
    }

    $isAllAnswered = true; // Флаг для проверки всех вопросов
    foreach ($questionsArr as $index => $question) {
        // Проверяем, существует ли ключ $index и массив для данного вопроса не пустой
        if (!isset($_POST['answer'][$index]) || empty($_POST['answer'][$index])) {
            $isAllAnswered = false;
            break;
        }
    }

    if ($isAllAnswered) {
        // Показываем счетчик правильных ответов
        $scoreRes = $score * 3;
        echo "<p style='color: green'>Ваш результат за второй тест: $scoreRes</p>";

        $_SESSION["scoreTest2"] = $scoreRes;
        //Показываем кнопку для перехода на следующую страницу

        echo "<p style='color: green'>Вы будете перенаправлены на следующую страницу через 5 секунд...</p>";

        // Добавляем JavaScript для автоматического перенаправления на след страницу
        echo "<script>
                    setTimeout(function() {
                        window.location.href = 'page3.php';
                    }, 5000);
                  </script>";
    }
    else {
        echo "<p style='color: red'>Пожалуйста, ответьте на все вопросы!</p>";
    }

}

// Генерация теста
echo "<form action='' method='post'>";
foreach ($questionsArr as $index => $question) {
    $parts = explode(";", $question);
    $questionNumber = $parts[0];         // Номер вопроса
    $questionText = $parts[1];          // Текст вопроса
    $options = array_slice($parts, 2, 3); // Варианты ответов


    echo "<p><strong>$questionNumber. $questionText</strong></p>";

    foreach ($options as $key => $option) {
        $escapedOption = htmlspecialchars($option, ENT_QUOTES, 'UTF-8');
        echo "<label>
                <input type='checkbox' name='answer[$index][]' value='" . ($key + 1) . "'> $option
              </label><br>";
    }
    echo "<hr>";
}

echo "<input type='submit' value='Отправить'>";
echo "</form>";

