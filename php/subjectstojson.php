<?php
// Настройки базы данных
$host = 'localhost';  // Хост
$dbname = 'host1340522_compbook';       // Имя базы данных
$username = 'host1340522_mgimo';   // Имя пользователя
$password = 'a32d3bd4';       // Пароль

try {
    // Подключаемся к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Запрос для получения всех записей из таблицы subject
    $stmt = $pdo->query("SELECT id, name FROM subjects");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Преобразуем полученные данные в формат JSON
    $jsonSubjects = json_encode($subjects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Создание JavaScript файла
    $jsFile = 'subjects.js';
    $jsContent = "const subjects = $jsonSubjects;";

    // Запись данных в файл
    file_put_contents($jsFile, $jsContent);

    echo "Файл '$jsFile' успешно создан.";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
