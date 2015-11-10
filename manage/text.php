<!DOCTYPE html>
<html>
<head lang="en">
    <title>Control Panel</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <script type="text/javascript" src="../js/jquery.js"></script>

    </head>
<body>
    <button type="button" id="clic">click me</button>
    <table>
        <tbody>
            <tr><td></td></tr>
        </tbody>
    </table>
    <script>
        $(function(){
            $('#clic').click(function() { $('td').append($('<button type="button" id="op">Click</button>'));});
            $('table>tbody>tr>td').on('click', '#op', function(event) {
                var btn = $(this);
                console.log(btn);
                alert(btn.attr("id"));
            });
        });
    </script>
</body>
</html>