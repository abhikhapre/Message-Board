$(document).ready(function(){
   $(document).on("click",".submit_like", function(e) {
       var message_id = $(this).val();
       var message_like = "like";
       console.log(message_id);
      $('#new_span_' + message_id + ', .ajax_msg_span').remove();
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{message_id: message_id,  msg_like: message_like},
          success: function(msg){
            var result = JSON.parse(msg);
            console.log(result);
            $('#dialog_box_msg_id_' + message_id).find('#msg_like_' + message_id).html(result.msg_Likes_count);
            $('#dialog_box_msg_id_' + message_id + " .message").append("<span class = 'ajax_msg_span' id = 'new_span_" +message_id + "'>" + result.msg_like + "</span>");
          }
        });
   return false;
   });
  
   $(".message_submit").on('click', function(e) {
       var messageType = $(this).val();
       var messageValue = $('#message_text').val();
       
       $.ajax({
          type: "POST",
          url: "MessageAPI.php",
          data:{messageType: messageType, messageValue: messageValue},
          success: function(msg){
            console.log(msg);
            var postResult = JSON.parse(msg);
            console.log(postResult);
            $('#blank_meesage').remove();
            if(msg === "Message Field is Blank")
              {
                $('.message_field_blank').append('<span id = "blank_meesage" style="font-weight: bold; font-size: 20px;">Message Field is Blank</span>');
              }
            else{
              var postMessage =  '<div class="container"><div id = "dialog_box_msg_id_' + postResult.MessageID + '" class="dialogbox"><div class="body"><span class="tip tip-up"></span><div class="message"><a href = "Profile.php?userid=' + postResult.UserID + '" ><img width = "100" height = "100" src = "images/' + postResult.image_name + '"></img></a><br>Name : <a href = "Profile.php?userid='+ postResult.UserID + '" ><span>'+ postResult.FirstName + " " + postResult.LastName + '</span></a><br>Message : <a href = "MessageLikes.php?messageid='+postResult.MessageID + '" ><span>'+ postResult.Message + '</span></a><br><span> Date : '+ postResult.Date + '</span><br><span> Time : '+postResult.Time + '</span><br>Likes : <a id = "msg_like_' + postResult.MessageID + '" href = "MessageLikes+php?messageid='+ postResult.MessageID + '" > <span>'+ postResult.Likes + '</span></a><br><button type = "submit" name = "messageid" class="submit_like" value = "'+ postResult.MessageID + '"> Like! </button><br></div></div></div></div>'
              console.log(postMessage);
              $('.message_board_main > :last-child').after(postMessage);
            }
          }
        });
   return false;
   });
  
});