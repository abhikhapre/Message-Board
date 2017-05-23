<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Profile Edit Page</title>
	</head>
	<body>
    <?php
			require_once('fileupload.php');
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
			$already_exist = "";
			$userID = $_SESSION['userID'];
			if (!isset($_SESSION['userID'])) 
			{
					header('location: SessionExpire.php');
			}
			else
			{
				$now = time(); 
				if ($now > $_SESSION['expire']) 
				{
						unset($_SESSION['userID']);
          	session_destroy();
						header('location: SessionExpire.php');
				}
				else
				{
					$sql = "SELECT * FROM Users WHERE UserID = '" . $userID . "'";
					$result = mysqli_query($conn, $sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc())
						{
							$FirstName = $row['FirstName'];
							$LastName = $row['LastName'];
							$Email = $row['email'];
							$ImageName = $row['image_name'];
							$Password = $row['password'];
						}
					}
					if(!empty($_POST['update']))
					{

						if($_POST['firstname'] != "")
							$FirstName = $_POST['firstname'];
						else
							$FirstName = $FirstName;

						if($_POST['lastname'] != "")
							$LastName = $_POST['lastname'];
						else
							$LastName = $LastName;

						if($_POST['email'] != "")
							$Email = $_POST['email'];
						else
							$Email = $Email;

						if($_POST['password'] != "")
							$Password = $_POST['password'];
						else
							$Password = $Password;

						if($_FILES["fileToUpload"]["name"] != "")
							$ImageName =  $userID . "_" . $_FILES["fileToUpload"]["name"];
						
						$result = uploadImage($ImageName);
						$pos = strpos($result, "Sorry,");
						if($pos === false )
						{
							$sql = "UPDATE Users SET FirstName = '" . $FirstName . "', LastName = '" . $LastName . "', email = '" . $Email. "', password = '" . $Password . "', image_name = '" . $ImageName .  "'				 WHERE UserID = '" . $userID ."'";
							if ($conn->query($sql) === TRUE) 
							{
								$already_exist =  "Profile Updated Successfully";
							} 
							else 
							{
								echo "Error: " . $sql . "<br>" . $conn->error;
							}
						}
						else{
							$already_exist = $result;
						}
					}
					$conn->close();
				?>
				<h1>Profile Edit Page</h1>
				<a href="Profile.php?userid=<?php echo $userID; ?> ">Go To Profile</a><br>
				<span><?php echo $already_exist; ?></span><br>
				<a href="logout.php" style="float:">Logout</a><br>
				<form method="post" action="" enctype="multipart/form-data">
					<label for="name" >Your First Name</label><br>
					<input type="text" name="firstname" id="firstname"  placeholder="Enter first your Name" value = "<?php echo $FirstName; ?>" ><br>
					<label for="name" >Your Lastname Name</label><br>
					<input type="text" name="lastname" id="lastname"  placeholder="Enter lastname your Name" value = "<?php echo $LastName; ?>" /><br>
					<label for="name" >Your Email</label><br>
					<input type="text" name="email" id="email"  placeholder="Enter your Email" value = "<?php echo $Email; ?>" /><br>
					<label for="name" >Your Password</label><br>
					<input type="password" name="password" id="password"  placeholder="Enter your Password"/><br>
					<input type="file" name="fileToUpload" id="fileToUpload">
					<button type="submit" name = "update" value = "update">Update</button>
				</form>
			</body>
		</html>
	<?php
				}
		}
?>