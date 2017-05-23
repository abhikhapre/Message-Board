<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login Page</title>
	</head>
	<body>
		<?php
	$host_Name = "localhost";
  $database_Name = "MessageDB";
  $database_User = "root";
  $database_Password = "abhi2756";
  $conn = new mysqli($host_Name, $database_User, $database_Password, $database_Name);
  if ($conn->connect_error) 
  {
    die("Connection failed: " . $conn->connect_error);
  }
			session_start();
			$email = $password = $error = "";
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if (empty($_POST["email"])) 
				{
					$email = "Email is required";
				}
				
				if (empty($_POST["password"])) 
				{
					$password = "Password is required";
				}
				
				if(!empty($_POST))
				{
					$sql = "SELECT * FROM Users WHERE email = '" . $_POST["email"] . "'";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) == 1) 
					{
						$row = $result->fetch_assoc();
						$_SESSION['email'] = $_POST["email"];
						$_SESSION['userID'] = $row["UserID"];
						$_SESSION['start'] = time();
						$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
						header('location: MessageBoard.php');
					} 
					else 
						{
							$error = "User Does Not Exist<br>";
						}
					$conn->close();
				}
			}
		?>
		
		<h1 class="title">Login Page</h1>
		
		<form method="post" action="login.php">
			<span><?php echo $error; ?></span></br>
			<span><?php echo $email; ?></span></br>
				<label for="email">Your Email</label>
				<input type="text" name="email" id="email"  placeholder="Enter your Email" value = "<?php if(isset($_POST['email'])) echo $_POST['email'];?>"/><br>
				<span><?php echo $password; ?></span></br>
				<label for="password">Password</label>
				<input type="password" name="password" id="password"  placeholder="Enter your Password" value = "<?php if(isset($_POST['email'])) echo $_POST['password'];?>"/>
				<button type="submit" name = "submit" value = "submit">Login</button>
				<a href="signup.php"><button type="button" name = "signup" value = "submit">Sign Up</button></a>
		</form>
	</body>
</html>