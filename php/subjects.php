<?php
// Функция для записи ошибок в файл
function logError($message) {
    $logFile = 'log.txt'; // Имя файла лога
    $timestamp = date("Y-m-d H:i:s"); // Текущая дата и время
    $fullMessage = "[$timestamp] $message\n"; // Форматируем сообщение
    file_put_contents($logFile, $fullMessage, FILE_APPEND); // Записываем в файл
}

// index.php
$conn = new mysqli("localhost", "host1340522_mgimo", "a32d3bd4", "host1340522_compbook");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка удаления предмета
if (isset($_POST['delete'])) {
    logError( $_POST['ids']);
    $idsToDelete = $_POST['ids']; // массив идентификаторов
    foreach ($idsToDelete as $id) {
        $conn->query("DELETE FROM subjects WHERE id = $id");
    }
}

// Обработка добавления предмета
if (isset($_POST['add'])) {
    $name = $_POST['subject_name'];
    $conn->query("INSERT INTO subjects (name) VALUES ('$name')");
}

// Обработка обновления предмета
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['subject_name'];
    $conn->query("UPDATE subjects SET name = '$name' WHERE id = $id");
}

// Получение списка предметов
$result = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects Management</title>
    <script>
        function updateSubject(id) {
            const name = prompt("Enter new subject name:");
            if (name) {
                // Скрытая форма для обновления
                document.getElementById("updateForm").style.display = "block";
                document.getElementById("updateId").value = id;
                document.getElementById("updateName").value = name;
                document.getElementById("updateFormName").innerText = name;
            }
        }
        
        function deleteSubjects() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
            alert(checkboxes);
            const idsToDelete = Array.from(checkboxes).map(cb => cb.value);            
            
            if (idsToDelete.length > 0) {
                alert(JSON.stringify(idsToDelete));
                const deleteField = document.createElement('input');
                deleteField.type = 'hidden';
                deleteField.name = 'delete';
                deleteField.value = '1';
                
                // Добавляем скрытое поле в форму
                document.getElementById("deleteForm").appendChild(deleteField);
                document.getElementById("deleteForm").submit(); // Просто отправляем форму
                //document.getElementById("deleteForm").elements['ids'].value = JSON.stringify(idsToDelete);
                //document.getElementById("deleteForm").submit();
            } else {
                alert("Please select at least one subject to delete.");
            }
        }
    </script>
</head>
<body>
    <h1>Subjects Management</h1>

    <form method="POST" id="deleteForm">
    <h3>Subjects List</h3>
    <table border="1">
        <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><button type="button" onclick="updateSubject(<?php echo $row['id']; ?>)">Update</button></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <button type="button" onclick="deleteSubjects()">Delete Selected</button>
</form>

    <form method="POST">
        <h3>Add New Subject</h3>

        <input type="text" name="subject_name" required>
        <button type="submit" name="add">Add Subject</button>
    </form>

    <form method="POST" id="updateForm" style="display:none;">
        <input type="hidden" name="id" id="updateId">
        <input type="text" name="subject_name" id="updateName">
        <button type="submit" name="update">Update Subject</button>
    </form>

</body>
</html>
