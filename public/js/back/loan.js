$(document).ready(function () {
	$('#sendData').click(function(e) {
		event.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('loanservice') }}",
			method: 'POST',
			data: {
				amount: $('#amount').val(),
				length_of_loan: $('#length_of_loan').val(),
				payment_frequency: $('#payment_frequency').val(),
				interest_rate: $('#interest_rate').val(),
				loan_title: $('#loan_title').val(),
				length_of_loan: $('#length_of_loan').val(),
				credit_score: $('#credit_score').val()
			},
			success: function(data) {
				//console.log(data);
				$('#append-title').text(data.titleOfLoan);
				$('#installments').text(data.lengthOfLoan);
				$('#principal').text(data.principal);
				$('#interest').text(data.totalInterest);
				$('#installment-amount').text(data.installmentAmount);
				$('#total').text(data.totalAmount);
				$('#showModal').trigger('click');

				// Send data on confirm button click
				$('#confirmLoan').click(function(e) {
					e.preventDefault();
					let expected_score = $('#credit_score').val();

					// Check whether credit score matches
					if (data.score < expected_score) {
						$('#cancel').trigger('click');
						swal({
						  title: "Loan Denied",
						  text: "You do not meet the minimum loan requirements",
						  icon: "warning",
						  buttons: true,
						  dangerMode: true,
						});
					} else {

					}
				});
			}
		});
	});
});