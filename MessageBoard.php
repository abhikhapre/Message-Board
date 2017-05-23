<?php
   session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Message Board</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
     <script src="js/messagesClient.js"></script>
  </head>
  <body>
          <h1 class="title">Message Board</h1>
    <div>
          <a href="Profile.php?userid=<?php echo $_SESSION['userID']; ?> " class="btn btn-primary btn-lg btn-block login-button" style = "width: 20%;" >Go to Profile</a><br>
          <a href="logout.php" class="btn btn-primary btn-lg btn-block login-button" style = "width: 10%;" > Logout</a>
    </div>
</body>
</html>
<?php
    $blank_field = "";
  $host_Name = "localhost";
  $database_Name = "MessageDB";
  $database_User = "root";
  $database_Password = "abhi2756";
  $conn = new mysqli($host_Name, $database_User, $database_Password, $database_Name);
  if ($conn->connect_error) 
  {
    die("Connection failed: " . $conn->connect_error);
  }
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
        if( !empty($_POST) && $_POST['message'] != "")
        {
          $email = $_SESSION['email'];
          $sql = "SELECT UserID FROM Users WHERE email = '" .  $email . "'";
          $result = mysqli_query($conn, $sql);
          $row = $result->fetch_assoc();
          $user_id = $row['UserID'];
          $_SESSION['userID'] = $user_id;
          $date = date('Y-m-d');
          $time = date('H:i:s');
          $sql = "INSERT INTO Messages (MessageID, UserID, Message, Date, Time) VALUES ('', '" . $user_id . "', '" . $_POST['message'] . "', '" . $date . "', '" . $time . "')";
          if ($conn->query($sql) === TRUE) 
          {
            $already_exist =  "New record created successfully";
          } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
        }
        else{
          $blank_field = "<span style = 'font-weight: bold; font-size: 20px;'>Message Box</span>";
        }
       $sql = "SELECT Users.UserID, Users.FirstName, Users.LastName, Users.image_name, Messages.UserID, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                                 COUNT(UserMessageLike.UserID) AS                 Likes
              FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
              LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID 
              GROUP BY Messages.MessageID
              ORDER BY COUNT(UserMessageLike.UserID) DESC";
          $result = mysqli_query($conn, $sql);
          if ($result->num_rows > 0) 
          {
            echo "<div class = 'message_board_main'>";
            while ($row = $result->fetch_assoc()) 
            {
              echo '<form method="post"> 
                      <div class="container">  
                        <div id = "dialog_box_msg_id_' . $row['MessageID'] . '"class="dialogbox"> 
                          <div class="body"> 
                            <span class="tip tip-up"></span>
                            <div class="message">
                              <a href = "Profile.php?userid='. $row['UserID'] . '" ><img width = "100" height = "100" src = "images/'. $row['image_name']. '"></img></a><br>
                              Name : <a href = "Profile.php?userid='. $row['UserID'] . '" ><span>'. $row['FirstName'] . " " . $row['LastName'] . '</span></a><br>
                              Message : <a href = "MessageLikes.php?messageid='. $row['MessageID'] . '" ><span>'. $row['Message']. '</span></a><br>
                              <span> Date : '. $row['Date']. '</span>
                              <span> Time : '. $row['Time']. '</span><br>
                              Likes : <a id = "msg_like_' . $row['MessageID'] . '" href = "MessageLikes.php?messageid='. $row['MessageID'] . '" > <span>'. $row['Likes']. '</span></a><br>
                              <button type = "submit" name = "messageid" class = "submit_like" value = "'. $row['MessageID'] . '"> Like! </button>
                            </div>
                          </div>
                        </div>
                       </div>
                     </form>';
            }
            echo "</div>";
          }
        $conn->close();
?>
    
  </body>
</html>
<?php        
      }
    }
    
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Message Board</title>
  </head>
  <body>
     <div class="container">
     <div class="panel-heading">
        <div class="panel-title text-center">
          
       
    <form action="MessageBoard.php" method="post">
        <div class="container">
           <div class="row">
            <div class="col-md-6">
              <div class="panel panel-login">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <?php echo $blank_field; ?>
                      <div class="form-group">
                        <input type="text" name="message" id="message_text" tabindex="2" class="form-control" placeholder="Message">
                      </div>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-sm-6 col-sm-offset-3">
                            <input type="submit" class = "message_submit" name="submit" tabindex="4" class="form-control bttn bttn-login" value="Submit">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         </div>
      </div>
    </form>
