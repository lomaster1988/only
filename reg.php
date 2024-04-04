<?php
include("database.php");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>Создать аккаунт</h2>
        Имя:<br>
        <input type="text" name="username"><br>
        Телефон:<br>
        <input type="text" name="phone"><br>
        Почта:<br>
        <input type="text" name="mail"><br>
        Пароль:<br>
        <input type="password" name="password"><br>
        Повторите пароль:<br>
        <input type="password" name="password2"><br><br>
        <input type="submit" name="submit" value="Регистрация">
    </form>
    <a href="index.php">У вас уже есть аккаунт?</a><br><br>
</body>

</html>

<?php

session_start();

if (isset($_SESSION["username"])) {
    header("Location: profile.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
    $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_SPECIAL_CHARS);
    
    $err_message = "Все поля обязательны для заполнения!";
    $err_message2 = "Указанные пароли не совпадают!";
    
    if (empty($username) || empty($phone) || empty($password) || empty($password2) || empty($mail)) {
        echo $err_message;
    } else if ($password !== $password2) {
        echo $err_message2;
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (user, password, phone, mail)
                    VALUES ('$username', '$hash', '$phone', '$mail')";
        try {
            mysqli_query($conn, $sql);
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["phone"] = $_POST["phone"];
            $_SESSION["mail"] = $_POST["mail"];
            $_SESSION["password"] = $_POST["password"];
            header("Location: profile.php");
        } catch (mysqli_sql_exception) {
            echo "Ошибка при регистрации. Пользователь с указанными данными
                        уже существует";
        }
    }
    }


?>