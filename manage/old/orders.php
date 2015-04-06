<?php
	session_start();
		if ($_SESSION['login']) {
		    echo <<<AOT
<!DOCTYPE HTML>
<html>
	<head>
		<title>Manage Orders</title>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<link rel="stylesheet" href="lib/jquery-ui-1.10.4.custom.min.css" />
		<script src="lib/jquery-1.10.2.js"></script>
		<script src="lib/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="lib/jquery.validate.min.js"></script>
		<script src="lib/main.js"></script>
		<link rel="stylesheet" href="lib/main.css" />
	</head>
	<body>
AOT;
	    echo <<<AOT
			<table id="search">
			<thead>
				<th>Search</th>
			</thead>
			<tbody>
				<tr>
					<td id="spec">
					<form id="by_date" method="POST" action="">
    					<table>
    					    <tbody>
    					        <tr>
    					            <td>
    					                <label for="date">Datum</label>
        						        <input type="text" title="Date" name="date" id="date" />
        						        <br />
        						        <br />
        						        <label for="Language">Språk</label>
        						        <input type="text" title="Language" name="language" id="language" />
    					            </td>
    					            <td>
    					                <label for="c_name">Kontaktperson</label>
        						        <input type="text" title="Contact Name" name="Cname" id="c-name" />
        						        <br />
        						        <br />
                						<label for="o_num">Uppdrag Num.</label>
        						        <input type="text" title="Order Number" name="Onum" id="o_num" maxlength="6"/>
    					            </td>
    					            <td>
    					                <label for="typ">Typ</label>
                						<select title="Typ Tolkning" name="Typ" id="typ">
                							<option selected="selected"></option>
                							<option value="KT">KT</option>
                							<option value="TT">TT</option>
                						</select>
                						<br />
                						<input type="submit" value="Search" class="submit_button"/>
    					            </td>
    					        </tr>
    						</tbody>
    					</table>
					</form></td>
					<td></td>
					<td id="gen">
					<form id='all' method="POST" action="">
AOT;
                    	
			echo <<<AOT
		                <input type="hidden" value="all" id="type" />
						<input type="submit" value="View All" class="view_all" />
					</form></td>
				</tr>
			</tbody>
		</table>
		<div class="space"></div>
		<div id="flash"></div>
		<div id="show"></div>	
AOT;
		} else {
			header("Location: index.html");
		}
		?>
	</body>
</html>