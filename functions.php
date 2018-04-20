<?php

    session_start();
    $errors = [];

    function writeLog($data)//функция записи в лог для отладки
    {
        $log = file_get_contents("log.txt");
        $data = (string)$data;
        $log .= strftime("%c", time())." >> ".$data.";\r\n";
        file_put_contents("log.txt", $log);
    }

    function get_json_data($fileName) //функция получения данных  из json-файла
    {
        $jsonFile = file_get_contents($fileName);
        $jsonData = json_decode($jsonFile, true);
        return $jsonData;
    }

    function login($login, $password) //функция проверки логина и пароля
    {
        $user = getUser($login);
        if ($user && ($user['password'] === $password)) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }

    function getUsers() //функция получения списка юзеров из файла
    {
        $jsondata = get_json_data(__DIR__.'/login.json', TRUE);
        if (!empty($jsondata)) {
            return $jsondata;
        } else {
            $errors[] = 'No users';
            return [];
        }
    }

    function getUser($login) //функция получения юзера по логину
    {
        $users = getUsers();
        foreach ($users  as $user) {
            if (in_array($login, $user)) {
                if ($user['userName'] === $login) {
                    return $user;
                }
            }
        }
        return null;
    }

    function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }

    function saveUserName($userName)
    {
        $users = getUsers();
        $users[] = array('userName' => $userName, 'password' => '');
        file_put_contents('login.json',json_encode($users));
        return $users;
    }

    function readUserName()
    {
        $users = getUsers();
        foreach ($users as $key => $user) {
            if ($user['password'] === '') {
                return $user['userName'];
            }
        }
    }

    function delUserName($userName)
    {
        $users = getUsers();
        foreach ($users as $key => $user) {
            if ($user['userName'] === $userName) {
                unset($user);
            }
        }
        file_put_contents('login.json',json_encode($users));
        return $users;
    }

    function printErrors()//функция печати перечня ошибок
    {
        if (!empty($errors)) {
            foreach ($errors as $err) {
                echo "$err<br>";
            }
        } else {
            echo "";
        }
    }

    function get_test_name($fileName) //функция получения названия теста из файла
    {
        $data = get_json_data($fileName);
        return $data['testName'];
    }

?>
