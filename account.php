<?php
include("dbconnect.php");
if(!isset($_SESSION['username'])){
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SESSION['username']; ?> - MentorTee</title>
		<link rel="stylesheet" href="style.css">
		<script type="text/javascript" src="javascript.js"></script>
	</head>
	<body>
		<div id="mentor-mentee">
			<h2>All my <?php $mm->CheckMentororMentee($_SESSION['username']); ?></h2>
			<?php
				if(isset($_POST['remove'])){
					$mm->RemoveMentor($_POST['mentorusername'], $_SESSION['username']);
				}
			?>
			<?php 
				$mm->MyMentororMenteeList($_SESSION['username']);
			?>
			<?php $mm->AvailableMentorList($_SESSION['username']); ?>
			<?php
				if(isset($_POST['add'])){
					$mm->AddNewMentor($_POST['mentorusername'], $_SESSION['username']);
				}
			?>
			<div>
				<?php
					if(isset($_POST['logout'])){
						session_destroy();
						echo "<script>location.href = 'login.php';</script>";
					}
				?>
				<form method="POST">
					<input class="textinput submiter" type="submit" value="Log out" name="logout" />
				</form>
			</div>
		</div>
	</body>
</html>