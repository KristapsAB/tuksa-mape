$(document).ready(function () {
    $("#data-form").submit(function (event) {
        event.preventDefault();
        var selectedData = $("#select-data").val();
        $.post("data_retrieval.php", { data: selectedData }, function (data) {
            $("#data-display").html(data);
        });
    });
});

$(document).ready(function() {
    $('.room-description').hide();

    $('.show-more-button').click(function() {
        $(this).next('.room-description').slideToggle();
    });
});
