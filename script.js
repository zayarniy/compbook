const subjectSelect = document.getElementById('subjectSelect');
const teacherSelect = document.getElementById('teacherSelect');



// Функция для заполнения select значениями из subjects
function populateSelect(subjects, selectElement, doIt) {
    // Очищаем текущие значения в select
    //selectElement.innerHTML = '<option value="">Выберите предмет</option>';

    // Проходим по каждому элементу массива subjects
    subjects.forEach(subject => {
        const option = document.createElement('option'); // Создаем элемент option
        option.value = doIt(subject)//subject.id; // Устанавливаем значение
        option.textContent = doIt(subject); // Устанавливаем текст для отображения
        selectElement.appendChild(option); // Добавляем элемент option в select
    });
}

let sortSubjects = subjects.sort((a, b) => {
    return a["name"].localeCompare(b["name"]);
});

sortSubjects.push({
    id: 100,
    name: "нет в списке"
});

let sortTeachers = teachers.sort((a, b) => {
    if (a["lastname"] == "нет в списке") return true;
    return a["lastname"].localeCompare(b["lastname"]);
});

// Вызов функции для заполнения select
populateSelect(sortSubjects, subjectSelect, (data) => data.name);
populateSelect(sortTeachers, teacherSelect, (data) => data.lastname + " " + data.firstname + " " + data.surname);


// Функция для установки текущего времени в формате datetime-local
function setCurrentDatetime() {
    const input = document.getElementById('date-time-apply');
    const now = new Date(); // Получаем текущее время

    // Форматируем значение в нужный формат
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Месяцы начинаются с 0
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    // Создаем строку в нужном формате
    const datetimeLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
    input.value = datetimeLocal; // Устанавливаем значение в input
}

// Вызываем функцию для установки текущего времени при загрузке страницы
setCurrentDatetime();

function saveData() {


    
    let teacher = (document.getElementById("teacherSelect").value);
    let grade = (document.getElementById("grade").value);
    let dateTimeApply = (document.getElementById("date-time-apply").value);
    let reason = document.getElementById("reason").value;
    let story = (document.getElementById("story").value);
    let subject = (document.getElementById("subjectSelect").value);
    Swal.fire({
        title: "Проверка:", html:
            `Учитель:<strong>${teacher}</strong><br>
            Предмет:<strong>${subject}</strong><br>
            Оценка:<strong>${grade}</strong><br>
            Время:<strong>${dateTimeApply}</strong><br>`, showCancelButton: true, confirmButtonText: "Отправить", cancelButtonText: "Нет"
    }).then((result) => {
        if (result.isConfirmed) {
            let timeApplySend = new Date();
            // Создаем объект FormData
            const formData = new FormData();

            // Добавляем данные в формат
            //formData.append('id', this.userID);
            formData.append('time_apply_send', timeApplySend.toISOString());
            formData.append('user_teacher', document.getElementById("teacherSelect").value);
            formData.append('user_grade', document.getElementById("grade").value);
            formData.append('user_time', document.getElementById("date-time-apply").value);
            formData.append('user_reason', document.getElementById("reason").value);
            formData.append('user_story', document.getElementById("story").value);
            formData.append('user_subject', document.getElementById("subjectSelect").value);
            // Отправляем данные на сервер
            fetch("php/savetodb.php", {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    // Обрабатываем ответ от сервера
                    console.log('Response:', response);



                })
                .catch(error => {
                    // Обрабатываем ошибки
                    console.error('Error:', error);
                });

            window.location.reload();

        }


    });
}