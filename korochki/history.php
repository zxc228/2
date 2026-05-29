<?php
include 'db.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$user_id = $_SESSION['user_id'];

if (isset($_POST['send_review'])) {
    $order_id = $_POST['order_id'];
    $review_text = trim($_POST['review_text']);
    $stmt = $pdo->prepare("UPDATE orders SET review = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$review_text, $order_id, $user_id]);
    header("Location: history.php"); exit;
}

$query = "SELECT orders.*, courses.title AS course_title FROM orders JOIN courses ON orders.course_id = courses.id WHERE orders.user_id = ?";
$my_orders = $pdo->prepare($query); $my_orders->execute([$user_id]);
$user_orders = $my_orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo.jpg" type="image/png">
    <title>Мои заявки</title>
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

<div class="container history-container"> 
    <h2>Мои заявки и курсы</h2>
    <div class="table-wrapper">
        <table>
            <tr><th>Название курса</th><th>Дата начала</th><th>Статус заявки</th><th>Отзыв о курсе</th></tr>
            <?php if(empty($user_orders)): ?>
                <tr><td colspan="4" class="no-orders">Заявок пока нет.</td></tr>
            <?php endif; ?>
            <?php foreach($user_orders as $uo): ?>
            <tr>
                <td><?=$uo['course_title']?></td>
                <td><?=date('d.m.Y', strtotime($uo['start_date']))?></td>
                <td><b><?=$uo['status']?></b></td>
                <td>
                    <?php if ($uo['status'] === 'Обучение завершено'): ?>
                        <?php if (empty($uo['review'])): ?>
                            <form method="POST" class="review-form">
                                <input type="hidden" name="order_id" value="<?=$uo['id']?>">
                                <input type="text" name="review_text" placeholder="Ваш отзыв" required class="review-input">
                                <button type="submit" name="send_review" class="review-submit-btn">ОК</button>
                            </form>
                        <?php else: ?>
                            <span class="user-review"><?=htmlspecialchars($uo['review'])?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="review-locked">Доступно после завершения</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<footer class="main-footer"> Портал «Корочки.есть»</footer>
<script src="js/script.js"></script>
</body>
</html>
