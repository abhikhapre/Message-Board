<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Logout Page</title>
	</head>
	<body>
    <?php
			session_start();
			
      session_destroy();
		?>
		<h1 class="title">Logout Page</h1>
		<form method="post" action="login.php">
			<label for="email">Logged out.</label><br>
				<a href = 'login.php'>Login</a>
		</form>
	</body>
</html>