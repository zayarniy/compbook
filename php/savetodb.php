<?php
// Получаем данные из запроса
// Функция для записи ошибок в файл
function logError($message) {
    $logFile = 'log.txt'; // Имя файла лога
    $timestamp = date("Y-m-d H:i:s"); // Текущая дата и время
    $fullMessage = "[$timestamp] $message\n"; // Форматируем сообщение
    file_put_contents($logFile, $fullMessage, FILE_APPEND); // Записываем в файл
}
try
{

    
    $conn = new PDO('mysql:host=localhost;dbname=host1340522_compbook', 'host1340522_mgimo', 'a32d3bd4');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO users_apply (id, time_send,grade, teacher,subject,time_apply,reason,story) VALUES (NULL, :time_send, :grade, :teacher,:subject,:time_apply,:reason,:story)";

    $user_story=$_POST['user_story'];
    $user_teacher=$_POST['user_teacher'];
    $user_grade=$_POST['user_grade'];
    $user_reason=$_POST['user_reason'];
    $user_subject=$_POST['user_subject'];

    $user_time=date("Y-m-d H:i:s", strtotime($_POST['user_time']));
    $time_apply_send = date("Y-m-d H:i:s", strtotime($_POST['time_apply_send']));

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':story', $user_story);
    $stmt->bindParam(':teacher', $user_teacher);
    $stmt->bindParam(':grade', $user_grade);
    $stmt->bindParam(':reason', $user_reason);
    $stmt->bindParam(':time_apply', $user_time);
    $stmt->bindParam(':time_send', $time_apply_send);
    $stmt->bindParam(':subject', $user_subject);

    if ($stmt->execute()) 
    {
        http_response_code(200);
        echo "Contest data saved successfully";
        logError("Contest data saved successfully".'\n');
        
    } else 
    {
        http_response_code(500);
        echo "Error saving contest data: " . $stmt->errorInfo()[2];
        logError("Error saving contest data: " . $stmt->errorInfo()[2].'\n');
    
    }

} 
catch(PDOException $e) 
{
    http_response_code(400);    
    logError("Error: " . $e->getMessage().'\n');
    echo "Error: " . $e->getMessage();
}
catch(Exception $e)
{
    http_response_code(500);    
    logError("Error: " . $e->getMessage().'\n');

}

