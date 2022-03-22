$(document).ready(function() {
	$('#resource').change(function() {
		var type = $(this).val();
		const monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];
		var d = new Date();
		//var month = d.getMonth();
		var year = d.getFullYear();

		switch (type) {
			case "user":
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Type of User</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year + ' Users');
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Users');
				$('#criteria').empty();
				$('#criteria').append(
					'<option value="" disabled="" selected="">Pick type of user</option><option value="all">All</option><option value="verified">Email Verified</option><option value="unverified">Email Unverified</option><option value="active">Active</option><option value="inactive">Inactive</option>'
				);
				break;
			case "loan":
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Type of Loan</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year + ' Loans');
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Loans');
				$('#criteria').empty();
				$('#criteria').append(
					'<option value="" disabled="" selected="">Pick type of loan</option><option value="all">All</option><option value="paid">Paid</option><option value="defaulted">Defaulted</option><option value="pending">Pending</option>'
				);
				break;
			case "transaction":
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Type of Transaction</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year + ' Transactions');
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Transactions');
				$('#criteria').empty();
				$('#criteria').append(
					'<option value="" disabled="" selected="">Pick type of transaction</option><option value="all">All</option><option value="debits">Debits</option><option value="credits">Credits</option>'
				);
				break;
			case "revenue":
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Type of Revenue</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year + ' Revenue');
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Revenue');
				$('#criteria').empty();
				$('#criteria').append(
					'<option value="" disabled="" selected="">Pick type of Revenue Report</option><option value="all">All</option><option value="profit">Profit</option><option value="loss">Loss</option>'
				);
				break;
			case "principal":
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Type of Principal</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year + ' Principal');
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Principal');
				$('#criteria').empty();
				$('#criteria').append(
					'<option value="" disabled="" selected="">Pick type of Principal Report</option><option value="all">All</option>'
				);
				break;
			default:
				$('#type-text').empty();
				$('#type-text').append('<span id="type-text">Resource Criteria</span>');
				$('#doc-title').attr('placeholder', monthNames[d.getMonth()] + ' ' + year);
				$('#doc-name').attr('placeholder', monthNames[d.getMonth()] +'-Document');
				$('#criteria').empty();
				$('#criteria').append('<option value="" disabled="" selected="">Pick Criteria</option>');
				break;
		}

	});
});
