<?php
include 'db.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$user_id = $_SESSION['user_id'];

if (isset($_POST['send_order'])) {
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];
    $payment = $_POST['payment'];
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, course_id, start_date, payment_type, status) VALUES (?, ?, ?, ?, 'Новая')");
    $stmt->execute([$user_id, $course_id, $date, $payment]);
    header("Location: history.php"); 
    exit;
}
$courses = $pdo->query("SELECT * FROM courses")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo.jpg" type="image/png">
    <title>Оставить заявку на курс</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
    <div class="logo-container"><img src="./img/logo.jpg" class="logo-img" alt="Лого"></div>
    <nav class="nav-links">
        <a href="index.php" class="nav-link-new">Новая заявка</a>
        <a href="history.php" class="nav-link-history">Мои заявки</a>
        <span>Привет, <?=$_SESSION['fio']?></span><a href="logout.php">Выход</a>
    </nav>
</header>

<div class="container">
    <div class="slider">
        <img src="./img/1.jpg" class="slide active">
        <img src="./img/2.jpg" class="slide">
        <img src="./img/3.jpg" class="slide">
        <img src="./img/4.webp" class="slide">
        <button class="slider-btn" onclick="prevSlide()"><</button>
        <button class="slider-btn next" onclick="nextSlide()">></button>
    </div>

    <h2>Формирование заявки на курс</h2>
    <form method="POST">
        <select name="course_id" required>
            <option value="">Выберите курс из списка...</option>
            <?php foreach($courses as $c): ?>
                <option value="<?=$c['id']?>"><?=$c['title']?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date" required>
        <select name="payment" required>
            <option value="Наличными">Наличными</option>
            <option value="Переводом по номеру телефона">Переводом по номеру телефона</option>
        </select>
        <button type="submit" name="send_order">Отправить заявку</button>
    </form>
</div>

<footer class="main-footer"> Портал «Корочки.есть»</footer>
<script src="./js/script.js"></script>
</body>
</html>
