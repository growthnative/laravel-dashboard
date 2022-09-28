jQuery(document).ready(function($) {
    
    // Validations for user module
    jQuery("#profile-data, #user-data , #update-user-data , #role-data , #user-register , #user-login , #password-forgot , #setPassword").validate({
        rules: {
            name: "required",
            last_name : "required",
            email: "required",
            password: "required",
            password_confirmation: "required",
            address: "required",
            phone_number: "required",
            state: "required",
            city: "required",
            zip_code: "required",
            role_id: "required",
        },
        messages: {
            name: "Name is required",
            last_name: "Last Name is required",
            email: "Email is required",
            password: "Password is required",
            password_confirmation: "Confirm Password is required",
            address: "Address is required",
            phone_number: "Phone Number is required",
            state: "State is required",
            city: "City is required",
            zip_code: "Zip Code is required",
            role_id: "Please select role"
        },
        errorPlacement: function(error, element) {
            if (element) {
                error.appendTo(element.parents('.form-data'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }

    });

    // Validation for password change
    jQuery('form[id="change-password"]').validate({
        rules: {
            current_password: "required",
            password: "required",
            password_confirmation: "required",
        },
        messages: {
            current_password: "This is required",
            password: "This is required",
            password_confirmation: "This is required",
        },
        errorPlacement: function(error, element) {
            if (element) {
                error.appendTo(element.parents('.form-data'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

// Image Validation
function readURL(input) {
    // Only jpg,jpeg and png are allowed
    var fileInput = document.getElementById('ProfileImage');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('Only images are allowed, Please try again.');
        fileInput.value = '';
        return false;
    }else{
    // Display/preview image
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#pimage').html('<img class ="rounded-circle" id="profileLogo" src="'+e.target.result+'"/>');
        };
        reader.readAsDataURL(input.files[0]);
    }}
}