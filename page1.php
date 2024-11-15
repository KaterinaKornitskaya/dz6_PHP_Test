<p>Page1</p>

<?php
session_start();

$userName = $_SESSION['userName'];
echo "<p>Привет, " . $userName . "</p>";

$filename = "test1.txt";
// Получаем содержимое файла
$content = file_get_contents($filename);
$questionsArr = [];
if ($content === false) {
    echo "<p>Не удалось открыть файл.</p>";
    exit;
} else {
    // Разделяем содержимое на массив вопросов
    $questionsArr = explode("|", $content);
}

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    // Подготовка для проверки ответов
    $correctAnswers = [];
    $isAllAnswered = true;

    foreach ($questionsArr as $index => $question) {
        // Парсинг вопроса и вариантов ответа
        $currentQuestionArr = explode(";", $question);
        $options = array_slice($currentQuestionArr, 2, 3);
        $correctAnsw = $options[$currentQuestionArr[5] - 1];
        $correctAnswers[] = $correctAnsw;

        // Проверяем, есть ли ответ на текущий вопрос
        if (empty($_POST['answer'][$index])) {
            $isAllAnswered = false;
            break;
        }
    }

    if ($isAllAnswered) {
        // Подсчёт правильных ответов
        $score = 0;
        foreach ($_POST['answer'] as $index => $answer) {
            if ($answer == $correctAnswers[$index]) {
                $score++;
            }
        }

        // Вывод результатов и перенаправление
        echo "<p style='color: green'>Ваш результат за первый тест: $score </p>";
        echo "<p style='color: green'>Вы будете перенаправлены на следующую страницу через 5 секунд...</p>";

        $_SESSION["scoreTest1"] = $score;
        // JavaScript для перенаправления
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'page2.php';
                }, 5000);
              </script>";
        exit;
    } else {
        echo "<p style='color: red'>Нужно ответить на все вопросы!</p>";
    }
}

// Генерация формы (всегда выводится, если запрос не POST или есть ошибки)
echo "<form action='' method='post'>";

foreach ($questionsArr as $index => $question) {
    $currentQuestionArr = explode(";", $question);
    $questionNumber = $currentQuestionArr[0];
    $questionText = $currentQuestionArr[1];
    $options = array_slice($currentQuestionArr, 2, 3);

    echo "<p>$questionNumber. $questionText</p>";

    // Генерация радиокнопок для каждого варианта ответа
    foreach ($options as $option) {
        $escapedOption = htmlspecialchars($option, ENT_QUOTES, 'UTF-8');
        echo "<label><input type='radio' name='answer[$index]' value='$escapedOption'> $option</label><br>";
    }
    echo "<hr>";
}

echo "<input type='submit' value='Отправить'>";
echo "</form>";
?>
