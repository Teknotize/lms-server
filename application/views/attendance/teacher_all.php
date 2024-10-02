<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?= translate('select_ground') ?></h4>
	</header>
	<?php echo form_open($this->uri->uri_string()); ?>
	<div class="panel-body">
		<div class="row mb-sm">
			<?php if (is_superadmin_loggedin()): ?>
				<div class="col-md-4 mb-sm">
					<div class="form-group">
						<label class="control-label"><?= translate('institution') ?> <span class="required">*</span></label>
						<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branchID'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<span class="error"><?= form_error('branch_id') ?></span>
				</div>
			<?php endif; ?>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?= translate('role') ?> <span class="required">*</span></label>
					<?php
					$role_list = $this->app_lib->getRoles();
					echo form_dropdown("staff_role", $role_list, set_value('staff_role'), "class='form-control'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"><?= form_error('staff_role') ?></span>
				</div>
			</div>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
					<label class="control-label">
						<?= translate('date') ?>
					</label>
					<div class="input-group">
						<input type="text" class="form-control" name="date" id='attDate' value="<?= set_value('date', date("Y-m-d")) ?>" />
						<span class="input-group-addon"><i class="fas fa-calendar"></i></span>
					</div>
					<span class="error"><?= form_error('date') ?></span>
				</div>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-offset-10 col-md-2">
				<button type="submit" name="search" value="1" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?= translate('filter') ?></button>
			</div>
		</div>
	</footer>
	<?php echo form_close(); ?>
</section>

<section class="panel appear-animation" data-appear-animation="<?= $global_config['animations'] ?>" data-appear-animation-delay="100">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="table-responsive mb-sm mt-xs">
				<table class="table table-bordered table-hover table-condensed mb-none table-export dataTable">
					<thead>
						<tr>
							<th>#</th>
							<th><?= translate('name') ?></th>
							<th><?= translate('staff_id') ?></th>
							<th><?= translate('Institution') ?></th>
							<th><?= translate('department') ?></th>
							<th><?= translate('role') ?></th>
							<th><?= translate('date') ?></th>
							<th><?= translate('status') ?></th>
							<th><?= translate('remarks') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (count($attendance_list)) {
							foreach ($attendance_list as $key => $row) {
						?>
								<tr>
									<td><?php echo $count++; ?></td>
									<td><?php echo $row['name'] ?></td>
									<td><?php echo isset($row['staff_id']) && !empty($row['staff_id']) ? htmlspecialchars($row['staff_id']) : 'N/A'; ?></td>
									<td><?php echo isset($row['branch_name']) && !empty($row['branch_name']) ? htmlspecialchars($row['branch_name']) : 'N/A'; ?></td>
									<td><?php echo isset($row['department_name']) && !empty($row['department_name']) ? htmlspecialchars($row['department_name']) : 'N/A'; ?></td>
									<td><?php echo isset($row['role_name']) && !empty($row['role_name']) ? htmlspecialchars($row['role_name']) : 'N/A'; ?></td>
									<td><?php echo $row['att_date'] ?></td>
									<td><?php
										switch ($row['att_status']) {
											case 'P':
												echo ('<span class="badge badge-success">Present</span>');
												break;
											case 'H':
												echo ('<span class="badge badge-info">Holiday</span>');
												break;
											case 'A':
												echo ('<span class="badge badge-danger">Absent</span>');
												break;
											case 'L':
												echo ('<span class="badge badge-warning">Late</span>');
												break;
										}
										?></td>
									<td><?php echo isset($row['att_remark']) && !empty($row['att_remark']) ? htmlspecialchars($row['att_remark']) : 'N/A'; ?></td>
								</tr>
						<?php
							}
						} else {
							echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>