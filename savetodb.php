<?php
// Получаем данные из запроса
try
{

    
    $conn = new PDO('mysql:host=localhost;dbname=host1340522_compbook', 'host1340522_mgimo', 'a32d3bd4');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO users_apply (id, time_send,teacher,subject,time_apply,reason,story) VALUES (NULL, :time_send,:teacher,:subject,:time_apply,:reason,:story)";

    $user_story=$_POST['user_story'];
    $user_teacher=$_POST['user_teacher'];
    $user_grade=$_POST['user_grade'];
    $user_reason=$_POST['user_reason'];

    $user_time=date("Y-m-d H:i:s", strtotime($_POST['user_time']));
    $time_apply_send = date("Y-m-d H:i:s", strtotime($_POST['time_apply_send']));

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_story', $user_story);
    $stmt->bindParam(':user_teacher', $user_teacher);
    $stmt->bindParam(':user_grade', $user_grade);
    $stmt->bindParam(':user_reason', $user_reason);
    $stmt->bindParam(':user_time', $user_time);
    $stmt->bindParam(':time_apply_send', $time_apply_send);

    if ($stmt->execute()) 
    {
        http_response_code(200);
        echo "Contest data saved successfully";
    } else 
    {
        http_response_code(500);
        echo "Error saving contest data: " . $stmt->errorInfo()[2];
    }

} 
catch(PDOException $e) 
{
    http_response_code(400);
    $logData="Error: " . $e->getMessage().'\n';
    file_put_contents($logFile, $logData, FILE_APPEND);  
    //echo "Error: " . $e->getMessage();
}
catch(Exception $e)
{
    http_response_code(500);
    $logFile='log.txt';
    $logData="Error: " . $e->getMessage().'\n';
    file_put_contents($logFile, $logData, FILE_APPEND);  

}

