<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR .'functions.php';

//обработчик пост-запроса на странице авторизации:
$login = filter_input(INPUT_POST, 'login');
$passwd = filter_input(INPUT_POST, 'password');
$counter = 0;
$res = '';

if (isset($_POST['enter'])) {
    $counter = 0;
    if (! isset($_COOKIE['counter'])) {
        setcookie('counter',0);
    }
    else $counter = $_COOKIE['counter'];

    if ($login == "Гость") {
        $_SESSION['user'] = $login;
        redirect('list', ''); // пропускаем админскую страничку, сразу к списку тестов
    }

    if (login($login, $passwd)) {
        $_SESSION['user'] = $login;
        setcookie('counter' , 0);
        redirect('admin', '');
    } else {
        $counter++;
        setcookie('counter' , $counter);
        $res = "Неверный логин или пароль";
    }
}
elseif (isset($_POST['reg'])) {
    $res = addUser($login, $passwd);

    if ($res === true) {
        $_SESSION['user'] = $login;
        redirect('admin', ''); // пропускаем админскую страничку, сразу к списку тестов
    }
}

echo $res;
