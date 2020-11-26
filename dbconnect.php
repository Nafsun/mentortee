<?php
session_start();
?>
<?php
class PDOConnection{
	private $hostdb = "mysql:host=localhost;dbname=textailn_mentortee"; //dbname=textailn_mentortee dbname=mentormentee
	private $username = "textailn_teemen"; //root textailn_teemen
	private $password = "mentortee12345678"; //mentortee12345678 
	
	public function dbconnection(){
		try{
			$connection = new PDO($this->hostdb, $this->username, $this->password); //, $this->password
			return $connection;
		}catch (PDOException $e){
			echo "Connection Error:" . $e->getMessage() . "";
		}
	}
}

class MentorMentee extends PDOConnection{
	public function Register($fullname, $username, $password, $mentorormentee){
		//Checking if a username already exist
		$checkforusername = $this->dbconnection()->query("SELECT username FROM signup");
		if($checkforusername->fetchColumn() === $username){
			echo "<p id='addedorremoved' class='message'>There is already someone with that username, choose another username</p>";
		}else{
			//Adding a mentor or mentee to a signup table
			$register = $this->dbconnection()->prepare("INSERT INTO signup (fullname, username, mentorormentee) VALUES (?, ?, ?)");
			$register->execute([$fullname, $username, $mentorormentee]);
			$login = $this->dbconnection()->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
			$login->execute([$username, $password]);
			$this->dbconnection()->query("CREATE TABLE {$username}_info (id int(11) AUTO_INCREMENT, username varchar(100), PRIMARY KEY(id));");
			echo "<p class='message'>Thank you for signing up as a " . $mentorormentee . ", click login below</p>";
		}
	}
	public function Login($username, $password){
		//Checking if your username matches your password
		$login = $this->dbconnection()->prepare("SELECT password FROM login WHERE username = ?");
		$login->execute([$username]);
		if($login->fetchColumn() === $password){
			$_SESSION['username'] = $username;
			echo "<script>location.href = 'account.php';</script>";
		}else{
			echo "<p id='addedorremoved' class='message'>Username or Password incorrect</p>";
		}
	}
	public function MyMentororMenteeList($username){
		//Geting all the list of your mentors or mentees
		$mylist = $this->dbconnection()->query("SELECT id, username FROM {$username}_info ORDER BY id DESC");
		if($mylist->rowCount() == 0){
			//Checking if you are a mentor or a mentee
			$checkme = $this->dbconnection()->prepare("SELECT mentorormentee FROM signup WHERE username = ?");
			$checkme->execute([$username]);
			while($row = $checkme->fetch()){
				if($row['mentorormentee'] == 'mentee'){
					echo "<p class='message3'>No mentor added yet</p>";
				}else{
					echo "<p class='message3'>No mentee have added you as his mentor yet</p>";
				}
			}
		}
		echo "<table align='center'>";
		while($row = $mylist->fetch()){
			echo "<tr>";
			echo "<form method='POST'>
						<td><span>{$row['username']}</span></td>
						<td><input type='hidden' name='mentorusername' value='{$row['username']}' />
						<input type='submit' value='Remove' name='remove' /></td>
					</form>";
			echo "</tr>";
		}
		echo "</table>";
	}
	public function CheckMentororMentee($username){
		//Checking if you are a mentor or a mentee
		$my = $this->dbconnection()->prepare("SELECT mentorormentee FROM signup WHERE username = ?");
		$my->execute([$username]);
		while($row = $my->fetch()){
			if($row['mentorormentee'] == 'mentor'){
				echo "<span>mentees</span>";
			}else{
				echo "<span>mentors</span>";
			}
		}
	}
	public function AvailableMentorList($username){
		//Checking if you are a mentor or a mentee
		$checkme = $this->dbconnection()->prepare("SELECT mentorormentee FROM signup WHERE username = ?");
		$checkme->execute([$username]);
		while($row = $checkme->fetch()){
			if($row['mentorormentee'] == 'mentee'){
				echo "<p class='message2'>All Available Mentors</p>";
				if($checkme->rowCount() == 0){
					echo "<p class='message3'>No mentor is currently available on the platform</p>";
				}
				//Getting all your existing mentors
				$already_added_mentor = $this->dbconnection()->prepare("SELECT id, username FROM {$username}_info");
				$already_added_mentor->execute([$username]);
				$new_array_of_already_added_mentor = array();
				
				//Adding all your existing mentors to an array
				while($row = $already_added_mentor->fetch()){
					array_push($new_array_of_already_added_mentor, $row['username']);
				}
				
				$available = $this->dbconnection()->query("SELECT id, username FROM signup WHERE mentorormentee = 'mentor' ORDER BY id DESC");
				
				echo "<table align='center'>";
				while($row = $available->fetch()){
					//Checking if an available mentor is already your mentor and only displaying those that are not your mentors yet
					if(in_array($row['username'], $new_array_of_already_added_mentor) == false){
						echo "<tr>";
						echo "<form method='POST'>
								<td><span>{$row['username']}</span></td>
								<td><input type='hidden' name='mentorusername' value='{$row['username']}' />
								<input type='submit' value='Add' name='add' /></td>
							</form>";
						echo "</tr>";
					}
				}
				echo "</table>";
			}
		}
	}
	public function AddNewMentor($mentorusername, $username){
		//Storing a mentee to a specific mentor table
		$add_mentor = $this->dbconnection()->prepare("INSERT INTO {$username}_info (username) VALUES (?)");
		$add_mentor->execute([$mentorusername]);
		//Storing a mentor to a specific mentee table
		$add_mentee = $this->dbconnection()->prepare("INSERT INTO {$mentorusername}_info (username) VALUES (?)");
		$add_mentee->execute([$username]);
		echo "<script>location.href = 'account.php';</script>";
	}
	public function RemoveMentor($mentorusername, $username){
		//Deleting a mentor from a specific mentee table
		$this->dbconnection()->query("DELETE FROM {$username}_info WHERE username = {$this->dbconnection()->quote($mentorusername)}");
		//Deleting a mentee from a specific mentor table
		$this->dbconnection()->query("DELETE FROM {$mentorusername}_info WHERE username = {$this->dbconnection()->quote($username)}");
		//Checking if you are a mentor or a mentee
		$checkme = $this->dbconnection()->prepare("SELECT mentorormentee FROM signup WHERE username = ?");
		$checkme->execute([$username]);
		while($row = $checkme->fetch()){
			if($row['mentorormentee'] == 'mentee'){
				echo "<p class='message' id='addedorremoved'>Successfully disqualify " . $mentorusername . " from being your mentor</p>";
			}else{
				echo "<p class='message' id='addedorremoved'>Successfully disqualify " . $mentorusername . " from being your mentee</p>";
			}
		}
	}
}
$mm = new MentorMentee();
?>