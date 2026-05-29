<?php
include 'db.php';
$error = '';
if (isset($_POST['signup'])) {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $fio = trim($_POST['fio']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetch()) {
        $error = "Логин уже занят!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (login, password, fio, phone, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$login, $hashed, $fio, $phone, $email]);
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo.jpg" type="image/png">
    <title>Регистрация</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h2>Регистрация</h2>
        <?php if ($error): ?> <div class="error"><?= $error ?></div> <?php endif; ?>
        <form method="POST">
            <input type="text" name="login" placeholder="Логин (от 6 символов)" pattern="^[a-zA-Z0-9]{6,}$" required>
            <input type="password" name="password" placeholder="Пароль (от 8 символов)" minlength="8" required>
            <input type="text" name="fio" placeholder="ФИО" pattern="^[А-Яа-яЁё\s]+$" required>
            <input type="text" name="phone" placeholder="8(XXX)XXX-XX-XX" pattern="8\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <button type="submit" name="signup">Создать пользователя</button>
        </form>
        <a href="login.php">Уже зарегистрированы? Авторизация</a>
    </div>
</body>

</html>