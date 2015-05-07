<?php
if(!empty($_SESSION['organization_number']) && !empty($_SESSION['user_number']))
{
    $user_menu = true;
} else {
    $user_menu = false;
}
?>
<header id="header">
    <div id="stuck_container">
        <div class="container">
            <div class="row">
                <div class="grid_12">
                    <div class="brand put-left">
                        <h1>
                            <a href="index.php">
                                <img src="images/logo.png" alt="Logo"/>
                            </a>
                        </h1>
                    </div>
                    <nav class="nav put-right">
                        <ul class="sf-menu">
                            <li><a href="hemsida.php">Tjänster</a></li>
                            <li><a href="omoss.php">Om oss</a></li>
                            <li><a href="kvalitet.php">Kvalitetspolicy</a></li>
                            <li><a href="utbildning.php">Kurser</a></li>
                            <li><a href="kontakt.php">Kontakt</a></li>
                            <li><a class="fa fa-user" href="bokning.php"><?php echo ($user_menu) ? ("Min Panel"):("Online bokning"); ?> </a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <script>
        var i = document.location.href.lastIndexOf("/");
        var current = document.location.href.substr(i+1);

        var currentLink = $(".sf-menu li a[href^='"+current+"']");

        console.log(currentLink);
        console.log(currentLink.length);
        if (currentLink.length > 0) {
            $(".sf-menu li").removeClass('current');
            currentLink.each(function(){
                $(this).parents("li").addClass('current');
            });
        } else {
            var ref = document.referrer.substr(i+1);
            $(".sf-menu li a[href^='"+ref+"']").each(
                function() {
                    $(this).parents("li").addClass('current');
                }
            );
        }


    </script>
</header>