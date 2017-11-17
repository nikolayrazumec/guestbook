<?php
clearstatcache();
defined('_CONTROL') or die;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>GUEstBook</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/guest.css" type="text/css"/>
    <link rel="stylesheet" href="assets/css/star.css" type="text/css"/>
    <link rel="stylesheet" href="assets/css/modal.css" type="text/css"/>
    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
</head>
<body>

<div class="container">
    <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="/">Гостевая книга</a></li>
        <?php
        if (!empty($_SESSION["name"])) {
            echo '<li><a href="/lib/exit.php">' . $_SESSION["name"] . '/выйти</a></li>';
        } else {
            echo '<li><a href="login.php">Войти/Зарегистрироваться</a></li>';
        }
        if (!empty($_SESSION["admin"])) {
            echo '<li><a href="lib/getfile.php/" target="_blank">скачать все сообщения</a></li>';
        }
        ?>

    </ul>
</div>

<p hidden="true" id="name" name="<?php echo $_SESSION["name"]; ?>"></p>
<div class="loading-div"><img src="assets/preloader.gif"></div>
<div id="results"></div>

<?php
if (!empty($_SESSION["name"])) { ?>
    <div class="container">

        <form class="form-horizontal" id="formdata">
            <fieldset>
                <div class="form-group">
                    <label for="textArea" class=" control-label">Отзыв:</label>
                <textarea class="form-control" rows="3" placeholder="Ваши пожелания:" name="message"
                          id='text'></textarea>
                    <input name="my_input" value="5" id="rating_simple1" type="hidden">

                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                        <label for="file">Приложите свое изображение:</label>
                        <input id="file" type="file" name="file" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                        <button type="reset" class="btn btn-default">Отмена</button>
                        <input id="button" class="btn btn-primary submit" type="submit" value="Отправить" name="submit">
                        <div id="results1">
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <?php
} ?>

<script src="assets/js/star.js"></script>
<script src="assets/js/guest.js"></script>
<script src="assets/js/modal.js"></script>

</body>
</html>