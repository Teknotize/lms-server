<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<section class="panel">
	<header class="panel-heading panel-heading-with-button">
		<h4 class="panel-title"><?= translate('select_ground') ?></h4>
		<div class="text-right mb-sm">
			<a href="<?= base_url('attendance/all/student') ?>" class="btn btn-circle btn-default mb-sm">
				<i class="fas fa-plus-circle"></i> All Attendance </a>
		</div>
	</header>
	<?php echo form_open($this->uri->uri_string()); ?>
	<div class="panel-body">
		<div class="row mb-sm">
			<?php if (is_superadmin_loggedin()): ?>
				<div class="col-md-3 mb-sm">
					<div class="form-group">
						<label class="control-label"><?= translate('branch') ?> <span class="required">*</span></label>
						<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)' id='branchID'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<span class="error"><?= form_error('branch_id') ?></span>
				</div>
			<?php endif; ?>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?= translate('class') ?> <span class="required">*</span></label>
					<?php
					$arrayClass = $this->app_lib->getClass($branch_id);
					echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
					 	data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"><?= form_error('class_id') ?></span>
				</div>
			</div>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?= translate('section') ?> <span class="required">*</span></label>
					<?php
					$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
					echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"><?= form_error('section_id') ?></span>
				</div>
			</div>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
					<label class="control-label"><?= translate('date') ?> <span class="required">*</span></label>
					<div class="input-group">
						<input type="text" class="form-control" name="date" id="attDate" value="<?= set_value('date', date("Y-m-d")) ?>" autocomplete="off" />
						<span class="input-group-addon"><i class="icon-event icons"></i></span>
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
							<th><?= translate('date') ?></th>
							<th><?= translate('Institution') ?></th>
							<th><?= translate('Class') ?></th>
							<th><?= translate('Section') ?></th>
							<th><?= translate('roll') ?></th>
							<th><?= translate('register_no') ?></th>
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
									<td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
									<td><?php echo $row['date'] ?></td>
									<td><?php echo isset($row['branch']) && !empty($row['branch']) ? htmlspecialchars($row['branch']) : 'N/A'; ?></td>
									<td><?php echo isset($row['class']) && !empty($row['class']) ? htmlspecialchars($row['class']) : 'N/A'; ?></td>
									<td><?php echo isset($row['section']) && !empty($row['section']) ? htmlspecialchars($row['section']) : 'N/A'; ?></td>
									<td><?php echo isset($row['roll']) && !empty($row['roll']) ? htmlspecialchars($row['roll']) : 'N/A'; ?></td>
									<td><?php echo isset($row['register_no']) && !empty($row['register_no']) ? htmlspecialchars($row['register_no']) : 'N/A'; ?></td>
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