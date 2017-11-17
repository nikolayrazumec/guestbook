<?php
clearstatcache();
session_start();
if (empty($_SESSION["name"])) {
    header('Location: /');
}
define('_CONTROL', 1);
include "lib/db.php";
clearstatcache();


class Chan extends Db
{
    public function mySqli()
    {
        $id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        $name = $_SESSION["name"];
        $db = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        $res = $db->query("SELECT `id`, `name`, `msg`, `note`, `datetime`, `filename` FROM `msgs` WHERE `id`='$id' AND `name`='$name' LIMIT 1");
        $query = $res->num_rows;
        $query1 = $res->fetch_all(MYSQLI_ASSOC);
        if (!$query) {
            header('Location: /');
        }
        $_SESSION['idchange'] = $query1[0]['id'];
        return $query1[0];
    }
}

$a = new Chan();
$arr = $a->mySqli();
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
        ?>
    </ul>
</div>

<?php
if (!empty($_SESSION["name"])) { ?>
    <div class="container">

        <form class="form-horizontal" id="formdata">
            <fieldset>
                <div class="form-group">
                    <label for="textArea" class=" control-label">Отзыв:</label>
                <textarea class="form-control" rows="3" placeholder="Ваши пожелания:" name="message"
                          id='text'><?php echo $arr['msg'] ?></textarea>
                    <input name="my_input" value="5" id="rating_simple1" type="hidden">

                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                        <div id="file1">
                            <label for="file">Приложите новое изображение:</label>
                            <input id="file" type="file" name="file" accept="image/*">
                        </div>

                        <div class="checkbox">
                            <?php if (!empty($arr['filename'])) { ?>
                                <label for="checkbox1"><input type="checkbox" name="remember" id="checkbox1">удалить
                                    текушее изображение</label>
                            <?php } ?>
                            <label for="checkbox2"><input type="checkbox" name="remember" id="checkbox2">удалить
                                все</label>
                        </div>

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
        <?php if (!empty($arr['filename'])) {
            echo '<img id="myImg" src="uploads/' . $arr['filename'] . '" class="img-rounded" alt="' . $arr['name'] . '" width="630" height="436">';
        } ?>
    </div>
    <?php
} ?>


<script>
    var delit = 0;
    var delit2 = 0;

    $('#checkbox1').change(function () {
        if ($(this).is(':checked')) {
            delit = 1;
            $("#file1").hide();
        }
        if (!$(this).is(':checked')) {
            delit = 0;
            $("#file1").show();
        }
    });

    $('#checkbox2').change(function () {
        if ($(this).is(':checked')) {
            delit2 = 1;
            $("#file1").hide();
            $("label[for='checkbox1']").hide();
        }
        if (!$(this).is(':checked')) {
            delit2 = 0;
            $("#file1").show();
            $("label[for='checkbox1']").show();
        }
    });

    $("#button").click(function () {
        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего

        var name = $("#name").attr('name');
        var text = $("#text").val();

        var note = $("#rating_simple1").val();

        var formData = new FormData();
        formData.append('tax_file', $('input[type=file]')[0].files[0]);
        formData.append("name", name);
        formData.append("text", text);
        formData.append("note", note);
        formData.append("delit", delit);
        formData.append("delit2", delit2);


        $.ajax({
            url: "lib/update.php",
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $("#results1").html(response);
                    $("#results1").show();

                    //$('#results1').delay(7000).fadeOut();
                    $("#formdata").trigger('reset');

                    var name = $("#byNC").attr('name');
                    var handler = 'lib/pages.php';
                }
            }
        });


    });

    (function (a) {
        a.fn.webwidget_rating_sex = function (p) {
            var p = p || {};
            var b = p && p.rating_star_length ? p.rating_star_length : "5";
            var c = p && p.rating_function_name ? p.rating_function_name : "";
            var e = p && p.rating_initial_value ? p.rating_initial_value : "1";
            var d = p && p.directory ? p.directory : "assets/images";
            var f = e;
            var g = a(this);
            b = parseInt(b);
            init();
            g.next("ul").children("li").hover(function () {
                $(this).parent().children("li").css('background-position', '0px 0px');
                var a = $(this).parent().children("li").index($(this));
                $(this).parent().children("li").slice(0, a + 1).css('background-position', '0px -28px')
            }, function () {
            });
            g.next("ul").children("li").click(function () {
                var a = $(this).parent().children("li").index($(this));
                f = a + 1;
                g.val(f);
                if (c != "") {
                    eval(c + "(" + g.val() + ")")
                }
            });
            g.next("ul").hover(function () {
            }, function () {
                if (f == "") {
                    $(this).children("li").slice(0, f).css('background-position', '0px 0px')
                } else {
                    $(this).children("li").css('background-position', '0px 0px');
                    $(this).children("li").slice(0, f).css('background-position', '0px -28px')
                }
            });

            function init() {
                $('<div style="clear:both;"></div>').insertAfter(g);
                g.css("float", "left");
                var a = $("<ul>");
                a.addClass("webwidget_rating_sex");
                for (var i = 1; i <= b; i++) {
                    a.append('<li style="background-image:url(' + d + '/web_widget_star.gif)"><span>' + i + '</span></li>')
                }
                a.insertAfter(g);
                if (e != "") {
                    f = e;
                    g.val(e);
                    g.next("ul").children("li").slice(0, f).css('background-position', '0px -28px')
                }
            }
        }
    })(jQuery);
    $(function () {
        $("#rating_simple1").webwidget_rating_sex({
            rating_star_length: '5',
            rating_initial_value: '<?php echo $arr['note']?>',
            rating_function_name: '',//this is function name for click
            directory: 'assets/images/'
        });
    });
</script>

</body>
</html>