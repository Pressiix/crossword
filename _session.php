<?php
// Start the session
session_start();

if(isset($_GET['last_score']))
{
    $_SESSION["score"] = $_GET['last_score'];
}

?>