/**
 * Created by Samuil on 09-06-2015.
 */
$(document).ready(function () {
    var modal = $('.modal.employeeMoreInfo');


    $('#moreInfoNemat').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>gsd</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoMarielle').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoRami').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoCarolina').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoHussam').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoHamed').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });

    $('#moreInfoMustafa').on('click', function() {
        var header = $("<p>Nemat Sarvari - Ladamot, VD</p>");
        var imgSrc = "images/nemat.jpg";
        var desc = $("<p>fasfasfasfasa</p>");

        //Header
        modal.find(".header").empty();
        modal.find(".header").append(header);

        //Image
        modal.find(".content").children(".image").find("img").attr("src",imgSrc);

        //Description
        modal.find(".content").children(".description").empty();
        modal.find(".content").children(".description").append(desc);
        modal.modal("show");
    });
});
