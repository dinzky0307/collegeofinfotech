<?php    

include '../database.php';

function getMessages()   {
    $userId = $_SESSION['user_id'];

    $selects = 'messages.id, userdata.fname, userdata.lname, messages.content, messages.created_at, message_replies.sender_id';
    $joins = 'LEFT JOIN messages ON userdata.id  = messages.sender_id LEFT JOIN message_replies ON messages.id = message_replies.message_id';
    $orders = 'ORDER BY created_at DESC';

    $query = "SELECT {$selects} FROM userdata {$joins} where messages.receiver_id = {$userId} {$orders}";
    $rows = mysql_query($query);

    return $rows; 
}
function getPosts()   {
  $userId = $_SESSION['user_id'];

  $selects = 'posts.id, userdata.fname, userdata.lname, posts.content, posts.created_at';
  $joins = 'INNER JOIN posts ON userdata.id  = posts.author_id';
  $orders = 'ORDER BY posts.created_at DESC';

  $query = "SELECT {$selects} FROM userdata {$joins} where posts.author_id = {$userId} {$orders}";
  $rows = mysql_query($query);

  return $rows; 
}

if(isset($_POST['deleteMessage'])) {
  // sql to delete a record
  $sql = "DELETE FROM messages WHERE id = {$_POST['message_id']}";

  // use exec() because no results are returned
  $connection->exec($sql);
  
  echo "Record deleted successfully";
}
if(isset($_POST['deletePost'])) {
  // sql to delete a record
  $sql = "DELETE FROM posts WHERE id = {$_POST['post_id']}";

  // use exec() because no results are returned
  $connection->exec($sql);
  
  echo "Record deleted successfully";
}

if(isset($_POST['replyMessage'])) {
  $messageId = $_POST['message_id'];
  $userId = $_SESSION['user_id'];
  $messageContent = $_POST['content'];

  $sql = "INSERT INTO message_replies (message_id, sender_id, content)
  VALUES (?, ?, ?)";

  $connection->prepare($sql)->execute([$messageId, $userId, $messageContent]);
  
  echo "Record added successfully";

  echo "<meta http-equiv='refresh' content='0'>";
}
?>