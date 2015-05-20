/**
 * Created by Samuil on 10-05-2015.
 */
$(document).ready(function () {
    var container = $('#newsContainer');
    var item = $("#" + $("#newsID").text());
    var offset = item.offset().top - container.offset().top;
    setTimeout(function () {
        container.animate({scrollTop: offset}, 1000);
    }, 500);
});
