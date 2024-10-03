<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<?php
			$this->db->where_not_in('id', array(1, 6, 7));

			if (!is_superadmin_loggedin()) {
				$this->db->where('name !=', 'Directorate');
			}
			$roles = $this->db->get('roles')->result();
			foreach ($roles as $role) {
			?>
				<li class="<?php if ($role->id == $act_role) echo 'active'; ?>">
					<a href="<?php echo base_url('employee/view/' . $role->id); ?>">
						<i class="far fa-user-circle"></i> <?php echo $role->name ?>
					</a>
				</li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active">
				<div class="export_title"><?php echo translate('employee') . " " . translate('list'); ?></div>
				<table class="table table-bordered table-hover table-condensed table-export" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th class="no-sort"><?php echo translate('photo'); ?></th>
							<th><?php echo translate('institution'); ?></th>
							<th><?php echo translate('staff_id'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('designation'); ?></th>
							<th><?php echo translate('department'); ?></th>
							<th><?php echo translate('email'); ?></th>
							<th><?php echo translate('mobile_no'); ?></th>
							<th><?php echo translate('job_status'); ?></th>
							<?php
							if (!is_superadmin_loggedin()) {
								$show_custom_fields = custom_form_table('employee', get_loggedin_branch_id());
								if (count($show_custom_fields)) {
									foreach ($show_custom_fields as $fields) {
							?>
										<th><?= $fields['field_label'] ?></th>
							<?php }
								}
							} ?>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1;
						foreach ($stafflist as $row): ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td class="center">
									<img src="<?php echo get_image_url('staff', $row->photo); ?>" height="50" />
								</td>
								<td><?php echo get_type_name_by_id('branch', $row->branch_id); ?></td>
								<td><?php echo $row->staff_id; ?></td>
								<td><?php echo $row->name; ?></td>
								<td><?php echo $row->designation_name; ?></td>
								<td><?php echo $row->department_name; ?></td>
								<td><?php echo $row->email; ?></td>
								<td><?php echo $row->mobileno; ?></td>
								<?php
								$color = '';
								$this->db->where('staff_id', $row->id);
								$this->db->order_by('id', 'DESC');
								$jobstatusResult = $this->db->get('staff_job_status')->row_array();

								$this->db->where('user_id', $row->id);
								$this->db->order_by('id', 'DESC');
								$login_res = $this->db->get('login_credential')->row_array();
								if ($login_res['active'] == 1) {
									$status = "Active";
								} else {
									$status = "Deactive";
									$color = "red";
								}

								if ($jobstatusResult) {
									$status = $jobstatusResult['type'];
									$color = "red";
								} else {
									$status = $status . '';
								}


								?>
								<td style="color: <?php echo $color; ?>;">
									<?php echo $status; ?>
								</td>
								<?php
								if (!is_superadmin_loggedin()) {
									if (count($show_custom_fields)) {
										foreach ($show_custom_fields as $fields) {
								?>
											<td><?php echo get_table_custom_field_value($fields['id'], $row->id); ?></td>
								<?php }
									}
								} ?>
								<td class="min-w-c">
									<?php if (get_permission('employee', 'is_edit')): ?>
										<a href="<?php echo base_url('employee/profile/' . $row->id); ?>" class="btn btn-circle btn-default icon" data-toggle="tooltip"
											data-original-title="<?= translate('profile') ?>">
											<i class="far fa-arrow-alt-circle-right"></i>
										</a>
									<?php endif;
									if (get_permission('employee', 'is_delete')): ?>
										<?php echo btn_delete('employee/delete/' . $row->id); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>