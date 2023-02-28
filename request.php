<?php
require('./inc/connect.php');
require('./inc/session.php');

if (isset($_GET['comment'])) {
  $comment = $_GET['comment'];
} elseif (isset($_POST['comment'])) {
  $comment = $_POST['comment'];
}

$result = array();
if ($_SESSION['is_login']) {
  $user = $_SESSION['user_id'];
  $comment = mysqli_real_escape_string($conn, $comment);
  $stmt = "INSERT INTO comments (user, comment) VALUES(1, '$comment')";
  if (mysqli_query($conn, $stmt)) {
    $stmt = "SELECT c.comment as comment, c.create_date as create_date, u.username as username FROM comments c LEFT JOIN users u ON c.user = u.id WHERE user = $user ORDER BY create_date DESC ";
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
      "message" => "Comment is not inserted properly"
    );
  }
} else {
  $result = array(
    "flag" => false,
    "message" => "Cannot find user"
  );
}

echo json_encode($result);
