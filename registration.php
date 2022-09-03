<?php
    session_start();
    
    require_once "Regex_file.php"; // regex file
    require_once "basic_functions_file.php"; // file containing a few basic/commonly used functions
    require_once "validate_functions.php"; // file containing functions for validation
    require_once "database_connect.php";

    $validated = true; // if remains true, login passes checks/backend security measures
    $_submit = $_POST["submit"];
    
    if (isset($_POST["submit"]) && $_POST["submit"]) { // if form is set & submitted
 
       // if successfully submitted, passed client-side validation tests, proceeding now to layer-2 validation
        // will now retrieve form data from superglobals and clean the strings
      
        // CALL CLEANING FUNCTIONS , sanitizes whatever user enters completely from malicious attempts
        $_email = inputCleaning($db, $_POST["_email"]);
        $_password = inputCleaning($db, $_POST["_password"]);
        $_passwordconfirm = inputCleaning($db, $_POST["_passwordconfirm"]);
        $_major = inputCleaning($db, $_POST["_major"]);
        $_dateofbirth = inputCleaning($db, $_POST["_dateofbirth"]);
        // WILL ADD AVATAR
        // input sanitized and ready to enter DB

        $query = "SELECT user_email FROM userbase WHERE user_email = '$_email';"; // checking if email exists already
        $result = queryDatabase($query);
        $row = $result->fetch_assoc();

        if ($row->num_rows > 0) { // if row exists in DB then email exists already in DB
            $validated = false;
        }
        else { // user/email is not already taken and can be registered with
            // functions for validation

            if (validateEmail($_email) == false) $validated = false; // tests against returned value

            if (validatePassword($_password) == false) $validated = false;

            if (validatePasswordConfirm($_password, $_passwordconfirm) == false) $validated = false;

            if (validateMajor($_major) == false) $validated = false;

            if (validateDateOfBirth($_dateofbirth) == false) $validated = false;
        }

        if ($validated == true) { // passed checks

            $_dateFormatting = date("Y-m-d", strtotime($_dateofbirth)); // formats date into proper form for entry into DB
            
            // enter the users registration details into the DB
            $query_FullPass = "INSERT INTO userbase (user_email, user_password, user_major, user_dateofbith) VALUES ('$_email', '$_password', '$_major', '$_dateFormatting');"; 
            $result_FullPass = queryDatabase($query_FullPass);
            echo "<script>alert('result_FullPass: $result_FullPass')</script>";

            if ($result_FullPass === true) { // identity hard equivalence
                // successful, redirect to the login page
                header("Location: login.php"); // Future Addition? : Present a success message, then do a 2-4 second timed redirect to login page. I feel like it would be less jarring.
                $db->close();
                exit();
            }
        }
        else {
            // exceptions, errors --> ADD REMINDER
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
        <div class="signup-form"> <!-- where the account form will be placed -->
            <h1><?= _REGISTRATION ?></h>
            <?php 
                if ($failMessage) {
                    echo "<br><br>";
                    echo "<label class='err_msg' id='err_msg'>$failMessage</label>";
                    echo "<br>";
                } 
            ?>
            <p><?= _FILL_FORM ?></p>
            <hr>
            <br>
            <form name="form" action="signup.php" method="post" id="form" novalidate>
                <label for="_email"></label>
                <p><?= _ENTER_EMAIL ?>:</p><br>
                <input type="email" placeholder="e.g. stc291@uregina.ca" name="_email" id="_email" required minlength="8" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <span class="error"></span><br><br>

                <label for="_password"></label>
                <p><?= _ENTER_PASSWORD ?>:</p><br>
                <input type="password" name="_password" id="_password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8">
                <span class="error"></span><br><br>

                <label for="_passwordconfirm"></label>
                <p><?= _REENTER_PASSWORD ?>:</p><br>
                <input type="password" placeholder="Confirm Password" name="_passwordconfirm" id="_passwordconfirm" required>
                <span class="error"></span><br><br>

                <label for="_major"></label>
                <p><?= _ENTER_PROGRAM_MAJOR ?></p><br>
                <input type="text" placeholder="e.g. Biology, or Software Systems Engineering" name="_major" id="_major" required pattern="Computer Science|Physics|Mathematics|Statistics|Biology|Chemistry|Biochemistry|Geology|Education|Electronic Systems Engineering|Environmental Systems Engineering|Industrial Systems Engineering|Petroleum Systems Engineering|Software Systems Engineering|Process Systems Engineering|Kinesiology|Health Studies|Sport and Recreation Studies|Film|Music|Theatre|Visual Arts|Creative Technologies|Nursing|Accounting|Finance|Human Resource Management|International Business|Marketing|Management|Social Work|Anthropology|Economics|English|French|Gender Studies|Religious Studies|Critical Studies|Geography|Environmental Studies|History|International Languages|Journalism|Justice Studies|Philosophy|Classics|Politics|Psychology|Sociology|Social Studies|Indigenous Studies|Linguistics|Education"> <!-- add rest -->
                <span class="error"></span><br><br><br>

                <label for="_dateofbirth"></label>
                <p><?= _ENTER_BIRTHDATE ?>:</p><br>
                <input type="date"  name="_dateofbirth" id="_dateofbirth" required min="1922-12-31" max="2003-12-31">
                <span class="error"></span><br><br>

                <button type="submit" class="sitewideSubmission" value="submit" id="submit" name="submit"><?= _REGISTER ?></button>
            </form>
            </div> <!-- end of div class "signup-forum" -->
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