<?php
include 'db.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) { header("Location: login.php"); exit; }
$show_toast = false;

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    $_SESSION['toast_success'] = true;
    header("Location: admin.php"); exit;
}
if (isset($_SESSION['toast_success'])) { $show_toast = true; unset($_SESSION['toast_success']); }

$query = "SELECT orders.*, users.fio, courses.title AS course_title FROM orders JOIN users ON orders.user_id = users.id JOIN courses ON orders.course_id = courses.id";
$orders = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo.jpg" type="image/png">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
    <div class="logo-container"><img src="./img/logo.jpg" class="logo-img" alt="Лого"></div>
    <nav class="nav-links"><span>Привет, Администратор</span> <a href="logout.php">Выход</a></nav>
</header>

<?php if ($show_toast): ?>
    <div id="toast" class="toast-msg">Статус заявки успешно изменен!</div>
<?php endif; ?>

<div class="container admin-container"> 
    <h2>Все заявки и отзывы пользователей</h2>
    <input type="text" id="adminSearch" onkeyup="filterTable()" placeholder="Быстрый поиск по ФИО студента или названию курса...">

    <div class="table-wrapper">
        <table id="ordersTable">
            <tr class="table-header-row"><th>ФИО</th><th>Курс</th><th>Дата</th><th>Оплата</th><th>Статус</th><th>Отзыв студента</th><th>Действие</th></tr>
            <?php foreach($orders as $o): ?>
            <tr class="order-row">
                <td class="search-fio"><?=$o['fio']?></td>
                <td class="search-course"><?=$o['course_title']?></td>
                <td><?=date('d.m.Y', strtotime($o['start_date']))?></td>
                <td><?=$o['payment_type']?></td>
                <td><b><?=$o['status']?></b></td>
                <td>
                    <?php if(!empty($o['review'])): ?>
                        <span class="user-review"><?=htmlspecialchars($o['review'])?></span>
                    <?php else: ?>
                        <span class="no-review">Нет отзыва</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" class="admin-action-form">
                        <input type="hidden" name="order_id" value="<?=$o['id']?>">
                        <select name="status" class="admin-select">
                            <option value="Идет обучение" <?=$o['status']=='Идет обучение'?'selected':''?>>Идет обучение</option>
                            <option value="Обучение завершено" <?=$o['status']=='Обучение завершено'?'selected':''?>>Обучение завершено</option>
                        </select>
                        <button type="submit" name="update_status" class="admin-submit-btn">Сохранить</button>
                    </form>
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
