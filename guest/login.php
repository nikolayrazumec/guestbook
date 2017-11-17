<?php
session_start();
if (!empty($_SESSION["name"])) {
    header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>регистрация</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
</head>
<body>

<div class="container">
    <ul class="nav nav-pills nav-stacked">
        <li><a href="/">Гостевая книга</a></li>
        <li class="active"><a href="login.php">Войти/Зарегистрироваться</a>
        </li>
    </ul>
</div>

<div class="container">
    <h2>Войти/Зарегистрироваться<?php echo $_SESSION['name']; ?></h2>
    <div class="form-group">
        <label for="name">Имя:</label>
        <input type="text" class="form-control" id="name" placeholder="имя" name="name">
    </div>
    <div class="form-group">
        <label for="pass">Пароль:</label>
        <input type="password" class="form-control" id="pass" placeholder="пароль" name="pass">
    </div>
    <div class="form-group" id="emaildiv" hidden="hidden">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="email@email.com" name="email">
    </div>
    <div class="checkbox">
        <label><input type="checkbox" name="remember" id="checkbox1">вы впервые у нас</label>
    </div>
    <button type="submit" class="btn btn-info" id="enter1" name="reg">Войти</button>
    <div class="loading-div"></div>
    <div id="results"></div>
</div>

<script src="assets/js/login.js"></script>

</body>
</html>