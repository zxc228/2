<?php
include 'db.php';
$error = '';
if (isset($_POST['login_btn'])) {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    if ($login === 'Admin' && $password === 'KorokNET') {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fio'] = $user['fio'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo.jpg">
    <title>Авторизация</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <h2>Авторизация</h2>
        <?php if ($error): ?> <div class="error"><?= $error ?></div> <?php endif; ?>
        <form method="POST">
            <input type="text" name="login" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" name="login_btn">Войти</button>
        </form>
        <a href="register.php">Еще не зарегистрированы? Регистрация</a>
    </div>
</body>
</html>
