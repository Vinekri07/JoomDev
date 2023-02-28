<?php
ini_set('display_errors', 1);
require('./inc/connect.php');
require('./inc/session.php');

$result = array();
if ($_SESSION['is_login']) {
  $user = $_SESSION['user_id'];
  $stmt = "SELECT c.comment as comment, c.create_date as create_date, u.username as username FROM comments c LEFT JOIN users u ON c.user = u.id WHERE user = $user ORDER BY create_date DESC";
  $rslt = mysqli_query($conn, $stmt);
  $comment_data = array();
  if (mysqli_num_rows($rslt) > 0) {
    while ($row = mysqli_fetch_assoc($rslt)) {
      $comment_data[] = $row;
    }
    $html = "<div class=\"ui comments\"><h3 class=\"ui dividing header\">Comments</h3>";
    foreach ($comment_data as $key => $value) {
      $html .= "<div class=\"comment\"><div class=\"content\"><a class=\"author\">" . $value['username'] . "</a><div class=\"metadata\"><span class=\"date\">" . $value['create_date'] . "</span></div><div class=\"text\">" . str_replace(" ", " &nbsp;", $value['comment']) . "</div></div></div>";
    }
    $html .= "</div>";

    $result = array(
      "flag" => true,
      "count" => mysqli_num_rows($rslt),
      "html" => nl2br($html)
    );
  } else {
    $result = array(
      "flag" => true,
      "count" => 0
    );
  }
} else {
  $result = array(
    "flag" => false,
    "message" => "Cannot find user"
  );
  header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="./libs/Jquery/jquery.js"></script>
  <link rel="stylesheet" href="./libs/Semantic-UI/semantic.min.css">
  <script src="./libs/Semantic-UI/semantic.min.js"></script>
  <title>Comment</title>

</head>

<body>
  <div class="ui basic right aligned segment">
    <a href="logout.php" class="ui red button">Logout</a>
  </div>
  <div class="ui segment">
    <div class=" ui container">

      <h2 class="ui teal image header">
        <div class="content">
          Add Comment Here
        </div>
      </h2>
      <div class="ui form">
        <div class="u field">
          <textarea name="comment" id="comment_area" rows="10"></textarea>
        </div>
        <div class="ui field">
          <input type="button" value="Add Comment" id="add_comment" class="ui teal button">
        </div>
      </div>

    </div>
    <div class="ui divider">

    </div>
    <div class="ui container">
      <h2 class="ui blue image header">
        <div class="content">
          Comments
        </div>
      </h2>
      <div class="ui blue segment">
        <div class="ui basic segment" id="comments">
          <?php
          if (!empty($result) && ($result['flag']) && ($result['count'] > 0)) {
            echo $result['html'];
          }
          ?>
        </div>
      </div>
    </div>
  </div>

</body>

</html>
<script>
  $("#add_comment").click(function() {
    var comment = $("#comment_area").val();

    $.ajax({
      url: 'request.php',
      method: "POST",
      data: {
        comment: comment,
      },
      success: function(result) {
        if (result) {
          var response_obj = JSON.parse(result);
          console.log(response_obj);
          if (response_obj['count'] > 0) {
            $("#comments").html(response_obj['html']);
          } else {
            $("#comments").html('');
          }
        } else {

        }
      }
    })
  });
</script>