<?php
    // contains basic functions required throughout the site
    require_once "database_connect.php";

    function sessionDestroy() { // destroy the current users session (CALL function on logout)
        $_SESSION = array();
        if (session_id() != "" || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-2592000, '/');
        }
	    session_destroy();
    }

    function clean($variable) { // call to sanitize a string
        $variable = strip_tags($variable);
        $variable = stripslashes($variable);
        $variable = htmlentities($variable);
        $variable = htmlspecialchars($variable); 
        $variable = trim($variable);
        return $variable;
    }

    function inputCleaning($db, $variable) { // call to clean before any entry into DB. Does a full clean.
        $variable = $db->real_escape_string($variable);
        $variable = clean($variable);
        return $variable;
    }

    function queryDatabase($query) { // query's the DB 
        global $db;
        $result = $db->query($query);
        //if (!$result) die("DataBaseError");
        return $result;
    }

    // add more as we need
?>