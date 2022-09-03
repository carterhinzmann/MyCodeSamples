<?php
    session_start();
    
    require_once "Regex_file.php"; // regex file
    require_once "basic_functions_file.php"; // file containing a few basic/commonly used functions
    require_once "validate_functions.php"; // file containing functions for validation
    require_once "database_connect.php";

    $validated = true; // if remains true, login passes checks/backend security measures
    $_submit = $_POST["submit"];
    
    if (isset($_POST["email_submit"]) && $_POST["email_submit"]) {
        $validated_email = true;

        $_email = inputCleaning($db, $_POST["email-change"]);
        $_email_confirm = inputCleaning($db, $_POST["email-change-confirm"]);
   
        $validated_email = validateEmail($_email); // returns true or false value into variable

        if ($_email != $_email_confirm || $_email_confirm == "" || $_email_confirm == null) {
            $validated_email = false;
        }

        if ($validated_email == true) {
            $q_pass = "UPDATE userbase SET user_email = '$_email' WHERE user_email = '$user_email';";
            $r_pass = queryDatabase($q_pass);
        }
        if ($r_pass === true) {
            $_SESSION["user_email"] = $_email;
           
            $db->close();
            header("Location: account.php");
            //exit();
        }
        else {
            $db->close();
        }
    } 

    if (isset($_POST["password_submit"]) && $_POST["password_submit"]) {
        $validated_password = true;

        $_password = inputCleaning($db, $_POST["password-change"]);
        $_password_confirm = inputCleaning($db, $_POST["password-change-confirm"]);

        $validated_password = validatePassword($_password); // returns true or false value into variable
        $validated_password = validatePasswordConfirm($_password, $_password_confirm);

        if ($validated_password == true) {
            $q_pass = "UPDATE userbase SET user_password = '$_password' WHERE user_email = '$user_email';";
            $r_pass = queryDatabase($q_pass);
        }
        if ($r_pass === true) {
            
            $db->close();
            header("Location: account.php");
            //exit();
        }
        else {
            $db->close();
        }
    }

    if (isset($_POST["major_submit"]) && $_POST["major_submit"]) {
        $validated_major = true;

        $_major = inputCleaning($db, $_POST["major-change"]);
  
        $validated_major = validateMajor($_major); // returns true or false value into variable

        if ($validated_major == true) {
            $q_pass = "UPDATE userbase SET user_major = '$_major' WHERE user_email = '$user_email';";
            $r_pass = queryDatabase($q_pass);
        }
        if ($r_pass === true) {
          
            $db->close();
            header("Location: account.php");
            //exit();
        }
        else {
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
        <div class="account-form"> <!-- where the account form will be placed -->
            <h1><?= _ACCOUNT_DETAILS ?></h>
            <p><?= _CHANGE_ACCOUNT_INFO ?></p>
            <hr>
            <form name="account-details" id="account-details" action="account.php" method="post"> 
                <label for="account-details-email"><b><?= _CHANGE_ACTIVE_EMAIL ?>:</b></label><br><br>
                <input type="text" placeholder="New Email" name="email-change" id="_email" minlength="8" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <span class="error"></span><br><br>
                <button type="submit" class="sitewideSubmission" value="submit" id="email_submit" name="email_submit"><?= _SUBMIT ?></button><br><br>

                <label for="account-details-password"><b><?= _CHANGE_ACTIVE_PASSWORD ?>:</b></label><br><br>
                <input type="text" placeholder="New password" name="password-change" id="_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8">
                <span class="error"></span><br><br>
                <label for="account-details-password-confirm"><b><?= _CONFIRM_ACTIVE_PASSWORD ?>:</b></label><br><br>
                <input type="text" placeholder="Confirm New Password" name="password-change-confirm" id="_passwordconfirm" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8">
                <span class="error"></span><br><br>
                <button type="submit" class="sitewideSubmission" value="submit" id="password_submit" name="password_submit"><?= _SUBMIT ?></button><br><br>

                <label for="account-details-major"><b><?= _CHANGE_ACTIVE_MAJOR ?>:</b></label><br><br>
                <input type="text" placeholder="New Major" name="major-change" id="_major" pattern="Computer Science|Physics|Mathematics|Statistics|Biology|Chemistry|Biochemistry|Geology|Education|Electronic Systems Engineering|Environmental Systems Engineering|Industrial Systems Engineering|Petroleum Systems Engineering|Software Systems Engineering|Process Systems Engineering|Kinesiology|Health Studies|Sport and Recreation Studies|Film|Music|Theatre|Visual Arts|Creative Technologies|Nursing|Accounting|Finance|Human Resource Management|International Business|Marketing|Management|Social Work|Anthropology|Economics|English|French|Gender Studies|Religious Studies|Critical Studies|Geography|Environmental Studies|History|International Languages|Journalism|Justice Studies|Philosophy|Classics|Politics|Psychology|Sociology|Social Studies|Indigenous Studies|Linguistics|Education">
                <span class="error"></span><br><br>
                <button type="submit" class="sitewideSubmission" value="submit" id="major_submit" name="major_submit"><?= _SUBMIT ?></button><br>
               
            </form> <!-- end of account details form -->
            </div> <!-- end of div class "account-form" -->
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
        <script type="text/javascript" src="signup_validation.js"></script>
    </div> <!-- end of div class "grid-container" -->  
</body> <!-- end of body -->
</html> <!-- end of html -->