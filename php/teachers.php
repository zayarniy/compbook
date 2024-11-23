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
    //logError( $_POST['ids']);
    $idsToDelete = $_POST['ids']; // массив идентификаторов
    foreach ($idsToDelete as $id) {
        $conn->query("DELETE FROM teachers WHERE id = $id");
    }
}

// Обработка добавления предмета
if (isset($_POST['add'])) {
    $firstname = trim($_POST['teacher_firstname']);
    $surname = trim($_POST['teacher_surname']);
    $lastname = trim($_POST['teacher_lastname']);
    $action = trim($_POST['teacher_action']);
    $mail = trim($_POST['teacher_mail']);
    $password = $_POST['teacher_password'];
    //$conn->query("INSERT INTO teachers (lastname,firstname,surname,mail,action,password) VALUES ('$lastname','$firstname','$surname','$mail','$action','$password')");
    if ($conn->query("INSERT INTO teachers (lastname,firstname,surname,mail,action,password) VALUES ('$lastname','$firstname','$surname','$mail','$action','$password')") === TRUE) {
        // Успешное добавление, редирект на ту же страницу, но с пометкой о успехе
        header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1'); 
        exit;
    } else {
        echo "Ошибка: " . $conn->error;
    }

}

// Обработка обновления предмета
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['teacher_firstname'];
    $surname = $_POST['teacher_surname'];
    $lastname = $_POST['teacher_lastname'];
    $action = $_POST['teacher_action'];
    $mail = $_POST['teacher_mail'];
    $password = $_POST['teacher_password'];
    logError($id.$firstname.$lastname.$surname.$action.$mail.$password);
    $conn->query("UPDATE teachers SET lastname = '$lastname',firstname = '$firstname',surname = '$surname',mail = '$mail', action = '$action', password = '$password' WHERE id = $id");
}

// Получение списка предметов
$result = $conn->query("SELECT * FROM teachers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Management</title>
    <script>
        /*
        function updateTeacher(id) {
            const row=document.query
            
            const lastname = document.getElementById('teacher_lastname'); //prompt("Enter new techer name:");
            const firstname = document.getElementById('teacher_firstname');
            const surname = document.getElementById('teacher_surname');
            const action = document.getElementById('teacher_action');
            const mail = document.getElementById('teacher_mail');
            const password = document.getElementById('teacher_password');
            if (lastname) {
                // Скрытая форма для обновления
                document.getElementById("updateForm").style.display = "block";
                document.getElementById("updateId").value = id;
                document.getElementById("updateLastName").value = lastname;
                document.getElementById("updateFirstName").value = firstname;
                document.getElementById("updateSurName").value = surname;
                document.getElementById("updateAction").value = action;
                document.getElementById("updateMail").value = mail;
                document.getElementById("updatePassword").value = password;

                document.getElementById("updateFormName").innerText = lastname;//?
            }
        }*/
       
        function update(id, lastname, firstname, surname, mail, password, action) {
            // Создание формы
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // URL для отправки; можно оставить пустым для текущей страницы.

            // Создание скрытого поля для ID
            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            idField.value = id;
            form.appendChild(idField);

            // Создание полей для обновления информации
            const lastnameField = document.createElement('input');
            lastnameField.type = 'text';
            lastnameField.name = 'teacher_lastname';
            lastnameField.value = lastname.trim();
            form.appendChild(lastnameField);

            const firstnameField = document.createElement('input');
            firstnameField.type = 'text';
            firstnameField.name = 'teacher_firstname';
            firstnameField.value = firstname.trim();
            form.appendChild(firstnameField);

            const surnameField = document.createElement('input');
            surnameField.type = 'text';
            surnameField.name = 'teacher_surname';
            surnameField.value = surname.trim();
            form.appendChild(surnameField);

            const mailField = document.createElement('input');
            mailField.type = 'email';
            mailField.name = 'teacher_mail';
            mailField.value = mail.trim();
            form.appendChild(mailField);

            const passwordField = document.createElement('input');
            passwordField.type = 'text';
            passwordField.name = 'teacher_password';
            passwordField.value = password;
            form.appendChild(passwordField);

            const actionField = document.createElement('input');
            actionField.type = 'number';
            actionField.name = 'teacher_action';
            actionField.value = action;
            form.appendChild(actionField);

            // Создание кнопки для отправки формы
            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.name = 'update';
            submitButton.innerText = 'Update Teacher';
            form.appendChild(submitButton);

            // Добавление формы на документ
            document.body.appendChild(form);
            alert(id+" "+firstname+" "+surname+" "+lastname+" "+action+" "+mail+" "+password);
            // Выполнение отправки формы
            //form.submit();
            submitButton.click();
            
        }        
        function updateTeacher(id) {
            const row = document.querySelector(`tr[data-id='${id}']`);
            const firstname = row.querySelector('input[name="firstname"]').value;
            const surname = row.querySelector('input[name="surname"]').value;
            const lastname = row.querySelector('input[name="lastname"]').value;
            const action = row.querySelector('input[name="action"]').value;
            const mail = row.querySelector('input[name="mail"]').value;
            const password = row.querySelector('input[name="password"]').value;
            
            update(id, lastname, firstname, surname, mail, password, action)
/*
            const data = new FormData();
            data.append('update', true);
            data.append('id', id);
            data.append('teacher_firstname', firstname);
            data.append('teacher_surname', surname);
            data.append('teacher_lastname', lastname);
            data.append('teacher_action', action);
            data.append('teacher_mail', mail);
            data.append('teacher_password', password);
            data.submit();

                // Скрытая форма для обновления
                document.getElementById("updateForm").style.display = "block";
                document.getElementById("updateId").value = id;
                document.getElementById("updateLastName").value = lastname;
                document.getElementById("updateFirstName").value = firstname;
                document.getElementById("updateSurName").value = surname;
                document.getElementById("updateAction").value = action;
                document.getElementById("updateMail").value = mail;
                document.getElementById("updatePassword").value = password;

                document.getElementById("updateFormName").innerText = lastname;//?*/
}

        
        function deleteTeachers() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
            //alert(checkboxes);
            const idsToDelete = Array.from(checkboxes).map(cb => cb.value);            
            
            if (idsToDelete.length > 0) {
                if  (confirm('Delete?:'+ JSON.stringify(idsToDelete))){;
                const deleteField = document.createElement('input');
                deleteField.type = 'hidden';
                deleteField.name = 'delete';
                deleteField.value = '1';
                
                // Добавляем скрытое поле в форму
                document.getElementById("deleteForm").appendChild(deleteField);
                document.getElementById("deleteForm").submit(); // Просто отправляем форму
                //document.getElementById("deleteForm").elements['ids'].value = JSON.stringify(idsToDelete);
                //document.getElementById("deleteForm").submit();
                }
            } else {
                alert("Please select at least one teacher to delete.");
            }
        }
    </script>
</head>
<body>
    <h1>Teacher Management</h1>



    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Add New Teacher</h3>
        <?php if (isset($_GET['success'])): ?>
        <script>alert("Учитель успешно добавлен!")</script>
    <?php endif; ?>
        <table>
            <tr>
                <td>
        <label for="teacher_lastname">Lastname</label>   
        </td>
        <td>
        <input type="text" name="teacher_lastname" required>
        </td>
        </tr>
        <tr>
            <td>
        <label for="teacher_firstname">Firstname</label>   </td>
        <td>
        <input type="text" name="teacher_firstname" required></td>
        </tr>
        <tr>
            <td>
        <label for="teacher_surname">Surname</label>   </td>
        <td>
        <input type="text" name="teacher_surname"></td>
        </tr>
        <tr><td>
        <label for="teacher_mail">Mail</label>   </td>
        <td>
        <input type="text" name="teacher_mail"></td>
        </tr>
        <tr>
            <td>
        <label for="teacher_password">Password</label>   </td>
        <td>
        <input type="text" name="teacher_password"></td>
        </tr>
        <tr>
            <td>
        <label for="teacher_action">Action</label>   </td>
        <td>
        <input type="text" name="teacher_action"></td>
        </tr>
        </table>
        <button type="submit" name="add">Add Teacher</button>
    </form>
    <form method="POST" id="deleteForm">
    <h3>Teacher List</h3>
    <table border="1">
        <tr>
            <th>Select</th>
            <th>ID</th>
            <th>Lastname</th>
            <th>Firstname</th>
            <th>Surname</th>
            <th>Mail</th>
            <th>Password</th>
            <th>Action</th>
            <th>Actions</th>
            
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr data-id="<?php echo $row['id']; ?>">
                <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['id']; ?></td>
                <td><input type="text" name="lastname" value='<?php echo $row['lastname']; ?>'/></td>
                <td><input type="text" name="firstname" value='<?php echo $row['firstname']; ?>'/></td>
                <td><input type="text" name="surname" value='<?php echo $row['surname']; ?>'/></td>
                <td><input type="text" name="mail" value='<?php echo $row['mail']; ?>'/></td>
                <td><input type="text" name="password" value='<?php echo $row['password']; ?>'/></td>
                <td><input type="text" name="action" value='<?php echo $row['action']; ?>'/></td>
                <td><button type="button" onclick="updateTeacher(<?php echo $row['id']; ?>)">Update</button></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <button type="button" onclick="deleteTeachers()">Delete Selected</button>
</form>
    <form method="POST" id="updateForm" style="display:none;">
        <input type="hidden" name="id" id="updateId">
        <input type="text" name="teacher_lastname" id="updateLastname">
        <input type="text" name="teacher_firstname" id="updateFirstname">
        <input type="text" name="teacher_surname" id="updateSurname">
        <input type="email" name="teacher_mail" id="updateMail">
        <input type="password" name="teacher_password" id="updatePassword">
        <input type="number" name="teacher_action" id="updateAction">
        <button type="submit" name="update">Update Teacher</button>
    </form>

</body>
</html>
