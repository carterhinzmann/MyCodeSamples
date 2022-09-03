<?php
  
    function validateEmail($_email) {
        require 'Regex_file.php';
        
        return (preg_match($regex_email, $_email) && $_email != "" && $_email != null);
    }

    function validatePassword($_password) {
        require 'Regex_file.php';
        
        return (preg_match($regex_password, $_password) && $_password != "" && $_password != null);
    }

    function validatePasswordConfirm($_password, $_passwordConfirm) {
        require 'Regex_file.php';

        return ($_password == $_passwordConfirm && $_passwordConfirm != "" && $_passwordConfirm != null);
    }

    function validateMajor($_major) {
        require 'Regex_file.php';
        
        return (preg_match($regex_major, $_major) && $_major != "" && $_major != null);
    }

    function validateDateOfBirth($_dateofbirth) {
        require 'Regex_file.php';
        
        return ($_dateofbirth != null);
    }
?>