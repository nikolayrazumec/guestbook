$(document).ready(function () {
    //var name = $("#byNC").attr('name');
    var name = $("#name").attr('name');
    var handler = 'lib/pages.php';

    $.ajax({
        url: handler,
        type: "post",
        datetype: "text",
        cache: false,
        data: {"name": name},
        success: function (response) {
            if (response) {
                $("#results").html(response);
                $(".loading-div").hide();
            }
        }
    });


    $("#results").on("click", ".pagination a", function (e) {
        e.preventDefault();
        $("#results").hide();
        $(".loading-div").show();

        var page = $(this).attr("data-page");
        $.ajax({
            url: handler,
            type: "post",
            datetype: "text",
            cache: false,
            data: {"name": name, "page": page},
            success: function (response) {
                if (response) {
                    $("#results").html(response);
                    $(".loading-div").hide();
                    $("#results").show();

                }
            }
        });
    });
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


    $.ajax({
        url: "lib/submit.php",
        type: 'POST',
        data: formData,
        cache: false,
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false,
        success: function (response) {
            if (response) {
                $("#results1").html(response);
                $("#results1").show();

                $('#results1').delay(7000).fadeOut();
                $("#formdata").trigger('reset');

                var name = $("#byNC").attr('name');
                var handler = 'lib/pages.php';

                $("#results").hide();
                $(".loading-div").show();

                var page = $(this).attr("data-page");
                $.ajax({
                    url: handler,
                    type: "post",
                    datetype: "text",
                    cache: false,
                    data: {"name": name, "page": page},
                    success: function (response) {
                        if (response) {
                            $("#results").html(response);
                            $(".loading-div").hide();
                            $("#results").show();

                        }
                    }
                });

                //$('#results a')[0].click();
            }
        }
    });




});



