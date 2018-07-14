<?php 
/*essential functions*/


function redirect($location){
	header("LOCATION:$location");
}

function query($sql)
{
	global $connection;
	return mysqli_query($connection, $sql);
}

function confirm($reasult)
{
	global $connection;
	if(!$reasult)
	{
		echo "Connection failed".mysqli_error($connection);
	}
}

function escape_string($string){
global $connection;
return mysqli_real_escape_string($connection, $string);

}

function fetch_array($reasult)
{
 global $connection;
 return mysqli_fetch_array($reasult);
}
function get_bets(){
	$query=query("SELECT * FROM events");
	confirm($query);
	while ($row=fetch_array($query)) {
		# print out all the events to bet on
		$deli=<<<_delimeter
		<div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">{$row['event_name']}</h4>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mt-3 mb-4">
              <li>{$row['event_description']}</li>
              
            </ul>
            <a href="set_bet.php?id={$row['event_id']}"><button type="button"  class="btn btn-lg btn-block btn-outline-primary">Place a Bet</button></a>
          </div>
        </div>
_delimeter;
        echo $deli;
	}
}

function sign_up(){
	if (isset($_POST['submit']))
	{
		
		$username=escape_string($_POST['inputName']);
		$email=escape_string($_POST['inputEmail']);
		$password=escape_string($_POST['inputPassword']);
		$number=escape_string($_POST['inputNumber']);
		if (unique($number,$email)) {
			# checks if the email or password has been inserted into the table already
			
			redirect("sign-up.php");
			echo "the email or number is already taken";
		}
		else{
		$query=query("INSERT INTO `users` (`user_name`, `user_number`, `user_email`, `user_password`, `user_bets`, `user_balance`) VALUES ('$username', '$number', '$email', '$password', '', '')");
		//$query=query("INSERT INTO 'users'  VALUES('$username', '$number', '$email', '$password', '', '')");
			confirm($query);
			if (confirm($query)=="") {
				redirect("index.php");
				# code...
			}
		}
	}
}

    function unique($number,$email){

	$query=query("SELECT * FROM `users` WHERE `user_number` = $number OR `user_email` LIKE '$email' ");
	if (mysqli_num_rows($query)!=0) {
		echo "in unique";
		return true;

	}
	
}

function previous_bets()
{
	$deli=<<<_delimeter
	 <div>
                <h6 class="my-0">Product name</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$12</span>
_delimeter;
echo "$deli";
}


function login(){
	if (isset($_POST['submit'])) {
		# checks if the credentials match to the database
		//redirect("index.php");
		$number=escape_string($_POST['number']);
		$password=escape_string($_POST['password']);
		
		$query=query("SELECT * FROM `users` WHERE `user_number` = $number AND `user_password` LIKE '$password' ");
		confirm($query);
		//echo "$query";
		$row=fetch_array($query);
		if (confirm($query)=="") {
			# if there are no errors it sets the session variable
			
			//echo $row['user_name'];
			$_SESSION['username']=$row['user_name'];
			$_SESSION['number']=$row['user_number'];
			$_SESSION['password']=$row['user_password'];
			echo $_SESSION['username'];
			redirect("index.php");
		}
	}
}

function is_signed_in(){
	if(!isset($_SESSION['username'])){
		$deli=<<<_delimeter
		<a class="btn btn-outline-primary" href="sign-in.php">Sign in </a>
       <a class="btn btn-outline-primary" href="sign-up.php">Sign up</a>
       <a class="btn btn-outline-primary" href="admin.php">Admin </a>
_delimeter;
       echo $deli;
   }
	
	else {
		
		$deli=<<<_delimeter
		
		<a class="btn btn-outline-primary" href="">Hello {$_SESSION['username']}</a>
		<a class="btn btn-outline-primary" href="logout.php">Logout</a>
		<a class="btn btn-outline-primary" href="admin.php">Admin </a>
		
		
_delimeter;
		echo $deli;
		}
}

function logout(){
	unset($_SESSION['username']);
 unset($_SESSION['password']);
 unset($_SESSION['number']);
 session_destroy();
 redirect('index.php');

}

function bet_options($id){

	
	$query=query("SELECT * FROM `outcomes` WHERE `event_id` = $id");
	confirm($query);
	$row=fetch_array($query);
	echo $row['event_outcome_1'];
	$deli=<<<_delimeter
	<option value="event_outcome_1_total">{$row['event_outcome_1']}</option>
	<option value="event_outcome_2_total">{$row['event_outcome_2']}</option>
	<option value="event_outcome_3_total">{$row['event_outcome_3']}</option>
_delimeter;
	echo $deli;

}

function place_bet(){
	if (isset($_POST['submit'])) {
		
		# checks is the place a bet has been pressed
		#and subsequently checks if the user is logged in 
		if(!isset($_SESSION['username'])){
			redirect('sign-in.php');
		}
		else{
			
			$number=$_SESSION['number'];
			$password=$_SESSION['password'];

			$query=query("SELECT * FROM `users` WHERE `user_number` = $number AND `user_password` LIKE '$password' ");
			confirm($query);
			$row=fetch_array($query);
			$temp=$row['user_bets'];
			$temp=$temp*10;
			$temp=$temp+$_GET['id'];
			$balance=$row['user_balance'];
			$balance=$balance-$_POST['amt'];
			$query=query("UPDATE `users`SET `user_bets`=$temp, `user_balance`= $balance WHERE `user_number` = $number AND `user_password` LIKE '$password'  ");
			confirm($query);
			$query=query("SELECT * FROM `outcomes` WHERE `event_id` = {$_GET['id']} ");
			confirm($query);
			$row=fetch_array($query);
			$value=$_POST['option'];
			$tot;
			if ($value==='event_outcome_1_total') {
				# if it's the first outcome
				$tot=$row['event_outcome_1_total']+$_POST['amt'];
				$query=query("UPDATE `outcomes` SET `event_outcome_1_total` = '{$tot}' WHERE `outcomes`.`event_id` = {$_GET['id']}");
				confirm($query);
			}
			else if ($value==='event_outcome_2_total') {
				# if the second outcome has been chosen
			    $tot=$row['event_outcome_2_total']+$_POST['amt'];
			    $query=query("UPDATE `outcomes` SET `event_outcome_2_total` = '{$tot}' WHERE `outcomes`.`event_id` = {$_GET['id']}");
				confirm($query);

			}
			else{
				$tot=$row['event_outcome_3_total']+$_POST['amt'];
				$query=query("UPDATE `outcomes` SET `event_outcome_3_total` = '{$tot}' WHERE `outcomes`.`event_id` = {$_GET['id']}");
				confirm($query);
			}
			//$total=row['$value'];
			//echo $value;
			//$row['user_bets']*10+$_GET['id'];
		}
	}
}


function admin_users_row(){
	$query=query("SELECT * FROM `users` ");
	confirm($query);
	while ($row=fetch_array($query)) {
		# this will print out the enter uder table
		$deli=<<<_delimeter
		<tr>
                  <td>{$row['user_name']}</td>
                  <td>{$row['user_number']}</td>
                  <td>{$row['user_email']}</td>
                  <td>{$row['user_password']}</td>
                  <td>{$row['user_bets']}</td>
                  <td>{$row['user_balance']}</td>
                </tr>
_delimeter;
                echo $deli;
	}
}

function admin_events_row(){
	$query=query("SELECT * FROM `events` ");
	confirm($query);
	while ($row=fetch_array($query)) {
		# this will print out the enter uder table
		$deli=<<<_delimeter
		<tr>
                  <td>{$row['event_id']}</td>
                  <td>{$row['event_name']}</td>
                  <td>{$row['event_description']}</td>
                  <td>{$row['event_image']}</td>
                </tr>
_delimeter;
                echo $deli;
	}
}

function amount_distribution($id_event,$win_opt){
	//if (isset($_POST('submit')) {
		// distribute the jackpot among the winners

	//}
}


?>
