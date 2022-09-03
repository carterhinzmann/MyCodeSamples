const form  = document.getElementsByTagName('form'); // form validation done using JS Constraint Validation API
form.noValidate = true;

// getting form elements
const email = document.getElementById('_email');
const emailError = document.querySelector('#_email + span.error'); 

const password = document.getElementById('_password');
const passwordError = document.querySelector('#_password + span.error');

const passwordconfirm = document.getElementById('_passwordconfirm');
const passwordconfirmError = document.querySelector('#_passwordconfirm + span.error');

const major = document.getElementById('_major');
const majorError = document.querySelector('#_major + span.error');

const dateofbirth = document.getElementById('_dateofbirth');
const dateofbirthError = document.querySelector('#_dateofbirth + span.error');

// event listeners on each form element

email.addEventListener('input', function (event) {
    
    if (email.validity.valid) {
        emailError.innerHTML = ''; // Reset the content of the message
        emailError.className = 'error'; // Reset the visual state of the message
    } else {
      showError_email();
    }
});

password.addEventListener('input', function (event) {
   
    if (password.validity.valid) {
        passwordError.innerHTML = ''; // Reset the content of the message
        passwordError.className = 'error'; // Reset the visual state of the message
    } else {
      showError_password();
    }
});

passwordconfirm.addEventListener('input', function (event) {
   showError_passwordconfirm();
});

major.addEventListener('input', function (event) {
    
    if (major.validity.valid) {
        majorError.innerHTML = ''; // Reset the content of the message
        majorError.className = 'error'; // Reset the visual state of the message
    } else {
      showError_major();
    }
});

dateofbirth.addEventListener('blur', function (event) {
    
    if (dateofbirth.validity.valid) {
        dateofbirthError.innerHTML = '';
        dateofbirthError.className = 'error';
    } else {
        showError_dateofbirth();
    }
});

form.addEventListener('submit', function (event) {
    if (!email.validity.valid) {
        showError_email();
        event.preventDefault();
    }

    if (!password.validity.valid) {
        showError_password();
        event.preventDefault();
    } 

    if (!passwordconfirm.validity.valid) {
        showError_passwordconfirm();
        event.preventDefault();
    }

    if (!major.validity.valid) {
        showError_major();
        event.preventDefault();
    }
});
    
// error functions

function showError_email() {
    if (email.validity.valueMissing) {
        emailError.textContent = 'You need to enter an e-mail address.';  
        emailError.className = 'error active';
    }
    else if (email.validity.patternMismatch) {
        emailError.textContent = 'Entered value needs to be a valid e-mail address.';
        emailError.className = 'error active';
    }
    else if (email.validity.tooShort) {
        emailError.textContent = `Email should be at least ${ email.minLength } characters; you entered ${ email.value.length }.`;
        emailError.className = 'error active';
    }
}

function showError_password() {
    if (password.validity.valueMissing) {
        passwordError.textContent = 'You need to enter a password.';
        passwordError.className = 'error active';
    } 
    else if (password.validity.patternMismatch) {
        passwordError.textContent = 'passwords must be 8+ characters, 1+ digit, 1+ uppercase, and 1+ lowercase characters.';
        passwordError.className = 'error active';
    }
}


function showError_passwordconfirm() {
    
    if (document.getElementById('_password').value === document.getElementById('_passwordconfirm').value) {
        passwordconfirm.setCustomValidity('');
    } else {
        passwordconfirm.setCustomValidity('ERR');
    }

    if (passwordconfirm.validity.valueMissing) {
        passwordconfirmError.textContent = 'password confirmation cannot be empty.';
        passwordconfirmError.className = 'error active';
    } else if (passwordconfirm.validity.customError == true) {
        passwordconfirmError.textContent = 'passwords do not match.';
        passwordconfirmError.className = 'error active';
    } else {
        passwordconfirmError.innerHTML = '';
        passwordconfirmError.className = 'error';
        // if form submission problem, just set passwordconfirm.validity.valid = true; and it should work
    }
}


function showError_major() {
    if (major.validity.valueMissing) {
        majorError.textContent = 'You need to enter your program major';
        majorError.className = 'error active';
    } else if (major.validity.patternMismatch) {
        majorError.textContent = 'You need to enter a valid program major.';
        majorError.className = 'error active';
    }
}

function showError_dateofbirth() {
    if (dateofbirth.validity.valueMissing) {
        dateofbirthError.textContent = 'You need to enter your date of birth';
        dateofbirthError.className = 'error active';
    } else if (dateofbirth.validity.rangeUnderflow) {
        dateofbirthError.textContent = 'You need to enter a valid date of birth';
        dateofbirthError.className = 'error active';
    } else if (dateofbirth.validity.rangeOverflow) {
        dateofbirthError.textContent = 'You need to enter a valid date of birth';
        dateofbirthError.className = 'error active';
    }
}