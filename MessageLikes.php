<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Message Likes</title>
  </head>
  <body>
    <h1>Message Likes Page</h1>
    <a href="Profile.php?userid=<?php echo $_SESSION['userID']; ?>" style = "width: 20%;" >Go to Profile</a>
    <a href="logout.php" style = "width: 10%;" >Logout</a>
    <br>
 	</body>
</html> 
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
        $like_exist = "";
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
            if(!empty($_POST) && $_POST['messageid'] != "")
            {
              $message_id = $_POST['messageid'];
              $sql_insert = "INSERT INTO UserMessageLike(`UserID`, `MessageID`) VALUES ('" .  $userID . "', '" . $message_id . "')";
              
              if ($conn->query($sql_insert) === TRUE) 
              {
                $already_exist =  "New record created successfully";
              } 
              else
              {
                $like_exist = "Like Already Exist...!!!";
              }
            }
            $message_id = $_GET['messageid'];
            $sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date,               Messages.Time,                                   COUNT(UserMessageLike.UserID) AS Likes
                    FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                    LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                    WHERE Messages.MessageID = $message_id
                    GROUP BY Messages.MessageID
                    ORDER BY COUNT(UserMessageLike.UserID) DESC";
              $result = mysqli_query($conn, $sql);
              if ($result->num_rows > 0) 
              {
                echo "<span style = 'margin-left: 43em; font-weight: bold;'>" . $like_exist . " </span><br>";
                while ($row = $result->fetch_assoc()) 
                {
                  echo '<form method = "post" action="">
                          <div class="container">  
                            <div class="dialogbox"> 
                              <div class="body"> 
                                <span class="tip tip-up"></span>
                                <div class="message">
                                  <a href = "Profile.php?userid='. $row['UserID'] . '" ><img width = "100" height = "100" src = "images/'. $row['image_name']. '"></img></a><br>
                                  Name : <a href = "Profile.php?userid='. $row['UserID'] . '" ><span>'. $row['FirstName'] . " " . $row['LastName'] . '</span></a><br>
                                  Message : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" ><span>'. $row['Message']. '</span></a><br>
                                  <span> Date : '. $row['Date']. '</span><br>
                                  <span> Time : '. $row['Time']. '</span><br>
                                  Likes : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" > <span>'. $row['Likes']. '</span></a><br>
                                  <button type = "submit" name = "messageid" value = "'. $row['MessageID'] . '"> Like! </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>';
                }
              }
            $conn->close();
          }
        }
    ?>

