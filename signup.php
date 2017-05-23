<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up Page</title>
	</head>
	<body>
		<?php
		$already_exist = "";
		$FirstName_error = "";
		$LastName_error = "";
		$email_error = "";
		$password_error = "";
			
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
					if (!empty($_POST))
					{
						$FirstName = $_POST['firstname'];
						$LastName = $_POST['lastname'];
						$email = $_POST['email'];
						$password = $_POST['password'];
						if (empty($_POST["firstname"])) 
						{
							$FirstName_error = "First Name is required";
						}
						else 
						{
							$FirstName = $_POST["firstname"];
						}
					 if (empty($_POST["lastname"])) 
						{
							$LastName_error= "Last Name is required";
						}
						else 
						{
							$LastName = $_POST["lastname"];
						}
						if (empty($_POST["email"]) || !filter_var($email, FILTER_VALIDATE_EMAIL)) 
						{
							$email_error = "Email is required/Invalid";
						}
						else 
						{
							$email = $_POST["email"];
						}
						if (empty($_POST["password"])) 
						{
							$password_error = "Password is required";
						}
						else 
						{
							$password = $_POST["password"];
						}
						if($FirstName != "" && $LastName != "" && (filter_var($email, FILTER_VALIDATE_EMAIL)) && $password != "")
						{
							$sql = "SELECT * FROM Users WHERE email = '" . $email . "'";
							$result = mysqli_query($conn, $sql);
							if (mysqli_num_rows($result) == 1) {
								$already_exist = "Email Exist";
							}
							else
							{
								$password = password_hash($password, PASSWORD_DEFAULT);
								$sql = "INSERT INTO Users (UserID, FirstName, LastName, email, password) VALUES ('', '" . $FirstName . "', '" . $LastName . "', '" . $email. "', '" . $password . "')";
								if ($conn->query($sql) === TRUE) {
									header('location: login.php');
								} 
								else 
								{
									echo "Error: " . $sql . "<br>" . $conn->error;
								}
							}
							$conn->close();
						}
					}
				?>
					
				<h1>Sign Up Page</h1>
				<form method="post" action="">
					<span ><?php echo $already_exist; ?></span><br>
					<label for="name" class="cols-sm-2 control-label">Your First Name</label>
					<span ><?php echo $FirstName_error; ?></span>
					<input type="text" name="firstname" id="firstname"  placeholder="Ente your first Name" value = "<?php if(isset($_POST['email'])) echo $_POST['firstname'];?>" ><br>
					<label for="name">Your Last Name</label>
					<span class="error"><?php echo $LastName_error; ?></span>
					<input type="text" name="lastname" id="lastname"  placeholder="Enter your last Name" value = "<?php if(isset($_POST['email'])) echo $_POST['lastname'];?>" /><br>
					<label for="email">Your Email</label>
					<span class="error"><?php echo $email_error; ?></span>
					<input type="text" name="email" id="email"  placeholder="Enter your Email" value = "<?php if(isset($_POST['email'])) echo $_POST['email'];?>" /><br>
					<label for="password">Password</label>
					<span class="error"><?php echo $password_error; ?></span>
					<input type="password"  name="password" id="password"  placeholder="Enter your Password" value = "<?php if(isset($_POST['email'])) echo $_POST['password'];?>" /><br>
					<button type="submit" >Register</button>
				</form>
		</body>
</html>
