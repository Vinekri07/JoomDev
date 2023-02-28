<?php
require('./inc/connect.php');
require('./inc/session.php');
session_destroy();
header("Location:index.php");
