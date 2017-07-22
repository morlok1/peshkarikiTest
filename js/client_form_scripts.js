
/**
 * Осуществляет весь цикл работы с формой
 */
function processClientForm() {

    //Чистим старый результат
    document.getElementById("result").innerText = "";

    if (validateClientForm()) {

        var address = document.getElementById("address_form").value;
        var phone = document.getElementById("phone_form").value;
        submitClientForm(address, phone);

    }
}

/**
 * Проверяет поля формы на корректность:
 *      Фамилия Имя Отчество - не пусто
 *      Телефон - содержит ровно 10 символов
 *      Адрес - не пусто
 * @returns {boolean} - корректность заполненной формы
 */
function validateClientForm() {
//Сбрасываем старые сообщения об ошибках
    document.getElementById("fio_error").innerHTML = "";
    document.getElementById("phone_error").innerText = "";
    document.getElementById("address_error").innerText = "";


    var isValid = true;

    if (!document.getElementById("fio_form").value.length) {
        document.getElementById("fio_error").innerText = "Empty";
        isValid = false;
    }

    if (document.getElementById("phone_form").value.length != 10) {
        document.getElementById("phone_error").innerText = "Not 10 characters";
        isValid = false;
    }

    if (!document.getElementById("address_form").value.length) {
        document.getElementById("address_error").innerText = "Empty";
        isValid = false;
    }

    return isValid;
}


/**
 * Осуществляет отправку данных на сервер для
 * получения названия ближайшего пункта
 * выдачи и расстояния до него.
 * @param address - адрес клиента
 */
function submitClientForm(address, phone) {

    //Здесь будет происходить отправка формы
    var url = "ajax.php?"
            + "address=" + address
            + "&phone=" + phone;
    var xhr = new XMLHttpRequest();

    //Конфигурируем запрос
    xhr.open('GET', url, false);
    //Посылаем запрос
    xhr.send();
    if (xhr.status != 200)
    {
        document.getElementById('result').innerText = "Some thing wrong...";
    }
    else
    {
        var json_data = xhr.responseText;
        var data = JSON.parse(json_data);
        if (data['error'] == 0) {
            var resultString;
            var fio = document.getElementById("fio_form").value;
            var phone = data['phone'];
            var point = data['pointName'];
            var distance = data['distance']

            resultString = fio + " (" + phone + "): ближайщий пункт выдачи " +
                            point + " находится на расстоянии " + distance + " км.";
            document.getElementById("result").innerText = resultString;
        }
        else {
            document.getElementById("result").innerText = "Что-то пошло не так. Проверьте, пожалуйста, свои галактические координаты."
        }

    }
}
