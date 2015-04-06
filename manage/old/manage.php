<?php
		session_start();
		if(isset($_SESSION['order'])){
			$order = $_SESSION['order'];
		?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Manage Orders</title>
		<link rel="stylesheet" href="lib/jquery-ui-1.10.4.custom.min.css">
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<script src="lib/jquery-1.10.2.js"></script>
		<script src="lib/jquery-ui-1.10.4.custom.min.js"></script>
		<link rel="stylesheet" href="lib/manage.css">
	</head>
	<body>
		<div id='head'>
			<?php echo "<p>Order " . $order['order_id'] . "</p>";?>
		</div>
		<table id="hole">
			<tbody class="row">
				<tr>
					<td>
					<table class='order'>
						<thead>
							<tr>
								<th>Contact Person</th>
							</tr>
						</thead>
						<tbody class="row">
							<tr>
								<?php
								echo "<td><label class='titles'>Namn: </label>" . $order['contact_name'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Epost: </label> " . $order['contact_email'] . "</td>";
								?>
							</tr>
							<?php
							echo "<td><label class='titles'>Telephone: </label>" . $order['contact_telephone'] . "</td>";
							?>
							<tr>

							</tr>
						</tbody>
					</table></td>
					<td>
					<table class='order'>
						<thead>
							<tr>
								<th>Location</th>
						</thead>
						<tbody class="row">
							<tr>
								<?php
								echo "<td><label class='titles'>Adress: </label>" . $order['contact_address'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Post Number: </label>" . $order['contact_post_number'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>City: </label>" . $order['contact_city'] . "</td>";
								?>
							</tr>
						</tbody>
					</table></td>
					<td>
					<table class='order'>
						<thead>
							<tr>
								<th>Order</th>
							</tr>
						</thead>
						<tbody class="row">
							<tr>
								<?php
								echo "<td><label class='titles'>Typ: </label>" . $order['type'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Sprak: </label>" . $order['language'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Datum: </label>" . $order['date'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Starttid: </label>" . $order['start_time'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Sluttid: </label>" . $order['end_time'] . "</td>";
								?>
							</tr>
						</tbody>
					</table></td>
					<td>
					<table class='order'>
						<thead>
							<tr>
								<th>Company/Org</th>
							</tr>
						</thead>
						<tbody class="row">
							<tr class="row">
								<?php
								echo "<td><label class='titles'>Namn: </label>" . $order['org_owner'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Org. Namn: </label>" . $order['org_name'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Adress: </label>" . $order['org_address'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>Post nummer: </label>" . $order['org_post_number'] . "</td>";
								?>
							</tr>
							<tr>
								<?php
								echo "<td><label class='titles'>City: </label>" . $order['org_city'] . "</td>";
								?>
							</tr>
						</tbody>
					</table></td>
					<td>
					<table class='order'>
						<thead>
							<tr id='comment'>
								<th> Comment</th>
							</tr>
						</thead>
						<tbody style="width: 100%;">
							<tr class="row">
								<?php
								echo "<td>" . $order['comment'] . "</td>";
								}else {
                            	    header('Location: http://tolktjanst.se/manage/orders.php');
                                    exit ;
                            	}
								?>
							</tr>
						</tbody>
					</table></td>
				</tr>
			</tbody>
		</table>
<?php
    
	if ($order['state'] == "X") {
		echo '<div id="assign">
					<form action="assign-tolk.php" method="post" id="a-form" accept-charset="utf8">
						<h3>Assign a Tolk</h3>
						<fieldset>
							<label for="tolk-name">Namn:</label>
							<br />
							<input type="text" name="tolk-name" maxlength="44" required/>
							<br />
							<label for="tolk-email">Epost:</label>
							<br />
							<input type="email" name="tolk-email" maxlength="44" required/>
							<br />
							<label for="tolk-telephone">Telephone:</label>
							<br />
							<input type="tel" name="tolk-telephone" maxlength="44" required/>
							<br />
							<label for="tolk-city">City:</label>
							<br />
							<input type="text" name="tolk-city" maxlength="44" required/>
							<input type="hidden" name="customer-email" value="'.$order['contact_email'].'" />
							<input type="hidden" name="order-id" value="'.$order['order_num'].'" />
							<br />
							<input type="submit" value="Assign" id="btn-assign" />
						</fieldset>
					</form>
			</div>';
		
	} elseif($order['state'] == "O") {
		echo "<div id='tolk'>
			<legend>TOLK</legend>
			<table class='order'>
				<thead>
				</thead>
				<tbody>
					<tr>
						<td>
							<label>Namn:</label>
							<p>". $order['tolk-name']."</p>
						</td>
						<td>
							<label>Epost:</label>
							<p>".$order['tolk-email']."</p>
						</td>
					</tr>
					<tr>
						<td>
							<label>Telephone:</label>
							<p>".$order['tolk-telephone']."</p>
						</td>
						<td>
							<label>City:</label>
							<p>".$order['tolk-city']."</p>
						</td>
					</tr>
				</tbody>
		</div>";
		
	} 
	unset($_SESSION['order']);
?>
	</body>
</html>