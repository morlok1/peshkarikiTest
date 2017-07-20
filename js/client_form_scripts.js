
/**
 * Осуществляет весь цикл работы с формой
 */
function processClientForm() {

    if (validateClientForm()) {

        var address = document.getElementById("address_form").value;
        submitClientForm(address);

    }
    else {
        document.getElementById("result").innerText = "No.";
    }
}

/**
 * Проверяет поля формы на корректность:
 *      Фамилия Имя Отчество - не пусто
 *      Телефон - содержит больше 6 символов и меньше 12
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

    var phoneLength = document.getElementById("phone_form").value.length;
    if (phoneLength < 7) {
        document.getElementById("phone_error").innerText = "Less than 7";
        isValid = false;
    } else if (phoneLength > 11) {
        document.getElementById("phone_error").innerText = "More than 11";
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
function submitClientForm(address) {
    //Здесь будет происходить отправка формы
    var url = "ajax.php?"
            + "address=" + address;
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
        document.getElementById('result').innerText = xhr.responseText;
    }
}