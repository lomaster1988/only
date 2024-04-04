<?php

include("database.php");

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}

echo "Добро пожаловать, {$_SESSION["username"]}!";



if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: index.php");
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
        $sql = "UPDATE users SET user='{$username}', password='{$hash}', phone='{$phone}', mail='{$mail}'
                    WHERE users.id={$_SESSION["id"]}";
        try {
            mysqli_query($conn, $sql);
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["phone"] = $_POST["phone"];
            $_SESSION["mail"] = $_POST["mail"];
            $_SESSION["password"] = $_POST["password"];
            header("Location: profile.php");
        } catch (mysqli_sql_exception) {
            echo "<br>Ошибка при обновлении (вы ввели существующие данные чужого пользователя)";
        }
    }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="profile.php" method="post">
        <input type="submit" value="Выйти" name="logout">
    </form>


    <h2>Редактировать поля профиля:</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        Имя:<br>
        <input type="text" name="username" value=<?php echo $_SESSION["username"] ?>>

        <br>Телефон:<br>
        <input type="text" name="phone" value=<?php echo $_SESSION["phone"] ?>>

        <br>Почта:<br>
        <input type="text" name="mail" value=<?php echo $_SESSION["mail"] ?>>

        <br><br>Пароль:<br>
        <input type="text" name="password" value=<?php echo $_SESSION["password"] ?>><br>
        Повторите пароль:<br>
        <input type="text" name="password2" value=<?php echo $_SESSION["password"] ?>><br><br>
        <input type="submit" name="submit" value="Отредактировать">
    </form>
</body>

</html>