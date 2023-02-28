<?php
ini_set('display_errors', 1);
require('./inc/connect.php');
require('./inc/session.php');

$result = array();
if ($_POST) {
  if (isset($_POST['login'])) { //Login
    $username = $_POST['email'];
    $password = $_POST['password'];

    $stmt = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "' LIMIT 1 ";
    $rslt = mysqli_query($conn, $stmt);
    if (mysqli_num_rows($rslt) > 0) {
      $row = mysqli_fetch_assoc($rslt);
      $_SESSION['is_login'] = true;
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['name'] = $row['name'];
      header("Location:comments.php");
    }
  } elseif (isset($_POST['signup'])) { //Signup 
    $name = $_POST['name'];
    $username = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($name) && !empty($password)) {
      $stmt = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "' LIMIT 1 ";
      $rslt = mysqli_query($conn, $stmt);
      if (mysqli_num_rows($rslt) <= 0) {
        $stmt = "INSERT INTO users (name, username, password) VALUES('" . $name . "','" . $username . "','" . $password . "')";
        if (mysqli_query($conn, $stmt)) {
          $result = array(
            "flag" => true,
            "type" => "signup",
            "message" => "Register Successfully. Please Login with your credentials"
          );
        } else {
          $result = array(
            "flag" => false,
            "type" => "signup",
            "message" => "!Something went wrong. Please Register Again"
          );
        }
      } else {
        $result = array(
          "flag" => false,
          "type" => "signup",
          "message" => "User already present with this email ID"
        );
      }
    } else {
      $result = array(
        "flag" => false,
        "type" => "signup",
        "message" => "!Something went wrong. Please fill all details required"
      );
    }
  }
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
  <title>Document</title>
  <style type="text/css">
    body {
      background-color: #DADADA;
    }

    body>.grid {
      height: 100%;
    }

    .image {
      margin-top: -100px;
    }

    .column {
      max-width: 450px;
    }
  </style>
</head>

<body>
  <?php
  if (isset($result) && !empty($result)) { ?>
    <div class="ui <?php echo (isset($result['flag']) && $result['flag'] == true) ? 'positive' : 'negative'; ?> message">
      <?php echo $result['message']; ?>
    </div>
  <?php  }
  ?>
  <div class="ui middle aligned center aligned grid">
    <div class="column">
      <h2 class="ui teal image header">
        <div class="content">
          Log-in to your account
        </div>
      </h2>
      <form class="ui large form" method="POST">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="email" name="email" placeholder="E-Mail Address" required>
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="password" placeholder="Password" required>
            </div>
          </div>
          <div class="ui field">
            <input type="submit" class="ui teal button" name="login" value="Login">
          </div>
        </div>

        <div class="ui error message"></div>

      </form>

      <div class="ui message">
        New to us? <a href="#" id="signup_btn">Sign Up</a>
      </div>
    </div>
  </div>
  <div class="ui modal">
    <h2 class="ui teal image header">
      <div class="content">
        Register Here
      </div>
    </h2>
    <form class="ui large form" method="POST">
      <div class="ui stacked segment">
        <div class="field">
          <label for="">Name</label>
          <input type="text" name="name" placeholder="Enter Name Here" required>
        </div>
        <div class="field">
          <label for="">E-Mail</label>
          <input type="email" name="email" placeholder="E-Mail Address" required>
        </div>
        <div class="field">
          <label for="">Password</label>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="ui field">
          <input type="submit" class="ui teal button" name="signup" value="SignUp">
        </div>
      </div>

      <div class="ui error message"></div>

    </form>
  </div>
</body>
<script>
  $("#signup_btn").click(function() {
    $(".ui.modal").modal("show");
  });
</script>

</html>