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
        $user_ID = $_SESSION['userID']; 
        $return = array();
        $return_msg_del = array();
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
						if(isset($_POST['message_id']) && $_POST['message_id'] != "")
            {
               $message_id = $_POST['message_id'];
               if($_POST['msg_like'] === "like")
               {
                  $sql_insert = "INSERT INTO UserMessageLike(`UserID`, `MessageID`) VALUES ('" .  $user_ID . "', '" . $message_id . "')";
								 	if (mysqli_query($conn, $sql_insert))
                  {
                    $msgLike = "Message Like!";
                    $return["msg_like"] = $msgLike;
										 
										$sql = "SELECT Users.FirstName, Users.LastName, Messages.UserID, Users.image_name, Messages.MessageID, Messages.Message, Messages.Date,               Messages.Time,                                   COUNT(UserMessageLike.UserID) AS Likes
														FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
														LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
														WHERE Messages.MessageID = $message_id
														GROUP BY Messages.MessageID
														ORDER BY COUNT(UserMessageLike.UserID) DESC";
										$result = mysqli_query($conn, $sql);
										if ($result->num_rows > 0) 
										{
											while ($row = $result->fetch_assoc()) 
											{
												 $return["msg_Likes_count"] = $row['Likes'];
											}
										}
                  } 
                  else
                  {
                    $msgLikeExist =  "Message Already Liked!";
                    $return["msg_like"] = $msgLikeExist;
                  }
                }
               
             }
						if(isset($_POST['messageType']) && $_POST['messageType'] === "Submit")
            {
              if($_POST['messageValue'] != "")
              {
                $email = $_SESSION['email'];
                $sql = "SELECT UserID FROM Users WHERE email = '" .  $email . "'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                $user_id = $row['UserID'];
                $_SESSION['userID'] = $user_id;
                $date = date('Y-m-d');
                $time = date('H:i:s');
                $sql = "INSERT INTO Messages (MessageID, UserID, Message, Date, Time) VALUES ('', '" . $user_id . "', '" . $_POST['messageValue'] . "', '" . $date . "', '" . $time . "')";
								if (!mysqli_query($conn, $sql)) 
                {
                   echo "Error: " . $sql . "<br>" . $conn->error;
                } 
               
                $sql = "SELECT Users.UserID, Users.FirstName, Users.LastName, Users.image_name, Messages.UserID, Messages.MessageID, Messages.Message, Messages.Date, Messages.Time,                                                                                  COUNT(UserMessageLike.UserID) AS                 Likes
                    FROM Users INNER JOIN Messages ON Users.UserID = Messages.UserID
                    LEFT JOIN UserMessageLike ON Messages.MessageID = UserMessageLike.MessageID
                    GROUP BY Messages.MessageID
                    DESC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0)
                {
                  while ($row = $result->fetch_assoc()) 
                  {
                    $return["MessageID"] = $row["MessageID"];
                    $return["image_name"] = $row["image_name"];
                    $return["UserID"] = $row["UserID"];
                    $return["FirstName"] = $row["FirstName"];
                    $return["LastName"] = $row["LastName"];
                    $return["Message"] = $row["Message"];
                    $return["Date"] = $row["Date"];
                    $return["Time"] = $row["Time"];
                    $return["Likes"] = $row["Likes"];
                  }
                }
              }
             
            }
						$conn->close();
            echo json_encode($return);exit;
            
          }
        }
    ?>

	