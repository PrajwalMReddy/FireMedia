<?php
  session_start();
  session_unset();
  session_destroy();

  header("location: ../codes/blogs.php");
  exit();