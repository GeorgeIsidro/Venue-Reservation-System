<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<style>
body {
  font-family: Courier New, sans-serif;
  background-color: #f0f8ea;
}

.container {
  max-width: 400px;
  margin: 50px auto;
  background-color: #ffffff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.title {
  font-size: 32px;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
  color: #333333;
}

.subtitle {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
  color: #666666;
}

.input-field {
  width: 100%;
  padding: 16px;
  margin-bottom: 10px;
  border: 1px solid #cccccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.error {
  color: red;
  font-style: italic;
  margin-bottom: 10px;
}

.btn-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn {
  flex: 1;
  padding: 12px;
  background-color: #47a260;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #3c8f4f;
}

.link {
  text-align: center;
  margin-top: 20px;
  color: #666666;
  text-decoration: underline;
  cursor: pointer;
}



	</style>
</head>
<body>
	<?php 
		
		//Gets proper input format
		function formatdata($input){
			return htmlspecialchars(stripslashes(trim($input)));
		}
		
		//Connection to SQL
		$sqlconnect = mysqli_connect('localhost','root','');
		if(!$sqlconnect){
			die();
		}
		
		//Database init
		$selectDB = mysqli_select_db($sqlconnect,'Database1');
		if(!$selectDB){
			die("Database not connected." . mysqli_error());
		}
		
		// Get username-pw 
		$records = array( array("user"=> null, "pass"=> null,));	
		$recordsDB = mysqli_query($sqlconnect,"select * from Records");
		$count = 0;
		while($arr = mysqli_fetch_array($recordsDB)){
			$records[$count]["user"] = $arr['UserName'];
			$records[$count]["pass"] = $arr['Password'];
			$count++;
		}
		
		//Initializing Variables
		$username = $password = "";
		$userErr = $passErr = "";
		//Verifs
		$userVer = 0;
		$passVer = 0;
		$idNum = 0;
		
		//Error check and catch
		if($_SERVER["REQUEST_METHOD"]=="POST"){
			//UserName Check
			if(empty($_POST["username"])){
				$userErr = "Please input a Username!";
			} else {
				//Check if username is in DB1
				$username = formatdata($_POST["username"]);
				for($idNum; $idNum < $count; $idNum++){
					if($username == $records[$idNum]["user"]){
						$userVer = 1; //Found in Database
						break;
					}
				}
			}
			
			//Error message for UserName not found.
			if($userVer == 0 && !empty($_POST["username"])){
				$userErr = "Username does not exist!";
			}
			
			//PW check
			if(empty($_POST["password"])){
				$passErr = "Please input a Password!";
			} else {
				$password = formatdata($_POST["password"]);
				if($userVer == 1){
					//If Found
					if($password == $records[$idNum]["pass"]){
						$passVer = 1;
					} else {
						$passErr = "Password does not match!";
						echo "<script>alert('Password does not match!')</script>";
					}
				}
			}
		}
		
	?>

	<p class="title">Venue Reservation Login</p>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" class="input-field"><span class="error"><?php echo $userErr;?></span></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" class="input-field"><span class="error"><?php echo $passErr;?></span></td>
			</tr>
		</table>
		<input type="submit" value="Login" class="btn">
	</form>
	<br>
	<p>Not yet registered?</p>
	<form action="Act9-1Register.php" method="post">
		<input type="submit" value="Create Account" class="btn">
	</form>

	<?php
		// PHP code here
		if($userVer == 1 && $passVer == 1){
			header("Location: Act9-1Home.php");
		}
	?>

</body>
</html>