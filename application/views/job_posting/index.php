<section class="panel">
	<div class="tabs-custom">
		<div>
			<ul class="nav nav-tabs">
				<li id="list-tab" class="active">
					<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('job_postings') ?></a>
				</li>
				<?php if (get_permission('job_posting', 'is_add')) { ?>
					<li id="create-tab">
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('new_opening') ?></a>
					</li>
				<?php } ?>
				<button style="float: right;" class="btn btn-default btn-circle" id="authentication_btn" onclick="window.location.href='<?= base_url('job_posting/vacant_filled') ?>'">
					<!-- <i class="fas fa-unlock-alt"></i>  -->
					Vacant/Filled </button>
			</ul>
		</div>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table table-bordered table-hover table-condensed mb-none table-export">
					<thead>
						<tr>
							<th>#</th>
							<th><?= translate('institution') ?></th>
							<th><?= translate('title') ?></th>
							<th><?= translate('qualification') ?></th>
							<th><?= translate('experience') ?></th>
							<th><?= translate('contract_type') ?></th>
							<th><?= translate('location') ?></th>
							<th><?= translate('posts') ?></th>
							<th><?= translate('due_date') ?></th>
							<th><?= translate('description') ?></th>
							<th><?= translate('status') ?></th>
							<th><?= translate('created_by') ?></th>
							<th><?= translate('action_by') ?></th>
							<th><?= translate('actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						foreach ($job_postings as $row):
						?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?= $row['branch_name'] ? translate($row['branch_name']) : '---' ?></td>
								<td><?= $row['title'] ? translate($row['title']) : '---' ?></td>
								<td><?= $row['qualification'] ? $row['qualification'] : '---' ?></td>
								<td><?= $row['experience'] ? $row['experience'] : '---' ?></td>
								<td><?= $row['contract_type'] ? translate($row['contract_type']) : '---' ?></td>
								<td><?= $row['location'] ? translate($row['location']) : '---' ?></td>
								<td><?= $row['no_of_posts'] ? $row['no_of_posts'] : '---' ?></td>
								<td><?= $row['due_date'] ? $row['due_date'] : '---' ?></td>
								<td><?= $row['description'] ? $row['description'] : '---' ?></td>
								<td>
									<?php if ($row['status'] == 'filled'): ?>
										<span class="badge badge-success"><?= translate($row['status']) ?></span>
									<?php else: ?>
										<span class="badge badge-warning"><?= translate($row['status']) ?></span>
									<?php endif; ?>
								</td>
								<td><?= $row['created_by_name'] ? $row['created_by_name'] : '---' ?></td>
								<td><?= $row['action_by_name'] ? $row['action_by_name'] : '---' ?></td>

								<td class="min-w-c">
									<?php if (get_permission('award', 'is_edit')) { ?>
										<!--update link-->
										<a href="<?php echo base_url('job_posting/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon">
											<i class="fas fa-pen-nib"></i>
										</a>
									<?php }
									if (get_permission('job_posting', 'is_delete')) { ?>
										<!-- delete link -->
										<?php echo btn_delete('job_posting/delete/' . $row['id']); ?>
									<?php } ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php if (get_permission('job_posting', 'is_add')) { ?>
				<div class="tab-pane" id="create">
					<?php echo form_open_multipart(base_url('job_posting/create'), array('class' => 'form-bordered form-horizontal frm-submit-data')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('institution') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, "", "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('title') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_input(['type' => 'text', 'name' => 'title'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('title') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('qualification') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_input(['type' => 'text', 'name' => 'qualification'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('qualification') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('experience') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_input(['type' => 'text', 'name' => 'experience'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('experience') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('location') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_input(['type' => 'text', 'name' => 'location'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('location') ?></span>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"><?php echo translate('contract_type'); ?></label>
						<div class="col-md-6">
							<?php
							echo form_dropdown("contract_type", get_contract_type(), null, "class='form-control' data-plugin-selectTwo
							data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?= form_error('contract_type') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('no_of_posts') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_input(['type' => 'number', 'name' => 'no_of_posts'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('no_of_posts') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('description') ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?= form_textarea(['name' => 'description'], null, 'class="form-control"') ?>
							<span class="error"><?= form_error('description') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?= translate('due_date') ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<?= form_input(['type' => 'date', 'name' => 'due_date'], null, 'class="form-control" requried') ?>
							<span class="error"><?= form_error('due_date') ?></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing" name="submit" value="create">
									<i class="fas fa-plus-circle"></i> <?= translate('save') ?>
								</button>
							</div>
						</div>
					</footer>
					<?php echo form_close(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<script>
	// $(document).ready(function() {
	// 	// Get the URL fragment
	// 	var hash = window.location.hash;

	// 	// Check if the fragment is '#create'
	// 	if (hash === '#create') {
	// 		// Remove 'active' class from the first tab
	// 		$('#list-tab').removeClass('active');
	// 		// Add 'active' class to the second tab
	// 		$('#create-tab').addClass('active');
	// 		// Remove 'active' class from the first tab content
	// 		$('#list').removeClass('active');
	// 		// Add 'active' class to the second tab content
	// 		$('#create').addClass('active');
	// 	}
	// });
</script>