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
			var branchID = $(this).val();
			TPgetDesignationByBranch(branchID);
			TPgetDepartmentByBranch(branchID);
		});
});

