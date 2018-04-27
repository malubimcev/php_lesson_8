<?php
    session_start();
    $errors = [];

    function writeLog($data)//функция записи в лог для отладки
    {
        if (is_readable('log.txt')) {
            $log = file_get_contents("log.txt", TRUE);
            $data = (string)$data;
            $log .= strftime("%c", time()) . " >> " . $data . ";\r\n";
            file_put_contents("log.txt", $log);
        } else {
            echo "Файл log.txt не найден.";
        }
    }

    function get_json_data($fileName) //функция получения данных  из json-файла
    {
        if (is_readable($fileName)) {
            $jsonFile = file_get_contents($fileName, TRUE);
        } else {
            $errors[] = "Файл $fileName не найден.";
        }
        if (!empty($jsonFile)) {
            $jsonData = json_decode($jsonFile, true);
            return $jsonData;
        } else {
            $errors[] = 'No json data';
            return [];
        }
    }

    function login($userName, $password) //функция проверки логина и пароля
    {
        $user = getUser($userName);
        if (!$user) {
            saveUser($userName);
            $user = getUser($userName);
            $_SESSION['user'] = $user;
            return false;
        } else {
            if ($user['password'] === $password) {
                $_SESSION['user'] = $user;
                return true;
            }
        }
    }

    function getUsers() //функция получения списка юзеров из файла
    {
        $jsondata = get_json_data(__DIR__ . '/login.json');
        if (!empty($jsondata)) {
            return $jsondata;
        } else {
            $errors[] = 'No users';
            return [];
        }
    }

    function getUser($userName) //функция получения юзера по имени
    {
        $users = getUsers();
        if (isset($users)) {
            foreach ($users  as $user) {
                if ((in_array($userName, $user)) && ($user['userName'] === $userName)) {
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

    function isAdmin() {

	return isAuthorized() && $_SESSION['user']['isAdmin'];
    }

    function saveUser($userName)
    {
        $users = getUsers();
        $users[] = array('login' => 'guest', 'userName' => $userName, 'password' => '', 'isAdmin' => '0');
        file_put_contents('login.json', json_encode($users));
    }

    function readUserName($login)
    {
        $users = getUsers();
        foreach ($users as $key => $user) {
            if ($user['login'] === $login) {
                return $user['userName'];
            }
        }
    }

    function delUser()
    {
        $users = getUsers();
        foreach ($users as $key => $user) {
            if ($user['userName'] === $_SESSION['user']['userName']) {
                unset($users[$key]);
                file_put_contents('login.json', json_encode($users));
                return;
            }
        }
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

    function redirect($page)
    {
	header("Location: $page");
	die;
    }

    function logout()
    {
        delUser();
	session_destroy();
	redirect('index.php');
    }
