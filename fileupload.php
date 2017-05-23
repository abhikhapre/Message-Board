<?php
function uploadImage($ImageName)
{
  if($_FILES['fileToUpload']['name'] != "") 
  {
    $target_dir = "images/";
    $imageFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
    $target_file = $target_dir . $ImageName;
    $uploadOk = 1;
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $error =  "Sorry File is not an image.";
            $uploadOk = 0;
            return $error;
        }
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $error =  "Sorry, your file is too large.";
        $uploadOk = 0;
        return $error;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        return $error;
    }
    if ($uploadOk == 0) {
        $error = "Sorry, your file was not uploaded.";
        return $error;
    } 
    else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $success = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            return $success;
          } else {
              $error = "Sorry, there was an error uploading your file.";
              return $error;
        }
      }
    }
  }
?>