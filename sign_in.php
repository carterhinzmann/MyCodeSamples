<?php
    session_start();
    
    require_once "Regex_file.php"; // regex file
    require_once "basic_functions_file.php"; // file containing a few basic/commonly used functions
    require_once "validate_functions.php"; // file containing functions for validation
    require_once "database_connect.php"; // file for database connection

    $validated = true; // if remains true, login passes checks/backend security measures
    $_submit = $_POST["submit"];
    
    if (isset($_POST["submit"]) && $_POST["submit"]) { // if form is set & submitted
 
        $_email = inputCleaning($db, $_POST["_email"]);
        $_password = inputCleaning($db, $_POST["_password"]);
        
        // check if user's credentials validate with DB
        $query_1 = "SELECT * FROM userbase WHERE user_email = '$_email';";
        $result_1 = queryDatabase($query_1);
        $row = $result_1->fetch_assoc(); // fetch_assoc grabs an associative array (row-by-row),
       
       // if credentials dont match
        if ($_email != $row["user_email"] && $_password != $row["user_password"]) {
            $validated = false;
           $invalid_login = "Email/password combination incorrect"; 
        }
   
        // validation functions
        $_validate = validateEmail($_email); // returns true or false value into variable
        if ($_validate == false) $validated = false; // tests against returned value
         
        $_validate = validatePassword($_password);
        if ($_validate == false) $validated = false; // tests against returned value

        if ($validated == true) { // if DB credentials match
            session_start(); // start session variable
            $_SESSION["user_email"] = $row["user_email"];
            header("Location: Main_Index.php"); // probably direct to an index page 
        } else {
            $failMessage = "User Id or Password incorrect.";
            $db->close();
        }
    }

    // Set Language variable
    if(isset($_GET['lang']) && !empty($_GET['lang'])){
       $_SESSION['lang'] = $_GET['lang'];
       
       if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']){
       echo "<script type='text/javascript'> location.reload(); </script>";
       }
   }
   
   // Include Language file
   if(isset($_SESSION['lang'])){
       include "language/lang.".$_SESSION['lang'].".php";
   }else{
       include "language/lang.en.php";
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cssStyles.css">
    <title><?= _URFORUM ?></title>
</head>
<body>
    <script>
        function changeLang(){
            document.getElementById('form_lang').submit();
        }
    </script>

    <div class="grid-container"> <!-- website wrapping -->
        <div class="main-body">
            <div class="login-form"> <!-- where the account form will be placed -->
            <h1><?= _LOG_IN ?></h>
            <?php 
                if ($failMessage) {
                    echo "<br><br>";
                    echo "<label class='err_msg' id='err_msg'>$failMessage</label>";
                    echo "<br>";
                } 
            ?>
            <hr>
            <form name="form" method="post" id="form" action="login.php" novalidate>
                <label for="_email"><b><?= _EMAIL ?></b></label><br><br>
                <input type="email" name="_email" id="_email" required>
                <span class="error"></span><br><br>
                <label for="_password"><b><?= _PASSWORD ?></b></label><br><br>
                <input type="password" name="_password" id="_password" required><br>
                <br><br>
                <button type="submit" class="sitewideSubmission" id="submit" name="submit" value="submit"><?= _LOGIN ?></button>
            <input type="hidden" name="submit" value="1"/>
                <br><br>
            </form>
            </div> <!-- end of div class "login-form" -->
        </div> <!-- end of div class "main-body" -->

        <div class="header"> <!-- contains everything in sitewide, top header -->

            <div class="uni-logo">
                <img src="images/universityLogo.png" alt="" width="264" height="105" id="avatar"></img>
            </div> <!-- end of uni-logo, sitewide header -->
            
            <h1><?= _URFORUM ?></h1>    <!-- Title of site -->

            <?php
                    if(isset($_SESSION['user_email'])){
            ?>

                <div class="sitewide-logout">
                    <a href="logout.php"><?= _LOG_OUT ?></a>
                </div> <!-- end of login-button, sitewide header -->

                <div class="account-details">
                    <a href="account.php"><?= _ACCOUNT_INFORMATION ?></a>
                </div>  <!-- end of account-details, sitewide header -->

                <div class="current-login">
                    <p> <?= _LOGGED_IN_AS ?>: <br> <? echo $_SESSION['user_email']; ?> </p> <!-- Will display user that is logged in top right banner -->
                    </div> <!-- end of current-login, sitewide header -->

            <?php
                   } else {
            ?>

                <div class="sitewide-signup">
                    <a href="signup.php"><?= _SIGN_UP ?></a>
                </div> <!-- end of signup-button, sitewide header -->

                <div class="sitewide-login">
                    <a href="login.php"><?= _LOG_IN ?></a>
                </div> <!-- end of login-button, sitewide header -->

            <?php
                   }
            ?>
        </div> <!-- end of header -->

        <div class="nav-bar"> <!-- sitewide navigation bar, contains everything for sitewide navigation -->
            <ul>
                <li><a href="Main_Index.php"><?= _HOME ?></a></li> <!-- left-hand nav bar links -->
                <li><a href="events.php"><?= _UR_EVENTS ?></a></li>
                <li><a href="news.php"><?= _NEWS ?></a></li>
            </ul> <!-- end of nav-bar ul listing -->

        <!-- Language -->
            <form method='get' action='' id='form_lang' class='form_lang'>
                <?= _SELECT_LANGUAGE ?>: <select name='lang' onchange='changeLang();' >
                    <option value='en' <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en'){ echo "selected"; } ?> >English</option>
                    <option value='ch' <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'ch'){ echo "selected"; } ?> >中文</option>
                </select>
            </form>
        </div> <!-- end of nav class "nav-bar" -->
        
    </div> <!-- end of div class "grid-container" -->  
</body> <!-- end of body -->
</html> <!-- end of html -->