<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Книга жалоб и предложений, 2024-2025</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <?php
    // Подключение к базе данных
    $servername = "localhost";
    $username = "host1340522_mgimo";
    $password = "a32d3bd4";
    $dbname = "host1340522_compbook";


try {
    // Создание нового подключения PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Выполнение SQL-запроса
    $sql = "SELECT time_send as 'Отправлено',teacher as 'Учитель',subject as 'Предмет',  grade as 'Оценка',reason as 'Причина', story as 'История'  FROM users_apply ORDER BY time_send";
    
    $result = $conn->query($sql);

    // Вывод результатов в виде таблицы
    if ($result->rowCount() > 0) {
        echo "<table>";
        echo "<tr>
                <th width='200px'>Отправлено</th>
                <th>Учитель</th>
                <th>Предмет</th>
                <th>Оценка</th>
                <th>Причина</th>
                <th>История</th>
              </tr>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . $row["Отправлено"]. "</td>
                    <td>" . $row["Учитель"]. "</td>
                    <td>" . $row["Предмет"]. "</td>
                    <td>" . $row["Оценка"]. "</td>
                    <td>" . $row["Причина"]. "</td>
                    <td>" . $row["История"]. "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Нет результатов.";
    }
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}

$conn = null; // Закрытие подключения
?>
</body>

</html>