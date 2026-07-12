<?php
/*
    logout.php
    ----------------------------------------------------------
    Destroys the session (works for both student and admin)
    and redirects back to the home page.
    ----------------------------------------------------------
*/
session_start();
session_unset();     // remove all session variables
session_destroy();   // destroy the session itself
header("Location: index.php");
exit();
