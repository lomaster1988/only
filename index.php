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

    <h2>Страница входа</h2>
    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
        Введите e-mail или номер телефона:<br>
        <input type="text" name="login_input"><br>
        Введите пароль:<br>
        <input type="password" name="password"><br><br>

        <input type="submit" name="submit" value="Отправить">
    </form>

    <a href="reg.php">Создать новый аккаунт</a><br><br>

</body>

</html>


<?php

session_start();

if (isset($_SESSION["username"])) {
    header("Location: profile.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login_input = filter_input(INPUT_POST, "login_input", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT * FROM users WHERE phone = '{$login_input}' or mail='{$login_input}'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $row["user"];
            $_SESSION["password"] = $password;
            $_SESSION["phone"] = $row["phone"];
            $_SESSION["mail"] = $row["mail"];
            $_SESSION["id"] = $row["id"];
            header("Location: profile.php");
        } else {
            echo "Пароль неверный";
        };
    } elseif (empty($login_input) || empty($password)) {
        echo "Пожалуйста заполните оба поля";
    } else echo "Пользователь с указанными данными не найден ";
}

?>