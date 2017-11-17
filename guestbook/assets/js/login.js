$(document).ready(function () {
    $("#enter1").click(function () {
        var name = $("#name").val();
        var pass = $("#pass").val();
        var email = $("#email").val();
        var re = /^\w{3,}$/;
        var rename = re.test(name);
        var repass = re.test(pass);
        if (name == "" && pass == "") {
            alert("Поля пустые!!!");
        }
        else if
        (!rename || !repass) {
            alert("короткое имя или недопустимые символы");
        }
        else {
            //alert(re.test(name));
            $.ajax({
                url: "lib/action.php",
                type: "POST",
                data: {name: name, pass: pass, email: email},
                success: function (data) {
                    //alert("Вы успешно зарегистрированы!");
                    if (data) {
                        $("#results").html(data);
                        $(".loading-div").hide();
                        $("#results").show();
                    }
                }
            });
        }
    });
});

$('#checkbox1').change(function () {
    // $('#checkbox1').mousedown(function() {
    if ($(this).is(':checked')) {
        $("#emaildiv").show();
        $("#enter1").text('Зарегистрироваться');
        //this.checked = confirm("Are you sure?");
        //$(this).trigger("change");
    }
    if (!$(this).is(':checked')) {
        $("#emaildiv").hide();
        $("#enter1").text('Войти');
    }
});