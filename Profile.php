<!DOCTYPE html>
<html>
  <head>
    <title>Profile Page</title>
  </head>
  <body>
    <h1>Profile Page</h1>
        <a href="MessageBoard.php" style = "width: 20%;" >MessageBoard</a><br>
        <a href="ProfileEdit.php" style = "width: 20%;" >Go to Profile Edit</a><br>
        <a href="logout.php" style = "width: 10%;" >Logout</a><br>
    
<?php
    
  session_start();
  $host_Name = "localhost";
  $database_Name = "MessageDB";
  $database_User = "root";
  $database_Password = "abhi2756";
  $conn = new mysqli($host_Name, $database_User, $database_Password, $database_Name);
  if ($conn->connect_error) 
  {
    die("Connection failed: " . $conn->connect_error);
  }
    
    $userID = $_GET['userid'];
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
          $sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time, COUNT(UserMessageLike.UserID)                   AS               Likes
                  FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                  LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                  WHERE Users.UserID = " . $userID . "
                  GROUP BY Messages.MessageID
                  ORDER BY COUNT(UserMessageLike.UserID) DESC";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) 
            {
              $message_count = $result->num_rows;
              echo "<br><span style = 'font-weight: bold;'> Number of Messages Created By User " . $message_count . " </span><br>";
              while ($row = $result->fetch_assoc()) 
              {
                echo '<form action="MessageLikes.php?messageid='. $row['MessageID'] . '" method="post"> 
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
                                <button type = "submit" value = "'. $row['MessageID'] . '"> Like! </button>
                              </div>
                            </div>
                          </div>
                        </div>
                     </form>';
              }
            }
            $sql_likes_by_other = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                               COUNT(UserMessageLike.UserID) AS Likes
                  FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                  LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                  WHERE UserMessageLike.UserID = " . $userID . "
                  GROUP BY Messages.MessageID
                  ORDER BY COUNT(UserMessageLike.UserID) DESC";
            $result = mysqli_query($conn, $sql_likes_by_other);
            if ($result->num_rows > 0) 
            {
              echo "<br><span style = 'font-weight: bold;'> Number of Messages Liked By Other Users " . $result->num_rows . " </span><br>";
              while ($row = $result->fetch_assoc()) 
              {
                echo '<form action="MessageLikes.php?messageid='. $row['MessageID'] . '" method="post"> 
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
                                <button type = "submit" value = "'. $row['MessageID'] . '"> Like! </button>
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
  </body>
</html>