<?php

require_once 'Candidate.php';

?>


<html>
    <head>
        <meta http-equiv="Cache-Control" content="no-cache">
        <title>
            Тестовое задание
        </title>
        <link rel="stylesheet" type="text/css" href="css/client_form_styles.css">

        <script type="text/javascript" src="js/client_form_scripts.js"></script>
    </head>

    <body>

    <div id="client_form">
        <form>
            <span id="fio_text">ФИО: </span>
            <input id="fio_form" placeholder="Трензалоров Доктор Ктоктович">
            <span id="fio_error" class="error"></span>

            <span id="phone_text">Телефон: </span>
            <input id="phone_form" placeholder="(800)555-3535">
            <span id="phone_error" class="error"></span>

            <span id="address_text">Адрес: </span>
            <input id="address_form" placeholder="Улица Вязов, дом продается">
            <span id="address_error" class="error"></span>

            <input type="button" value="Найти" onclick="processClientForm()" id="client_form_submit_button">
        </form>
        <span id="result"></span>
    </div>

    </body>
</html>
