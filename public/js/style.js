$(document).ready(function() {
    $(".eye-icon").click(function () {
        var x = document.getElementById("inputPassword");
        if (x.type === "password") {
            x.type = "text";
            $('.eye-icon').html('<i class="fa fa-eye-slash"></i>');
        } else {
            x.type = "password";
            $('.eye-icon').html('<i class="fa fa-eye"></i>');
        }
    });

    $(".icon-password").click(function () {
        var x = document.getElementById("confirmPsd");
        if (x.type === "password") {
            x.type = "text";
            $('.icon-password').html('<i class="fa fa-eye-slash"></i>');
        } else {
            x.type = "password";
            $('.icon-password').html('<i class="fa fa-eye"></i>');
        }
    });

    // User active/Inactive
    $(".userStatusUpdate").click(function(e) {
        e.preventDefault();
        let currentInput = $(this).find("input");

        var r = confirm("Are you sure you want to update it");
        
        if (r == true) {
            $.ajax({
                url: "/updateStatus",
                type: "post",
                data: {
                    id: $(this).data("id"),
                    user_status: $(this).attr("data-current"),
                    _token: $(this).prev().val()
                },
                beforeSend: function() {
                    // setting a timeout
                    jQuery("#loader-icon").removeClass("d-none");
                    jQuery(".ajax-msg").addClass("d-none");
                },

                success: function(result) {  
                    jQuery("#loader-icon").addClass("d-none");
                    jQuery(".ajax-msg").removeClass("d-none");
               
                    if (result.form_data.status == "Active") {
                        
                        $(currentInput[0]).attr('checked', true);
                        e.currentTarget.setAttribute('data-current', result.form_data.status);
                        $("#successMessage").html("User Activated Successfully");
                    
                        
                    } else {
                        $(currentInput[0]).attr('checked', false);
                        e.currentTarget.setAttribute('data-current', result.form_data.status);
                        $("#successMessage").html("User Deactivated Successfully");  
                    }
                },
                error: function(result) {
                    //console.log(result);
                }
            });
        } else {
            // He refused the confirmation
        }

    });
});


$(document).ready(function() {
    $(".permission-name").click(function(){

        var arr = $(this).val();       

        if($(this).prop("checked") == true){
            if(arr == 1){
                $("input[type=checkbox][value='"+3+"']").prop('checked',true);
            }
            else if(arr == 3){
                $("input[type=checkbox][value='"+1+"']").prop('checked',true);
            }                
        }
        if($(this).prop("checked") == false){
            if(arr == 1){
                $("input[type=checkbox][value='"+3+"']").prop('checked',false);
            }
            else if(arr == 3){
                $("input[type=checkbox][value='"+1+"']").prop('checked',false);
            }
        }
        
    });

    //  Serach filter for users(in admin section)
    $("#userSearch").click(function (event) {
        event.preventDefault();
        $("#formUserListing").submit();
    });

    //  Serach filter for payment(in admin section)
    $("#formPaymentListing").click(function (event) {
        event.preventDefault();
        $("#paymentListing").submit();
    });

    // Payment Setting active/Inactive
    $(".paymentStatusUpdate").click(function(e) {
        e.preventDefault();
        let currentInput = $(this).find("input");
        var r = confirm("Are you sure you want to update it");
        
        if (r == true) {
            $.ajax({
                url: "/updatePaymentStatus",
                type: "post",
                data: {
                    id: $(this).data("id"),
                    payment_status: $(this).attr("data-current"),
                    _token: $(this).prev().val()
                },
                beforeSend: function() {
                    // setting a timeout
                    jQuery("#loader-icon").removeClass("d-none");
                    jQuery(".ajax-msg").addClass("d-none");
                },

                success: function(result) { 
                    jQuery("#loader-icon").addClass("d-none");
                    jQuery(".ajax-msg").removeClass("d-none");
               
                    if (result.form_data.status == "1") 
                    {
                        $(currentInput).attr('checked', true);
                        e.currentTarget.setAttribute('data-current', result.form_data.status);
                        $("#successMessage").html("Setting Activated Successfully");
                    } else {
                        $(currentInput).attr('checked', false);
                        e.currentTarget.setAttribute('data-current', result.form_data.status);
                        $("#successMessage").html("Setting Deactivated Successfully");  
                    }
                },
                error: function(result) {
                    //console.log(result);
                }
            });
        } else {
            // He refused the confirmation
        }

    });

    // Display payment form only when value is selected from dropdown
    $('#payment-value').on('change', function() {
       // if()
        $('.row').removeClass("payment-form");
    });

    // Check there is some-data or not(in the form)
    if($('#payment-value').val('')){
        $('.row').removeClass("payment-form");
    } else{
        
        $('.row').addClass("payment-form");
    }
});
 




