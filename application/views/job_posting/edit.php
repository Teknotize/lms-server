<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?= base_url('job_posting/index') ?>"><i class="fas fa-list-ul"></i> <?= translate('job_posts') . ' ' . translate('list') ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('edit_job_post') ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
				<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit-data')); ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('institution') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, $job_post['branch_id'], "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('title') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?= form_input(['type' => 'text', 'name' => 'title'], $job_post['title'], 'class="form-control" requried') ?>
						<span class="error"><?= form_error('title') ?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('qualification') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?= form_input(['type' => 'text', 'name' => 'qualification'], $job_post['qualification'], 'class="form-control" requried') ?>
						<span class="error"><?= form_error('qualification') ?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('experience') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?= form_input(['type' => 'text', 'name' => 'experience'], $job_post['experience'], 'class="form-control" requried') ?>
						<span class="error"><?= form_error('experience') ?></span>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"><?php echo translate('contract_type'); ?></label>
					<div class="col-md-6">
						<?php
						echo form_dropdown("contract_type", get_contract_type(), $job_post['contract_type'], "class='form-control' data-plugin-selectTwo
							data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?= form_error('contract_type') ?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('no_of_posts') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?= form_input(['type' => 'number', 'name' => 'no_of_posts'], $job_post['no_of_posts'], 'class="form-control" requried') ?>
						<span class="error"><?= form_error('no_of_posts') ?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('description') ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?= form_textarea(['name' => 'description'], $job_post['description'], 'class="form-control"') ?>
						<span class="error"><?= form_error('description') ?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?= translate('due_date') ?> <span class="required">*</span></label>
					<div class="col-md-6 mb-md">
						<?= form_input(['type' => 'date', 'name' => 'due_date'], $job_post['due_date'], 'class="form-control" requried') ?>
						<span class="error"><?= form_error('due_date') ?></span>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"><?php echo translate('status'); ?></label>
					<div class="col-md-6">
						<?php
						echo form_dropdown("status", $status, $job_post['status'], "class='form-control' data-plugin-selectTwo
							data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?= form_error('status') ?></span>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-3 col-md-2">
							<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing" name="submit" value="update">
								<i class="fas fa-plus-circle"></i> <?= translate('save') ?>
							</button>
						</div>
					</div>
				</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$('#class_id').on('change', function() {
			var class_id = $(this).val();
			var branch_id = ($("#branch_id").length ? $('#branch_id').val() : "");
			$.ajax({
				url: base_url + 'ajax/getStudentByClass',
				type: 'POST',
				data: {
					branch_id: branch_id,
					class_id: class_id
				},
				success: function(data) {
					$('#user_id').html(data);
				}
			});
		});
	});

	function getStafflistRole() {
		$('#user_id').html('');
		$('#user_id').append('<option value=""><?= translate('select') ?></option>');
		var user_role = $('#role_id').val();
		var branch_id = ($("#branch_id").length ? $('#branch_id').val() : "");
		$.ajax({
			url: base_url + 'leave/getCategory',
			type: "POST",
			data: {
				role_id: user_role,
				branch_id: branch_id
			},
			success: function(data) {
				$('#leave_category').html(data);
			}
		});

		if (user_role != "") {
			if (user_role == 7) {
				$("#classDiv").show("slow");
				$.ajax({
					url: base_url + 'ajax/getClassByBranch',
					type: "POST",
					data: {
						branch_id: branch_id
					},
					success: function(data) {
						$('#class_id').html(data);
					}
				});
			} else {
				$("#classDiv").hide("slow");
				$.ajax({
					url: base_url + 'ajax/getStafflistRole',
					type: "POST",
					data: {
						role_id: user_role,
						branch_id: branch_id
					},
					success: function(data) {
						$('#user_id').html(data);
					}
				});
			}
		}
	}
</script>