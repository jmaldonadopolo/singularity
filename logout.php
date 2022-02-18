<?php
session_start (); 
unset($_SESSION['singularity_email']); 
//session_destroy();
header("Location: index.php");
?>