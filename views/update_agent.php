<?php
  session_start();

  if (isset($_POST['agent'])) {
    $_SESSION['selectedAgent'] = $_POST['agent'];
  }
?>