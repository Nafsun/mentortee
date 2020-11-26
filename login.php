<?php
include("dbconnect.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login - MentorTee</title>
		<link rel="stylesheet" href="style.css">
		<script type="text/javascript" src="javascript.js"></script>
	</head>
	<body>
		<div id="mentor-mentee">
			<?php
			  if(isset($_POST['login'])){
				$username = $_POST['username'];
				$password = $_POST['password'];
				if(empty($username)){
					echo "<p class='message'>You forgot to enter your username</p>";
				}
				if(empty($password)){
					echo "<p class='message'>You forgot to enter your password</p>";
				}else{
					$mm->Login($username, $password);
				}
			  }
			?>
			<form method="POST">
				<p>Username<br/><input class="textinput" type="text" name="username"/></p>
				<p>Password<br/><input class="textinput" type="password" name="password"/></p>
				<input class="textinput submiter" type="submit" value="Login" name="login" />
			</form>
			<p>Don't have an account yet - <a href="index.php">Sign up</a></p>
		</div>
	</body>
</html>