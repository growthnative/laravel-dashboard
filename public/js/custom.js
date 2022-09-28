$('#amountData').keyup(function(){
    if ($(this).val() > 1000){
      alert("Maximum amount should not be more than $1000");
      $(this).val('1000');
    }
});


$(document).ready(function(){
	$('.payment-show-data').click(function() {
		var id = $(this).attr("data-payment_id");
		var amount = $(this).attr("data-payment_amount");
		// alert(amount);
		var del = $('input[name="amount"]').val(amount);
		var data = $("#payment_id").val(id);
		$('#amountData').attr('data-max-amount',amount);

		$('input[name="payment_amount"]').attr('value', amount);

	});

	// Validation to check i.e entered value is correct or not
	$("#payment_data").click(
		function () {
			var maxValue = $('#amountData').attr('data-max-amount');
			
			var val = $('#amountData').val();	
			
			if(val <= 0)
			{
				$("#divError").html("Refund amount must be greater than 0").addClass("error-msg");
				return false;
			}
			else if(val > maxValue){
				$("#divError").html("Refund amount must not be greater than $" + '' + maxValue ).addClass("error-msg");
				return false;
			}
			else{
				$("#divError").html("").removeClass("error-msg");
			}
		}            
	)

});









