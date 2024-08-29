console.log('Transfer Posting Module');
$(document).ready(function() {
    console.log('Transfer Posting Module');
		function TPgetDesignationByBranch(id) {
			$.ajax({
				url: base_url + 'ajax/getDataByBranch',
				type: 'POST',
				data: {
					table: "staff_designation",
					branch_id: id
				},
				success: function (response) {
					$('#tp_designation_id').html(response);
				}
			});
		}

		function TPgetDepartmentByBranch(id) {
			$.ajax({
				url: base_url + 'ajax/getDataByBranch',
				type: 'POST',
				data: {
					table: "staff_department",
					branch_id: id
				},
				success: function (response) {
					$('#tp_department_id').html(response);
				}
			});
		}
		// Transfer Posting Module
		$('#tp_new_branch_id').on('change', function() {
			var branchID = $(this).val() ? $(this).val() : $('#tp_current_branch_id').val();
			TPgetDesignationByBranch(branchID);
			TPgetDepartmentByBranch(branchID);
		});


	});
	
	
	// 	function approve_reject_model(url, title, text, successText) {
	// 	swal({
	// 		title: title,
	// 		text: text,
	// 		type: "warning",
	// 		showCancelButton: true,
	// 		confirmButtonClass: "btn btn-default swal2-btn-default",
	// 		cancelButtonClass: "btn btn-default swal2-btn-default",
	// 		confirmButtonText: "Yes Continue",
	// 		cancelButtonText: "Cancel",
	// 		buttonsStyling: false,
	// 		footer: false,
	// 	}).then((result) => {
	// 		if (result.value) {
	// 			$.ajax({
	// 				url: url,
	// 				type: "POST",
	// 				success: function(data) {
	// 					console.log(data.status);
	// 					if (data.status === "success") {
	// 						swal({
	// 							title: "Success",
	// 							text: successText,
	// 							buttonsStyling: false,
	// 							showCloseButton: true,
	// 							focusConfirm: false,
	// 							confirmButtonClass: "btn btn-default swal2-btn-default",
	// 							type: "success"
	// 						}).then((result) => {
	// 							if (result.value) {
	// 								location.reload();
	// 							}
	// 						});
	// 					} else {
	// 						swal({
	// 							title: "Failed",
	// 							text: "An error occurred. Please try again.",
	// 							buttonsStyling: false,
	// 							showCloseButton: true,
	// 							focusConfirm: false,
	// 							confirmButtonClass: "btn btn-default swal2-btn-default",
	// 							type: "error"
	// 						});
	// 					}
	// 				},
	// 				error: function() {
	// 					swal({
	// 						title: "Failed",
	// 						text: "An error occurred. Please try again.",
	// 						buttonsStyling: false,
	// 						showCloseButton: true,
	// 						focusConfirm: false,
	// 						confirmButtonClass: "btn btn-default swal2-btn-default",
	// 						type: "error"
	// 					});
	// 				}
	// 			});
	// 		}
	// 	});
	// }