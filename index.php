<?php
include("dbconnect.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mentor & Mentee</title>
		<link rel="stylesheet" href="style.css">
		<script type="text/javascript" src="javascript.js"></script>
		<?php include("head.php"); ?>
	</head>
	<body>
		<div id="mentor-mentee">
			<h1>MentorTee</h1>
			<?php
			  if(isset($_POST['submit'])){
				$fullname = $_POST['fullname'];
				$username = strtolower($_POST['username']);
				$password = $_POST['password'];
				if(empty($fullname)){
					echo "<p class='message'>You forgot to enter your fullname</p>";
				}elseif(empty($username)){
					echo "<p class='message'>You forgot to enter a username</p>";
				}elseif(empty($password)){
					echo "<p class='message'>You forgot to enter a password</p>";
				}elseif(empty($_POST['choosementorormentee'])) {
					echo "<p class='message'>Please choose between mentor and mentee.</p>";
				}else{
					$mm->Register($fullname, $username, $password, $_POST['choosementorormentee']);
				}
			  }
			?>
			<form method="POST">
				<p>Fullname<br/><input class="textinput" type="text" name="fullname"/></p>
				<p>Username<br/><input class="textinput" type="text" name="username"/></p>
				<p>Password<br/><input class="textinput" type="password" name="password"/></p>
				<p>Mentor or Mentee<br/> 
					<select class="textinput" name="choosementorormentee">
						<option value="" disabled selected>Choose</option>
						<option value="mentor">Mentor</option>
						<option value="mentee">Mentee</option>
					</select>
				</p>
				<input class="textinput submiter" type="submit" value="Sign Up" name="submit" />
			</form>
			<p>If you already have an account - <a href="login.php">login</a></p>
		</div>
	</body>
</html>