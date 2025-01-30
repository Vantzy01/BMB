document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form");
    const fullName = document.getElementById("fullName");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const mobileNo = document.getElementById("mobileNo");
    const password = document.getElementById("password");
    const password2 = document.getElementById("password2");
    const address = document.getElementById("address");
    const packageSelect = document.getElementById("package");
    const terms = document.getElementById("terms");
    const latitude = document.getElementById("latitude");
    const longitude = document.getElementById("longitude");

    form.addEventListener("submit", (e) => {
        e.preventDefault();
    
        if (validateInputs()) { 
            form.submit();
        }
    });

    function setError(element, message) {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector(".error");

        errorDisplay.innerText = message;
        inputControl.classList.add("error");
        inputControl.classList.remove("success");
    }

    function setSuccess(element) {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector(".error");

        errorDisplay.innerText = "";
        inputControl.classList.add("success");
        inputControl.classList.remove("error");
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function isValidPhone(phone) {
        const re = /^[0-9]{11}$/; // Ensures exactly 11 digits
        return re.test(phone);
    }

    function validateInputs() {
        let isValid = true;

        if (fullName.value.trim() === "") {
            setError(fullName, "Full Name is required");
            isValid = false;
        } else {
            setSuccess(fullName);
        }

        if (username.value.trim() === "") {
            setError(username, "Username is required");
            isValid = false;
        } else {
            setSuccess(username);
        }

        if (email.value.trim() === "") {
            setError(email, "Email is required");
            isValid = false;
        } else if (!isValidEmail(email.value.trim())) {
            setError(email, "Enter a valid email address");
            isValid = false;
        } else {
            setSuccess(email);
        }

        if (mobileNo.value.trim() === "") {
            setError(mobileNo, "Phone Number is required");
            isValid = false;
        } else if (!isValidPhone(mobileNo.value.trim())) {
            setError(mobileNo, "Phone Number must be 11 digits");
            isValid = false;
        } else {
            setSuccess(mobileNo);
        }

        if (password.value.trim() === "") {
            setError(password, "Password is required");
            isValid = false;
        } else if (password.value.length < 8) {
            setError(password, "Password must be at least 8 characters");
            isValid = false;
        } else {
            setSuccess(password);
        }

        if (password2.value.trim() === "") {
            setError(password2, "Please confirm your password");
            isValid = false;
        } else if (password2.value !== password.value) {
            setError(password2, "Passwords do not match");
            isValid = false;
        } else {
            setSuccess(password2);
        }

        if (address.value.trim() === "") {
            setError(address, "Address is required");
            isValid = false;
        } else {
            setSuccess(address);
        }

        if (packageSelect.value === "") {
            setError(packageSelect, "Please select a package");
            isValid = false;
        } else {
            setSuccess(packageSelect);
        }

        if (!terms.checked) {
            setError(terms, "You must agree to the terms and conditions");
            isValid = false;
        } else {
            setSuccess(terms);
        }

        if (!latitude || !longitude) {
            alert("Please set your location before submitting.");
            return false; // Stop submission if lat/lng are empty
        }

        return isValid;
    }
});

