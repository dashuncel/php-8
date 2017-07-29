<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR .'functions.php';

$errors = []; // массив ошибок
$nameButton = ""; // название кнопки на форме зависит от GET-параметра
$nameLink = ""; // название ссылки
$blockTime = 0; // время бокировки - запоминаем

if (isAuthorized()) {
    redirect('index','');
}

if (!session_id()) {
    session_start();
}

// проверяем, есть ли блокировка на клиенте?
if (isset($_COOKIE['block'])) {
    $timestamp = $blockTime - time();
    redirect('lock', "time?$timestamp");
}

//------ ПРОВЕРКА КАПЧИ --------:
if (isset($_POST['check'])) {
    checkCaptcha();
}

//---проверка капчи:
function  checkCaptcha()
{
    static $counter = 0;

    if (Captcha::check($_POST['captcha'])) {
        $errors[] = 'Проверочный код введён верно!';
        $counter = 0;
    } else {
        $errors[] = 'Проверочный код введён неверно!';
        $counter++;
    }

    if ($counter > MAX_BEF_LOCK) {
        $blockTime = time();
        setcookie('block', '', time() + TIME_LOCK);
        redirect('lock', "time?120");
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Список дел TODO</title>
    <link rel="stylesheet" href="./css/index.css">
    <meta charset="utf-8">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
</head>
<body>
<div id="container">
    <form action="login.php" method="POST" enctype="multipart/form-data" class="mainform">
        <p class="forgot"><a href="#">Регистрация</a><a class="hidden" href="#">Вход</a></p>
        <label for="name">Логин:</label>
        <input type="name" name="login" required>
        <label for="username">Пароль:</label>
        <input type="password" name="password">
        <div id="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="lower">
            <input type="submit" name="enter" value="Войти" class ='enter'>
            <input type="submit" name="reg" value="Регистрация" class = 'reg hidden'>
        </div>
    </form>
    <form class="captcha hidden" action="" method="POST" enctype="multipart/form-data">
        <div class="captcha">
            <p><img src='./core/gencaptcha.php' alt='Капча'/></p>
            <p>Проверочный код: <input type='text' name='captcha'/></p>
            <p><input type='submit' name='check' value='Отправить'/></p>
        </div>
    </form>
</div>
<script>
    'use strict';
    $('#lower input').click(function(event) { // надо вручную обработать результат, чтобы решить, отобразить капчу или нет
        event.preventDefault();
        let data = $('.mainform').serialize()  + "&" + this.name;
        $.post("login.php",
               data,
               function(data, result){
                   console.log(result, data);
            }
        );
    });

    $('.forgot a').click(function(event) {
        event.preventDefault();
        $('#lower input').each(function (i, val) {
            $(val).toggleClass('hidden');
        });

        $('.forgot a').each(function (i, val) {
            $(val).toggleClass('hidden');
        });
    });

</script>
</body>
</html>



