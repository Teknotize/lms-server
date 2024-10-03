<?php $widget = (is_superadmin_loggedin() ? '4' : '6'); ?>
<div class="row appear-animation" data-appear-animation="<?= $global_config['animations'] ?>">
	<div class="col-md-12 mb-lg">
		<div class="profile-head social">
			<div class="col-md-12 col-lg-4 col-xl-3">
				<div class="image-content-center user-pro">
					<div class="preview">
						<ul class="social-icon-one">
							<li><a href="<?= empty($staff['facebook_url']) ? '#' : $staff['facebook_url'] ?>"><span class="fab fa-facebook-f"></span></a></li>
							<li><a href="<?= empty($staff['twitter_url']) ? '#' : $staff['twitter_url'] ?>"><span class="fab fa-twitter"></span></a></li>
							<li><a href="<?= empty($staff['linkedin_url']) ? '#' : $staff['linkedin_url'] ?>"><span class="fab fa-linkedin-in"></span></a></li>
						</ul>
						<img src="<?= get_image_url('staff', $staff['photo']) ?>">
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-5 col-xl-5">
				<h5><?php echo $staff['name']; ?></h5>
				<p><?php echo ucfirst($staff['role']) ?> / <?php echo $staff['designation_name']; ?></p>
				<ul>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('department') ?>"><i class="fas fa-user-tie"></i></div> <?= (!empty($staff['department_name']) ? $staff['department_name'] : 'N/A'); ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('birthday') ?>"><i class="fas fa-birthday-cake"></i></div> <?= _d($staff['birthday']) ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('joining_date') ?>"><i class="far fa-calendar-alt"></i></div> <?= _d($staff['joining_date']) ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('mobile_no') ?>"><i class="fas fa-phone"></i></div> <?= (!empty($staff['mobileno']) ? $staff['mobileno'] : 'N/A'); ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('email') ?>"><i class="far fa-envelope"></i></div> <?= $staff['email'] ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('present_address') ?>"><i class="fas fa-home"></i></div> <?= (!empty($staff['present_address']) ? $staff['present_address'] : 'N/A'); ?>
					</li>
					<li>
						<div class="icon-holder" data-toggle="tooltip" data-original-title="<?= translate('Total Tenure') ?>"><i class="fas fa-home"></i></div> <?= (!empty($staff['total_tenure']) ? $staff['total_tenure'] : 'N/A'); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="panel-group" id="accordion">
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<div class="auth-pan">
							<button class="btn btn-default btn-circle" id="authentication_btn">
								<i class="fas fa-unlock-alt"></i> <?= translate('authentication') ?>
							</button>
						</div>
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#profile">
							<i class="fas fa-user-edit"></i> <?= translate('basic_details') ?>
						</a>
					</h4>
				</div>
				<div id="profile" class="accordion-body collapse <?= ($this->session->flashdata('profile_tab') ? 'in' : ''); ?>">
					<?php echo form_open_multipart($this->uri->uri_string()); ?>
					<div class="panel-body">
						<fieldset>
							<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff['id']; ?>">
							<!-- academic details-->
							<div class="headers-line">
								<i class="fas fa-school"></i> <?= translate('academic_details') ?>
							</div>
							<div class="row">
								<?php if (is_superadmin_loggedin()) { ?>
									<div class="col-md-4 mb-sm">
										<div class="form-group">
											<label class="control-label"><?= translate('institution') ?> <span class="required">*</span></label>
											<?php
											$arrayBranch = $this->app_lib->getSelectList('branch');
											echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $staff['branch_id']), "class='form-control' id='branch_id'
												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
											?>
											<span class="error"><?php echo form_error('branch_id'); ?></span>
										</div>
									</div>
								<?php } ?>
								<div class="col-md-<?= $widget ?> mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('role') ?> <span class="required">*</span></label>
										<?php
										$role_list = $this->app_lib->getRoles();

										if (!is_superadmin_loggedin()) {
											$key = array_search('Directorate', $role_list);
											if ($key !== false) {
												unset($role_list[$key]);
											}
										}

										echo form_dropdown("user_role", $role_list, set_value('user_role', $staff['role_id']), "class='form-control'
												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
										?>
										<span class="error"><?php echo form_error('user_role'); ?></span>
									</div>
								</div>
								<div class="col-md-<?= $widget ?> mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('joining_date') ?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
											<input type="text" class="form-control" name="joining_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }'
												autocomplete="off" value="<?= set_value('joining_date', $staff['joining_date']) ?>">
										</div>
										<span class="error"><?php echo form_error('joining_date'); ?></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('designation') ?> <span class="required">*</span></label>
										<?php
										$designation_list = $this->app_lib->getDesignation($staff['branch_id']);
										echo form_dropdown("designation_id", $designation_list, set_value('designation_id', $staff['designation']), "class='form-control' id='designation_id'
												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"><?php echo form_error('designation_id'); ?></span>
									</div>
								</div>
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('department') ?> <span class="required">*</span></label>
										<?php
										$department_list = $this->app_lib->getDepartment($staff['branch_id']);
										echo form_dropdown("department_id", $department_list, set_value('department_id', $staff['department']), "class='form-control' id='department_id'
												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"><?php echo form_error('department_id'); ?></span>
									</div>
								</div>
							</div>

							<div class="row mb-lg">

								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('qualification') ?> <span class="required">*</span></label>
										<textarea class="form-control" rows="1" name="qualification"><?= set_value('qualification', $staff['qualification']) ?></textarea>
										<span class="error"><?php echo form_error('qualification'); ?></span>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('experience_details') ?></label>
										<textarea class="form-control" rows="1" name="experience_details"><?= set_value('experience_details', $staff['experience_details']) ?></textarea>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('total_experience') ?></label>
										<input type="text" class="form-control" name="total_experience" value="<?= set_value('total_experience', $staff['total_experience']) ?>" autocomplete="off" />
									</div>
								</div>
							</div>

							<!-- employee details -->
							<div class="headers-line mt-md">
								<i class="fas fa-user-check"></i> <?= translate('employee_details') ?>
							</div>
							<div class="row">
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('staff_id') ?> <span class="required">*</span></label>
										<input class="form-control" name="staff_id_no" type="text" value="<?= set_value('staff_id_no', $staff['staff_id']) ?>" />
										<span class="error"><?php echo form_error('staff_id_no'); ?></span>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('name') ?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-user"></i></span>
											<input class="form-control" name="name" type="text" value="<?= set_value('name', $staff['name']) ?>" />
										</div>
										<span class="error"><?php echo form_error('name'); ?></span>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('gender') ?></label>
										<?php
										$array = array(
											"" => translate('select'),
											"male" => translate('male'),
											"female" => translate('female')
										);
										echo form_dropdown("sex", $array, set_value('sex', $staff['sex']), "class='form-control' data-plugin-selectTwo
												data-width='100%' data-minimum-results-for-search='Infinity'");
										?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('religion') ?></label>
										<input type="text" class="form-control" name="religion" value="<?= set_value('religion', $staff['religion']) ?>">
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('blood_group') ?></label>
										<?php
										$bloodArray = $this->app_lib->getBloodgroup();
										echo form_dropdown("blood_group", $bloodArray, set_value('blood_group', $staff['blood_group']), "class='form-control populate' data-plugin-selectTwo
												data-width='100%' data-minimum-results-for-search='Infinity' ");
										?>
									</div>
								</div>

								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('birthday') ?> </label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
											<input class="form-control" name="birthday" value="<?= set_value('birthday', $staff['birthday']) ?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }' autocomplete="off" type="text">
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('mobile_no') ?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
											<input class="form-control" name="mobile_no" type="text" value="<?= set_value('mobile_no', $staff['mobileno']) ?>" />
										</div>
										<span class="error"><?php echo form_error('mobile_no'); ?></span>
									</div>
								</div>
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('email') ?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
											<input type="text" class="form-control" name="email" id="email" value="<?= set_value('email', $staff['email']) ?>" />
										</div>
										<span class="error"><?php echo form_error('email'); ?></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('present_address') ?> <span class="required">*</span></label>
										<textarea class="form-control" rows="2" name="present_address" placeholder="<?= translate('present_address') ?>"><?= set_value('present_address', $staff['present_address']) ?></textarea>
										<span class="error"><?php echo form_error('present_address'); ?></span>
									</div>
								</div>
								<div class="col-md-6 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('permanent_address') ?></label>
										<textarea class="form-control" rows="2" name="permanent_address" placeholder="<?= translate('permanent_address') ?>"><?= set_value('permanent_address', $staff['permanent_address']) ?></textarea>
									</div>
								</div>
							</div>

							<!--custom fields details-->
							<div class="row" id="customFields">
								<?php echo render_custom_Fields('employee', $staff['branch_id'], $staff['id']); ?>
							</div>

							<div class="row mb-md">
								<div class="col-md-12">
									<div class="form-group">
										<label for="input-file-now"><?= translate('profile_picture') ?></label>
										<input type="file" name="user_photo" class="dropify" data-default-file="<?= get_image_url('staff', $staff['photo']) ?>" />
										<span class="error"><?php echo form_error('user_photo'); ?></span>
									</div>
								</div>
								<input type="hidden" name="old_user_photo" value="<?= $staff['photo'] ?>">
							</div>

							<!-- login details -->
							<div class="headers-line">
								<i class="fas fa-user-lock"></i> <?= translate('login_details') ?>
							</div>

							<div class="row mb-lg">
								<div class="col-md-12 mb-sm">
									<div class="form-group">
										<label class="control-label"><?= translate('username') ?> <span class="required">*</span></label>
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-user"></i></span>
											<input type="text" class="form-control" name="username" value="<?= set_value('username', $staff['username']) ?>" />
										</div>
										<span class="error"><?php echo form_error('username'); ?></span>
									</div>
								</div>
							</div>

							<!-- social links -->
							<div class="headers-line">
								<i class="fas fa-globe"></i> <?= translate('social_links') ?>
							</div>

							<div class="row mb-md">
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label">Facebook</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
											<input type="text" class="form-control" name="facebook" value="<?= set_value('facebook', $staff['facebook_url']) ?>" />
										</div>
										<span class="error"><?php echo form_error('facebook'); ?></span>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label">Twitter</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fab fa-twitter"></i></span>
											<input type="text" class="form-control" name="twitter" value="<?= set_value('twitter', $staff['twitter_url']) ?>" />
										</div>
										<span class="error"><?php echo form_error('twitter'); ?></span>
									</div>
								</div>
								<div class="col-md-4 mb-sm">
									<div class="form-group">
										<label class="control-label">Linkedin</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
											<input type="text" class="form-control" name="linkedin" value="<?= set_value('linkedin', $staff['linkedin_url']) ?>" />
										</div>
										<span class="error"><?php echo form_error('linkedin'); ?></span>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-md-offset-9 col-md-3">
								<button type="submit" name="submit" value="update" class="btn btn-default btn-block"><?= translate('update') ?></button>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#salary_transaction">
							<i class="far fa-address-card"></i> <?= translate('salary_transaction') ?>
						</a>
					</h4>
				</div>
				<div id="salary_transaction" class="accordion-body collapse">
					<div class="panel-body">
						<div class="table-responsive mb-sm mt-xs">
							<table class="table table-bordered table-hover table-condensed mb-md mt-sm">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('month_of_salary') ?></th>
										<th><?= translate('basic_salary') ?></th>
										<th><?= translate('allowances') ?> (+)</th>
										<th><?= translate('deductions') ?> (-)</th>
										<th><?= translate('paid_amount') ?></th>
										<th><?= translate('payment_type') ?></th>
										<th><?= translate('created_at') ?></th>
										<th class="hidden-print"><?= translate('payslip') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$salaryresult = $this->db->get_where("payslip", array('staff_id' => $staff['id']))->result_array();
									if (count($salaryresult)) {
										foreach ($salaryresult as $row):
									?>
											<tr>
												<td><?php echo $count++; ?></td>
												<td><?php echo $this->app_lib->getMonthslist($row['month']) . " / " . $row['year']; ?></td>
												<td><?php echo $global_config['currency_symbol'] . $row['basic_salary']; ?></td>
												<td><?php echo $global_config['currency_symbol'] . $row['total_allowance']; ?></td>
												<td><?php echo $global_config['currency_symbol'] . $row['total_deduction']; ?></td>
												<td><?php echo $global_config['currency_symbol'] . $row['net_salary']; ?></td>
												<td><?php echo get_type_name_by_id('payment_types', $row['pay_via']); ?></td>
												<td><?php echo _d($row['created_at']); ?></td>
												<td class="min-w-c hidden-print">
													<a href="<?php echo base_url('payroll/invoice/' . $row['id'] . "/" . $row['hash']); ?>" class="btn btn-default btn-circle"><i class="fas fa-eye"></i> <?= translate('view') ?></a>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo "<tr><td colspan='9'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#staff_education">
							<i class="fas fa-university"></i> <?= translate('education') ?>
						</a>
					</h4>
				</div>
				<div id="staff_education" class="accordion-body collapse <?= ($this->session->flashdata('staff_education_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffEducationModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('education') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('school') ?></th>
										<th><?= translate('degree') ?></th>
										<th><?= translate('field_of_study') ?></th>
										<th><?= translate('location') ?></th>
										<th><?= translate('start_date') ?></th>
										<th><?= translate('end_date') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$this->db->order_by('id', 'DESC');
									$educationResult = $this->db->get('staff_education')->result_array();
									if (count($educationResult)) {
										foreach ($educationResult as $education):
									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<td><?php echo $education['institute_name']; ?></td>
												<td><?php echo $education['degree']; ?></td>
												<td><?php echo $education['study_field']; ?></td>
												<td><?php echo $education['location']; ?></td>
												<td><?php echo $education['start_date']; ?></td>
												<td><?php echo $education['end_date']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editStaffEducation('<?= $education['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/staff_education_delete/' . $education['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#staff_experience">
							<i class="fas fa-university"></i> <?= translate('experience') ?>
						</a>
					</h4>
				</div>
				<div id="staff_experience" class="accordion-body collapse <?= ($this->session->flashdata('experience_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffExperienceModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('experience') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('title') ?></th>
										<th><?= translate('type') ?></th>
										<th><?= translate('institute_name') ?></th>
										<th><?= translate('location') ?></th>
										<th><?= translate('start_date') ?></th>
										<th><?= translate('end_date') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$this->db->order_by('id', 'DESC');
									$educationResult = $this->db->get('staff_experience')->result_array();
									if (count($educationResult)) {
										foreach ($educationResult as $education):
									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<td><?php echo $education['title']; ?></td>
												<td><?php echo $education['type']; ?></td>
												<td><?php echo $education['institute_name']; ?></td>
												<td><?php echo $education['location']; ?></td>
												<td><?php echo $education['start_date']; ?></td>
												<td><?php echo $education['end_date']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editExperience('<?= $education['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/experience_delete/' . $education['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#staff_spouse">
							<i class="fas fa-university"></i> <?= translate('spouse') ?>
						</a>
					</h4>
				</div>
				<div id="staff_spouse" class="accordion-body collapse <?= ($this->session->flashdata('spouse_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffSpouseModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('spouse') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('name') ?></th>
										<th><?= translate('occupation') ?></th>
										<th><?= translate('contact no') ?></th>
										<th><?= translate('No of child') ?></th>
										<th><?= translate('dependent child') ?></th>
										<th><?= translate('Detail') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$this->db->order_by('id', 'DESC');
									$spousResult = $this->db->get('staff_spouse')->result_array();
									if (count($spousResult)) {
										foreach ($spousResult as $spouse):
									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<td><?php echo $spouse['name']; ?></td>
												<td><?php echo $spouse['occupation']; ?></td>
												<td><?php echo $spouse['phone']; ?></td>
												<td><?php echo $spouse['total_child']; ?></td>
												<td><?php echo $spouse['dependent_child']; ?></td>
												<td><?php echo $spouse['detail']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editspouse('<?= $spouse['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/spouse_delete/' . $spouse['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#job_status">
							<i class="fas fa-university"></i> <?= translate('job_status') ?>
						</a>
					</h4>
				</div>
				<div id="job_status" class="accordion-body collapse <?= ($this->session->flashdata('job_status_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffJobStatusModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('job_status') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('name') ?></th>
										<th><?= translate('employee_id') ?></th>
										<th><?= translate('role') ?></th>
										<th><?= translate('assigned School') ?></th>
										<th><?= translate('Status Type') ?></th>
										<th><?= translate('date_of_joing') ?></th>
										<th><?= translate('date_of_happening') ?></th>
										<th><?= translate('comments') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$CI = &get_instance();
									$CI->load->model('Employee_model'); // Load the model getSingleStaff($id = '')

									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$this->db->order_by('id', 'DESC');
									$jobstatusResult = $this->db->get('staff_job_status')->result_array();
									if (count($jobstatusResult)) {
										foreach ($jobstatusResult as $jobs):

									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<?php
												$staff_id   = $jobs['staff_id']; // Assuming each job has a staff_id
												$staff_data = $CI->Employee_model->getSingleStaff($staff_id);
												$getBranch  = $CI->Employee_model->getBranch($staff['branch_id']);
												// print_r($getBranch); exit;
												?>
												<td><?php echo $staff_data['name']; ?></td>
												<td><?php echo $staff_data['staff_id']; ?></td>
												<td><?php echo $staff_data['role']; ?></td>
												<td><?php echo $getBranch['name']; ?></td>
												<td><?php echo $jobs['type']; ?></td>
												<td><?php echo $staff_data['joining_date']; ?></td>
												<td><?php echo $jobs['status_date']; ?></td>
												<td><?php echo $jobs['comment']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editJobStatusg('<?= $jobs['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/job_status_delete/' . $jobs['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<style>
				.star-rating {
					direction: rtl;
					display: inline-block;
				}

				.star-rating input[type="radio"] {
					display: none;
				}

				.star-rating label {
					font-size: 18px;
					color: #ddd;
					cursor: pointer;
					transition: color 0.3s;
				}

				.star-rating input[type="radio"]:checked~label {
					color: #ffd700;
				}

				.star-rating label:hover,
				.star-rating label:hover~label {
					color: #ffd700;
				}

				.star_color {
					color: darkorange;
				}
			</style>
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#performance">
							<i class="fas fa-university"></i> <?= translate('performance') ?>
						</a>
					</h4>
				</div>
				<div id="performance" class="accordion-body collapse <?= ($this->session->flashdata('performance_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addPerformanceModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('performance') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('employee_id') ?></th>
										<th><?= translate('employee_name') ?></th>
										<th><?= translate('Session') ?>/<?= translate('year') ?></th>
										<th><?= translate('rating_scale') ?></th>
										<th><?= translate('scored') ?></th>
										<th><?= translate('evaluation_date') ?></th>
										<th><?= translate('Approved_by') ?></th>
										<th><?= translate('verification_date') ?></th>
										<th><?= translate('status') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$CI = &get_instance();
									$CI->load->model('Employee_model'); // Load the model getSingleStaff($id = '')

									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$this->db->order_by('id', 'DESC');
									$performanceResult = $this->db->get('staff_performance')->result_array();
									if (count($performanceResult)) {
										$total_star = 0;
										foreach ($performanceResult as $performance):

									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<?php
												$staff_id   = $performance['staff_id']; // Assuming each job has a staff_id
												$staff_data = $CI->Employee_model->getSingleStaff($staff_id);
												$performance_rating =  $CI->Employee_model->staff_row_rating($performance['id']);
												// $pr     = round($performance_rating ['totalScore']);
												$pr     = $performance_rating['totalScore'];
												$p_star = $performance_rating['totalPercentage'];
												$this->db->where('id', $performance['year_id']);
												$year      = $this->db->get('schoolyear')->row_array();
												$getBranch  = $CI->Employee_model->getBranch($staff['branch_id']);

												// $total_star = $performance['academic_achievement'] + $performance['attendance'] + $performance['lesson_planning'] + $performance['personality'] + $performance['school_contribution'] + $performance['documentation'];
												// Example value, replace with your actual value

												$rp = round($p_star);
												//   echo"yes"; print_r($p_star); exit;
												?>
												<td><?php echo $staff_data['staff_id'];  ?></td>
												<td>
													<?php echo $staff_data['name']; ?> <br />
													<?php echo $staff_data['role']; ?>
												</td>
												<td><?php echo $year['school_year']; ?></td>

												<td>

													<?php for ($i = $pr; $i >= 1; $i--): ?>
														<i class="fas fa-star star_color"></i>
													<?php endfor; ?>

													<?php
													if ($pr >= 4) {
														echo "Excellent";
													} elseif ($pr >= 3) {
														echo "Good";
													} elseif ($pr >= 2) {
														echo "Satisfactory";
													} elseif ($pr >= 0) {
														echo "Need Improvement";
													}

													?>
												</td>
												<td><?php echo $rp . "%"; ?></td>
												<?php
												$date_string    = $performance['evaluation_date'];  // Your input date string
												$formatted_date = date('d M Y', strtotime($date_string));  // Format date to "12 Sep 2024"

												?>
												<td><?php echo $formatted_date; ?></td>
												<td>
													<?php
													if ($performance['action_by']) {
														$staff_data = $CI->Employee_model->getSingleStaff($performance['action_by']);
														echo $staff_data['name'];
														echo "<br/>";
														echo $staff_data['role'];
													} else {
														echo "Null";
													}
													?>
												</td>
												<td>
													<?php
													if ($performance['verification_date'] != NULL) {
														$date_string    = $performance['verification_date'];  // Your input date string
														$formatted_date = date('d M Y', strtotime($date_string));  // Format date to "12 Sep 2024"
														echo $formatted_date;
													} else {
														echo "Null";
													}

													?>
												</td>
												<td><?php echo $performance['status']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editPerformance('<?= $performance['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/performance_delete/' . $performance['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#sttaf_education">
							<i class="fas fa-university"></i> <?= translate('bank_account') ?>
						</a>
					</h4>
				</div>
				<div id="sttaf_education" class="accordion-body collapse <?= ($this->session->flashdata('sttaf_education_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addBankModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('add_bank') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('bank_name') ?></th>
										<th><?= translate('account_name') ?></th>
										<th><?= translate('institution') ?></th>
										<th><?= translate('bank_address') ?></th>
										<th><?= translate('ifsc_code') ?></th>
										<th><?= translate('account_no') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$bankResult = $this->db->get('staff_bank_account')->result_array();
									if (count($bankResult)) {
										foreach ($bankResult as $bank):
									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<td><?php echo $bank['bank_name']; ?></td>
												<td><?php echo $bank['holder_name']; ?></td>
												<td><?php echo $bank['bank_branch']; ?></td>
												<td><?php echo $bank['bank_address']; ?></td>
												<td><?php echo $bank['ifsc_code']; ?></td>
												<td><?php echo $bank['account_no']; ?></td>
												<td class="min-w-c">
													<a href="javascript:void(0);" onclick="editStaffBank('<?= $bank['id'] ?>')" class="btn btn-circle icon btn-default">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/bankaccount_delete/' . $bank['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#documents_details">
							<i class="fas fa-folder-open"></i> <?= translate('documents') . " " . translate('details') ?>
						</a>
					</h4>
				</div>
				<div id="documents_details" class="accordion-body collapse <?= ($this->session->flashdata('documents_details') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffDocuments')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?= translate('add') . " " . translate('add_documents') ?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('title') ?></th>
										<th><?= translate('document_type') ?></th>
										<th><?= translate('file') ?></th>
										<th><?= translate('remarks') ?></th>
										<th><?= translate('created_at') ?></th>
										<th><?= translate('actions') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$this->db->where('staff_id', $staff['id']);
									$documents = $this->db->get('staff_documents')->result_array();
									if (count($documents)) {
										foreach ($documents as $row):
									?>
											<tr>
												<td><?php echo $count++ ?></td>
												<td><?php echo $row['title']; ?></td>
												<td><?php echo $categorylist[$row['category_id']]; ?></td>
												<td><?php echo $row['file_name']; ?></td>
												<td><?php echo $row['remarks']; ?></td>
												<td><?php echo _d($row['created_at']); ?></td>
												<td class="min-w-c">
													<a href="<?php echo base_url('employee/documents_download?file=' . $row['enc_name']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('download'); ?>">
														<i class="fas fa-cloud-download-alt"></i>
													</a>
													<a href="javascript:void(0);" class="btn btn-circle icon btn-default" onclick="editDocument('<?= $row['id'] ?>', 'employee')">
														<i class="fas fa-pen-nib"></i>
													</a>
													<?php echo btn_delete('employee/document_delete/' . $row['id']); ?>
												</td>
											</tr>
									<?php
										endforeach;
									} else {
										echo '<tr> <td colspan="7"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php if ($staff['role_id'] == 3): ?>
				<div class="panel panel-accordion">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#class_schedule">
								<i class="fas fa-dna"></i> <?= translate('class_schedule') ?>
							</a>
						</h4>
					</div>
					<div id="class_schedule" class="accordion-body collapse">
						<div class="panel-body">
							<div class="table-responsive mb-sm mt-xs">
								<table class="table table-bordered table-hover table-condensed mb-md mt-sm">
									<thead>
										<tr>
											<th>#</th>
											<th><?= translate('subject') ?></th>
											<th><?= translate('class') ?></th>
											<th><?= translate('section') ?></th>
											<th><?= translate('class_room') ?></th>
											<th><?= translate('time_start') ?></th>
											<th><?= translate('time_end') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 1;
										$schedules = $this->employee_model->get_schedule_by_id($staff['id']);
										if ($schedules->num_rows() > 0) {
											$schedules = $schedules->result();
											foreach ($schedules as $row):
										?>
												<tr>
													<td><?php echo $count++; ?></td>
													<td><?php echo $row->subject_name; ?></td>
													<td><?php echo $row->class_name; ?></td>
													<td><?php echo $row->section_name; ?></td>
													<td><?php echo (empty($row->class_room) ? "N/A" : $row->class_room); ?></td>
													<td><?php echo date("g:i A", strtotime($row->time_start)); ?></td>
													<td><?php echo date("g:i A", strtotime($row->time_end)); ?></td>

												</tr>
											<?php endforeach; ?>
										<?php
										} else {
											echo "<tr><td colspan='7'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#tenure_tracking">
							<i class="fas fa-dna"></i> <?= translate('tenure_tracking') ?>
						</a>
					</h4>
				</div>
				<div id="tenure_tracking" class="accordion-body collapse <?= ($this->session->flashdata('tenure_tracking_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="table-responsive mb-sm mt-xs">

							<table class="table table-bordered table-hover table-condensed mb-md mt-sm">
								<thead>
									<tr>
										<th>#</th>
										<th><?= translate('Institution') ?></th>
										<th><?= translate('Department') ?></th>
										<th><?= translate('Role') ?></th>
										<th><?= translate('effective_from') ?></th>
										<th><?= translate('tenure') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									if (count($tenure_tracking) > 0) {
										foreach ($tenure_tracking as $row):
									?>
											<tr>
												<td><?= $count ?></td>
												<td><?= ($row['branch'] === null) ? '---' : $row['branch'] ?></td>
												<td><?= ($row['department'] === null) ? '---' : $row['department'] ?></td>
												<td><?= ($row['designation'] === null) ? '---' : $row['designation'] ?></td>
												<td><?= $row['effective_from'] ?></td>
												<td><?= $row['tenure'] ?></td>
											</tr>
										<?php
											$count++;
										endforeach;
										?>
									<?php
									} else {
										echo "<tr><td colspan='5'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<?php
			if (get_permission('transfer_posting', 'is_view')) {
			?>
				<div class="panel panel-accordion">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#transfer_posting">
								<i class="fas fa-dna"></i> <?= translate('transfer_posting') ?>
							</a>
						</h4>
					</div>
					<div id="transfer_posting" class="accordion-body collapse <?= ($this->session->flashdata('transfer_posting_tab') == 1 ? 'in' : ''); ?>">
						<div class="panel-body">
							<?php
							if (get_permission('transfer_posting', 'is_add')) {
							?>
								<div class="text-right mb-sm">
									<a href="javascript:void(0);" onclick="mfp_modal('#addTransferPosting')" class="btn btn-circle btn-default mb-sm">
										<i class="fas fa-plus-circle"></i> <?= translate('add_transfer_posting') ?>
									</a>
								</div>
							<?php
							}
							?>

							<div class="table-responsive mb-sm mt-xs">

								<table class="table table-bordered table-hover table-condensed mb-md mt-sm">
									<thead>
										<tr>
											<th>#</th>
											<th><?= translate('requested_role') ?></th>
											<th><?= translate('requested_department') ?></th>
											<th><?= translate('requested_institute') ?></th>
											<th><?= translate('effective_from') ?></th>
											<th><?= translate('status') ?></th>
											<th><?= translate('requested_by') ?></th>
											<th><?= translate('action_at') ?></th>
											<th><?= translate('action_by') ?></th>
											<?php
											if (get_permission('transfer_posting', 'is_edit') || $row['emp_id'] === get_loggedin_user_id()) {
											?>
												<th><?= translate('actions') ?></th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 1;
										if (count($transfer_posting_requests) > 0) {
											foreach ($transfer_posting_requests as $row):
										?>
												<tr>
													<td><?= $count ?></td>
													<td><?= ($row['designation_id'] === '0' || $row['designation_id'] === null) ? '---' : $row['designation'] ?></td>
													<td><?= ($row['new_dep_id'] === '0' || $row['new_dep_id'] === null) ? '---' : $row['department'] ?></td>
													<td><?= ($row['new_branch_id'] === '0' || $row['new_branch_id'] === null) ? '---' : $row['branch'] ?></td>
													<td><?= $row['effective_from'] ?></td>
													<td>
														<?php if ($row['status'] == 'approved'): ?>
															<span class="badge badge-success"><?= translate($row['status']) ?></span>
														<?php elseif ($row['status'] == 'rejected'): ?>
															<span class="badge badge-danger"><?= translate($row['status']) ?></span>
														<?php else: ?>
															<span class="badge badge-warning"><?= translate($row['status']) ?></span>
														<?php endif; ?>
													</td>
													<td><?= ($row['created_by'] === '0' || $row['created_by'] === null) ? '---' : $row['created_by_name'] ?></td>
													<td><?= ($row['action_at'] === null) ? '---' : explode(" ", $row['action_at'])[0] ?>
													</td>
													<td><?= ($row['action_by'] === '0' || $row['action_by'] === null) ? '---' : $row['action_by_name'] ?></td>
													<td class="min-w-c">
														<?php
														if (get_permission('transfer_posting', 'is_edit')) {
														?>
															<a href="<?= base_url('transfer_posting/edit/' . $row['id']) ?>" class="btn btn-circle icon btn-default">
																<i class="fas fa-pen-nib"></i>
															</a>
														<?php
														}
														if ($row['status'] === 'pending') {
														?>
															<button class='btn btn-success icon btn-circle' onclick="approve_reject_model('<?= base_url('transfer_posting/approve_reject/' . $row['id'] . '/approved') ?>','Are you sure?','Do you want to approve this request','Transfer Request Approved.')"><i class='fas fa-check'></i></button>
															<button class='btn btn-danger icon btn-circle' onclick="approve_reject_model('<?= base_url('transfer_posting/approve_reject/' . $row['id'] . '/rejected') ?>','Are you sure?','Do you want to reject this request','Transfer Request Rejected.',true)"><i class='fas fa-times'></i></button>
														<?php
															if ($row['emp_id'] === get_loggedin_user_id()) {
																echo btn_delete('transfer_posting/delete/' . $row['id']);
															}
														}
														?>
													</td>
												</tr>
											<?php
												$count++;
											endforeach;
											?>
										<?php
										} else {
											echo "<tr><td colspan='7'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>

			<?php
			if (get_permission('promotions', 'is_view')) {
			?>
				<div class="panel panel-accordion">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#promotions">
								<i class="fas fa-dna"></i> <?= translate('promotions') ?>
							</a>
						</h4>
					</div>
					<div id="promotions" class="accordion-body collapse <?= ($this->session->flashdata('promotions_tab') == 1 ? 'in' : ''); ?>">
						<div class="panel-body">
							<?php
							if (get_permission('promotions', 'is_add')) {
							?>
								<div class="text-right mb-sm">
									<a href="javascript:void(0);" onclick="mfp_modal('#addPromotions')" class="btn btn-circle btn-default mb-sm">
										<i class="fas fa-plus-circle"></i> <?= translate('add_promotions') ?>
									</a>
								</div>
							<?php
							}
							?>

							<div class="table-responsive mb-sm mt-xs">

								<table class="table table-bordered table-hover table-condensed mb-md mt-sm">
									<thead>
										<tr>
											<th>#</th>
											<th><?= translate('staff_id') ?></th>
											<th><?= translate('requested_department') ?></th>
											<th><?= translate('requested_scale') ?></th>
											<th><?= translate('effective_from') ?></th>
											<th><?= translate('status') ?></th>
											<th><?= translate('requested_by') ?></th>
											<th><?= translate('action_by') ?></th>
											<?php
											if (get_permission('promotions', 'is_edit')) {
											?>
												<th><?= translate('actions') ?></th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 1;
										if (count($staff_promotions) > 0) {
											foreach ($staff_promotions as $row):
										?>
												<tr>
													<td><?= $count ?></td>
													<td><?= $row['staff_id'] ?></td>
													<td><?= ($row['dep_id'] === '0') ? '---' : translate($row['department']) ?></td>
													<td><?= ($row['scale_id'] === '0') ? '---' : translate($row['scale']) ?></td>
													<td><?= $row['effective_from'] ?></td>
													<td>
														<?php if ($row['status'] == 'approved'): ?>
															<span class="badge badge-success"><?= translate($row['status']) ?></span>
														<?php elseif ($row['status'] == 'rejected'): ?>
															<span class="badge badge-danger"><?= translate($row['status']) ?></span>
														<?php else: ?>
															<span class="badge badge-warning"><?= translate($row['status']) ?></span>
														<?php endif; ?>
													</td>
													<td><?= ($row['created_by'] === '0' || $row['created_by'] === null) ? '---' : translate($row['created_by_name']) ?></td>
													<td><?= ($row['action_by'] === '0' || $row['action_by'] === null) ? '---' : translate($row['action_by_name']) ?></td>
													<td class="min-w-c">
														<?php
														if (get_permission('promotions', 'is_edit')) {
														?>
															<a href="<?= base_url('promotions/edit/' . $row['id']) ?>" class="btn btn-circle icon btn-default">
																<i class="fas fa-pen-nib"></i>
															</a>
														<?php
														}
														if ($row['status'] === 'pending') {
														?>
															<button class='btn btn-success icon btn-circle' onclick="approve_reject_model('<?= base_url('promotions/approve_reject/' . $row['id'] . '/approved') ?>','Are you sure?','Do you want to approve this request','Promotion Request Approved.')"><i class='fas fa-check'></i></button>
															<button class='btn btn-danger icon btn-circle' onclick="approve_reject_model('<?= base_url('promotions/approve_reject/' . $row['id'] . '/rejected') ?>','Are you sure?','Do you want to reject this request','Promotion Request Rejected.',true)"><i class='fas fa-times'></i></button>
														<?php
															// if ($row['emp_id'] === get_loggedin_user_id()) {
															// 	echo btn_delete('promotion/delete/' . $row['id']);
															// }
														}
														?>
													</td>
												</tr>
											<?php
												$count++;
											endforeach;
											?>
										<?php
										} else {
											echo "<tr><td colspan='11'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php
if (get_permission('transfer_posting', 'is_add')) {
?>


	<!-- Transfer Posting Add Modal -->
	<div id="addTransferPosting" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<script></script>
		<section class="panel">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?= translate('add_transfer_posting'); ?></h4>
			</div>
			<?php echo form_open_multipart('transfer_posting/create', array('class' => 'form-horizontal frm-submit-data')); ?>
			<div class="panel-body">
				<!--  -->
				<input type="hidden" name="emp_id" required value="<?= $staff['id'] ?>">

				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('current_school') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="hidden" name="current_branch_id" value="<?= $staff['branch_id'] ?>">
						<?= form_dropdown("current_branch_id_disabled", $institutes, $staff['branch_id'], "class='form-control' id='tp_current_branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
						<span class="error"><?= form_error('current_branch_id') ?></span>
					</div>
				</div>

				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('new_school') ?></label>
					<div class="col-md-9">
						<?php
						$new_institutes = $institutes;
						unset($new_institutes[$staff['branch_id']]);
						?>
						<?= form_dropdown("new_branch_id", $new_institutes, null, "class='form-control' id='tp_new_branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'") ?>
						<span class="error"><?= form_error('new_branch_id') ?></span>
					</div>
				</div>

				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('new_role') ?> </label>
					<div class="col-md-9">
						<?php
						$designation_list = $this->app_lib->getDesignation($staff['branch_id']);
						echo form_dropdown("designation_id", $designation_list, null, "class='form-control' id='tp_designation_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?= form_error('designation_id') ?></span>
					</div>
				</div>

				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('current_department') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="hidden" name="current_dep_id" value="<?= $staff['department'] ?>">
						<?= form_dropdown("current_dep_id_disabled", $departments, $staff['department'], "class='form-control' id='current_dep_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
						<span class="error"><?= form_error('current_dep_id') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('new_department') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?= form_dropdown("new_dep_id", $departments, null, "class='form-control' id='tp_department_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required") ?>
						<span class="error"><?= form_error('new_dep_id') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('effective_from') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'date', 'name' => 'effective_from'], null, 'class="form-control" requried') ?>
						<span class="error"><?= form_error('effective_from') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('notes') ?></label>
					<div class="col-md-9">
						<?= form_textarea(['name' => 'notes'], null, 'class="form-control"') ?>
						<span class="error"><?= form_error('notes') ?></span>
					</div>
				</div>
				<!--  -->
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" id="" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
						</button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

<?php
}
?>


<?php
if (get_permission('promotions', 'is_add')) {
?>


	<!-- Transfer Posting Add Modal -->
	<div id="addPromotions" class="zoom-anim-dialog modal-block modal-lg modal-block-primary mfp-hide">
		<script></script>
		<section class="panel">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?= translate('add_promotions'); ?></h4>
			</div>
			<?php echo form_open_multipart('promotions/create', array('class' => 'form-horizontal frm-submit-data')); ?>
			<div class="panel-body">
				<!-- <i class="fas fa-star star_color"></i> -->
				<!--  -->
				<input type="hidden" name="emp_id" required value="<?= $staff['id'] ?>">

				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('employee_id') ?></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'text', 'name' => 'employee_id'], $staff['staff_id'], 'class="form-control" disabled') ?>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('phone') ?></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'text', 'name' => 'phone_number'], $staff['mobileno'], 'class="form-control" disabled') ?>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('current_department') ?></label>
					<div class="col-md-9">
						<?= form_dropdown("current_dep_id_disabled", $departments, $staff['department'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
						<span class="error"><?= form_error('current_dep_id') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('assign_department_(Optional)') ?> </label>
					<div class="col-md-9">
						<?= form_dropdown("new_dep_id", $departments, null, "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'") ?>
						<span class="error"><?= form_error('new_dep_id') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('current_scale') ?> </label>
					<div class="col-md-9">
						<?= form_dropdown("current_scale", $payscales, $promotions['payscale'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' disabled") ?>
						<span class="error"><?= form_error('current_scale') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('promotion_scale') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?= form_dropdown("promotion_scale", $payscales, null, "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required") ?>
						<span class="error"><?= form_error('promotion_scale') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('qualification') ?></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'text', 'name' => 'qualification'], $staff['qualification'], 'class="form-control" disabled') ?>
						<span class="error"><?= form_error('qualification') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('years_of_experience') ?></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'text', 'name' => 'years_of_experience'], null, 'class="form-control" disabled') ?>
						<span class="error"><?= form_error('years_of_experience') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('ratings') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?php
						$rating_value = 'N/A';
						if ($ratings)
							$rating_value = round($ratings['totalScore']) . ' Star - ' . $ratings['totalPercentage'] . '% - ' . $ratings['school_year'];
						?>
						<?= form_input(['type' => 'text', 'name' => 'ratings'], $rating_value, 'class="form-control" disabled') ?>
						<span class="error"><?= form_error('ratings') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('effective_from') ?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?= form_input(['type' => 'date', 'name' => 'effective_from'], null, 'class="form-control" requried') ?>
						<span class="error"><?= form_error('effective_from') ?></span>
					</div>
				</div>
				<div class="form-group mt-md">
					<label class="col-md-3 control-label"><?= translate('notes') ?></label>
					<div class="col-md-9">
						<?= form_textarea(['name' => 'notes'], null, 'class="form-control"') ?>
						<span class="error"><?= form_error('notes') ?></span>
					</div>
				</div>
				<!--  -->
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" id="" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?php echo translate('request'); ?>
						</button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>

<?php
}
?>

<!-- Documents Details Add Modal -->
<div id="addStaffDocuments" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?></h4>
		</div>
		<?php echo form_open_multipart('employee/document_create', array('class' => 'form-horizontal frm-submit-data')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo html_escape($staff['id']); ?>">
			<div class="form-group mt-md">
				<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="document_title" id="adocument_title" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('group'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<?php
					echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo
                        data-width='100%' id='adocument_category' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="file" name="document_file" class="dropify" data-height="110" data-default-file="" id="adocument_file" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
				<div class="col-md-9">
					<textarea class="form-control valid" rows="2" name="remarks"></textarea>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" id="docsavebtn" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Documents Details Edit Modal -->
<div id="editDocModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('document'); ?></h4>
		</div>
		<?php echo form_open_multipart('employee/document_update', array('class' => 'form-horizontal frm-submit-data')); ?>
		<div class="panel-body">
			<input type="hidden" name="document_id" id="edocument_id" value="">
			<div class="form-group mt-md">
				<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="document_title" id="edocument_title" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('group'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<?php
					echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo id='edocument_category'
                            data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="file" name="document_file" class="dropify" data-height="120" data-default-file="">
					<input type="hidden" name="exist_file_name" id="exist_file_name" value="">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
				<div class="col-md-9">
					<textarea class="form-control valid" rows="2" name="remarks" id="edocuments_remarks"></textarea>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="doceditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Bank Details Add Modal -->
<div id="addBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('bank'); ?></h4>
		</div>
		<?php echo form_open('employee/bank_account_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_name" id="abank_name" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="holder_name" id="aholder_name" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('bank_branch'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_branch" id="abank_branch" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="ifsc_code" id="aifsc_code" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="account_no" id="aaccount_no" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_address" id="abank_address" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="bankaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Bank Details Edit Modal -->
<div id="editBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('bank_account'); ?></h4>
		</header>
		<?php echo form_open('employee/bank_account_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="bank_id" id="ebank_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_name" id="ebank_name" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="holder_name" id="eholder_name" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('bank_branch'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_branch" id="ebank_branch" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="ifsc_code" id="eifsc_code" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="account_no" id="eaccount_no" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="bank_address" id="ebank_address" value="" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="bankeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff Education Details Add Modal -->
<div id="addStaffEducationModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('Education'); ?></h4>
		</div>
		<?php echo form_open('employee/staff_education_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('institute') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="institute_name" id="ainstitute_name" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('degree'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="degree" id="adegree" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('study_field'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="study_field" id="astudy_field" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('location'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="location" id="alocation" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('start_date'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="start_date" id="astart_date" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('end_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="end_date" id="end_date" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffeducationaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Staff Education Details Edit Modal -->
<div id="editStaffEducationModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('education'); ?></h4>
		</header>
		<?php echo form_open('employee/staff_education_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_education_id" id="estaff_education_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('institute') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="institute_name" id="einstitute_name" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('degree'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="degree" id="edegree" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('study_field'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="study_field" id="estudy_field" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('location'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="location" id="elocation" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('start_date'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="start_date" id="estart_date" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('end_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="end_date" id="eend_date" value="" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffeducationeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff Education Details Add Modal -->
<div id="addStaffExperienceModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('Experience'); ?></h4>
		</div>
		<?php echo form_open('employee/experience_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">


			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="title" id="atitle" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('type'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="type" id="atype" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('institute') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="institute_name" id="ainstitute_name" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('location'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="location" id="alocation" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('start_date'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="start_date" id="astart_date" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('end_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="end_date" id="end_date" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffeducationaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Staff Education Details Edit Modal -->
<div id="editStaffExperienceModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('experience'); ?></h4>
		</header>
		<?php echo form_open('employee/experience_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_experience_id" id="eestaff_experience_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="title" id="eetitle" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('type'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="type" id="eetype" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('institute') . " " . translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="institute_name" id="eeinstitute_name" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('location'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="location" id="eelocation" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('start_date'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="start_date" id="eestart_date" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('end_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="end_date" id="eeend_date" value="" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffexperienceeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff Education Details Add Modal -->
<div id="addStaffSpouseModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('spouse'); ?></h4>
		</div>
		<?php echo form_open('employee/spouse_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">


			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="name" id="aname" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('occupation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="occupation" id="aoccupation" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('phone'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="phone" id="aphone" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('No Of') . " " . translate('child'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="number" class="form-control" name="total_child" id="atotal_child" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('child_detail_with_name_and _age'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<textarea class="form-control" name="detail" id="detail" rows="5" placeholder="e.g. name = Kamran, age = 25; name = Imrane, age = 12"></textarea>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('No Of dependent'); ?></label>
				<div class="col-md-9">
					<input type="number" class="form-control" name="dependent_child" id="adependent_child" />
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffspouseaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Staff Education Details Edit Modal -->
<div id="editStaffSpouseModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('spouse'); ?></h4>
		</header>
		<?php echo form_open('employee/spouse_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_spouse_id" id="estaff_spouse_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="name" id="ename" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('occupation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="occupation" id="eoccupation" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('phone'); ?> </label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="phone" id="ephone" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('No of child'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="total_child" id="etotal_child" value="" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('No of Dependent'); ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="dependent_child" id="edependent_child" value="" />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('child_detail_with_name_and _age'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<textarea class="form-control" name="detail" id="edetail" rows="5" placeholder="e.g. name = Kamran, age = 25; name = Imrane, age = 12"></textarea>
					<span class="error"></span>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffspouseeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff job status Details Add Modal -->
<div id="addStaffJobStatusModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('job_status'); ?></h4>
		</div>
		<?php echo form_open('employee/job_status_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">


			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('employee_id'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['staff_id']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Role') . " /" . translate('Designation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['role']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Assigned School'); ?></label>
				<div class="col-md-9">
					<?php $getBranch  = $CI->Employee_model->getBranch($staff['branch_id']); ?>
					<input type="text" class="form-control" value="<?= $getBranch['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('date_of_joining'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" value="<?= $staff['joining_date']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('type'); ?></label>
				<div class="col-md-9">
					<?php
					$array = array(
						""                 => translate('select'),
						"In service death" => translate('In service death'),
						"Resigned " => translate('Resigned'),
						"Retired"     => translate('Retired'),
						"Terminated"   => translate('Terminate')
					);
					echo form_dropdown("type", $array, set_value('type'), "class='form-control' data-plugin-selectTwo
							data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('status_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="status_date" id="astatus_date" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('Comments'); ?></label>
				<div class="col-md-9">
					<textarea class="form-control valid" rows="5" name="comment"></textarea>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffjobstatusaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<!-- Staff job status Details Edit Modal -->
<div id="editStaffJobStatusModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('job_status'); ?></h4>
		</header>
		<?php echo form_open('employee/job_status_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_job_status_id" id="estaff_job_status_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('employee_id'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['staff_id']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Role') . " /" . translate('Designation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['role']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Assigned School'); ?></label>
				<?php $getBranch  = $CI->Employee_model->getBranch($staff['branch_id']); ?>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $getBranch['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('date_of_joining'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" value="<?= $staff['joining_date']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('type'); ?></label>
				<div class="col-md-9">
					<?php
					$array = array(
						""                   => translate('select'),
						"In service death"   => translate('In service death'),
						"Resigned " => translate('Resigned'),
						"Retired"   => translate('Retired'),
						"Terminated" => translate('Terminate')
					);

					echo form_dropdown(
						"type", // Name attribute
						$array, // Options array
						'', // No default selected value for now
						"id='jetype' class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'" // Additional attributes
					);
					?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('status_date'); ?></label>
				<div class="col-md-9">
					<input type="date" class="form-control" name="status_date" id="estatus_date" />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label"><?php echo translate('Comments'); ?></label>
				<div class="col-md-9">
					<textarea class="form-control valid" rows="5" name="comment" id="ecomment"></textarea>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-default" id="staffspouseeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<?php echo translate('update'); ?>
					</button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff performance Details Add Modal -->
<div id="addPerformanceModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('Performance'); ?></h4>
		</div>
		<?php echo form_open('employee/performance_create', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<?php $user_id = get_loggedin_user_id();
			$current_datetime = date('Y-m-d H:i:s'); ?>
			<input type="hidden" name="created_by" value="<?php echo $user_id; ?>">
			<input type="hidden" name="evaluation_date" value="<?php echo $current_datetime; ?>">



			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('employee_id'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['staff_id']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Role') . " /" . translate('Designation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" name="role" class="form-control" value="<?= $staff['role']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('session') . " /" . translate('year'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<?php
					$arrayYear = array("" => translate('select'));
					$years = $this->db->get('schoolyear')->result();
					foreach ($years as $year) {
						$arrayYear[$year->id] = $year->school_year;
					}
					echo form_dropdown("year_id", $arrayYear, set_value('year_id', $academic_year), "class='form-control' id='academic_year_id'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"></span>
				</div>
			</div>
			<!-- Academic Achievement -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('academic_achievement'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="academic_star4" name="academic_achievement" value="4" /><label for="academic_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="academic_star3" name="academic_achievement" value="3" /><label for="academic_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="academic_star2" name="academic_achievement" value="2" /><label for="academic_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="academic_star1" name="academic_achievement" value="1" /><label for="academic_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Attendance -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('attendance'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="attendance_star4" name="attendance" value="4" /><label for="attendance_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="attendance_star3" name="attendance" value="3" /><label for="attendance_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="attendance_star2" name="attendance" value="2" /><label for="attendance_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="attendance_star1" name="attendance" value="1" /><label for="attendance_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Lesson Planning -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('lesson_planning'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="lesson_star4" name="lesson_planning" value="4" /><label for="lesson_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="lesson_star3" name="lesson_planning" value="3" /><label for="lesson_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="lesson_star2" name="lesson_planning" value="2" /><label for="lesson_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="lesson_star1" name="lesson_planning" value="1" /><label for="lesson_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Personality -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('personality'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="personality_star4" name="personality" value="4" /><label for="personality_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="personality_star3" name="personality" value="3" /><label for="personality_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="personality_star2" name="personality" value="2" /><label for="personality_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="personality_star1" name="personality" value="1" /><label for="personality_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- School Contribution -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('school_contribution'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="contribution_star4" name="school_contribution" value="4" /><label for="contribution_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="contribution_star3" name="school_contribution" value="3" /><label for="contribution_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="contribution_star2" name="school_contribution" value="2" /><label for="contribution_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="contribution_star1" name="school_contribution" value="1" /><label for="contribution_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Documentation -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('documentation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">

						<input type="radio" id="documentation_star4" name="rating_documentation" value="4" /><label for="documentation_star4" title="4 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="documentation_star3" name="rating_documentation" value="3" /><label for="documentation_star3" title="3 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="documentation_star2" name="rating_documentation" value="2" /><label for="documentation_star2" title="2 stars"><i class="fas fa-star"></i></label>
						<input type="radio" id="documentation_star1" name="rating_documentation" value="1" /><label for="documentation_star1" title="1 star"><i class="fas fa-star"></i></label>
					</div>
					<span class="error"></span>
				</div>
			</div>

		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Staff performance Details Edit Modal -->

<div id="editPerformanceModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('job_status'); ?></h4>
		</header>
		<?php echo form_open('employee/performance_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_performance_id" id="estaff_performance_id" value="">
			<input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
			<?php $user_id  = get_loggedin_user_id();
			$current_datetime = date('Y-m-d H:i:s'); ?>
			<input type="hidden" name="action_by" value="<?php echo $user_id; ?>">
			<input type="hidden" name="verification_date" value="<?php echo $current_datetime; ?>">
			<!-- Staff Name -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['name']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>

			<!-- Employee ID -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('employee_id'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['staff_id']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>

			<!-- Role/Designation -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Role') . " /" . translate('Designation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="<?= $staff['role']; ?>" readonly />
					<span class="error"></span>
				</div>
			</div>

			<!-- Session / Year -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('session') . " /" . translate('year'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<?php
					$arrayYear = array("" => translate('select'));
					$years = $this->db->get('schoolyear')->result();
					foreach ($years as $year) {
						$arrayYear[$year->id] = $year->school_year;
					}
					echo form_dropdown("year_id", $arrayYear, set_value('year_id', $academic_year), "class='form-control' id='peacademic_year_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('status'); ?></label>
				<div class="col-md-9">
					<?php
					// Define the options for the status dropdown
					$statusOptions = array(
						"" => translate('select'),  // Default 'select' option
						"pending" => translate('pending'),
						"approved" => translate('approved'),
						"rejected" => translate('rejected')
					);

					// Use form_dropdown to generate the select field
					echo form_dropdown(
						"status",                    // Name attribute
						$statusOptions,              // Options array
						set_value('status', $status), // Preselected value
						"class='form-control' id='epstatus' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'"
					);
					?>
					<span class="error"></span>
				</div>
			</div>

			<!-- Academic Achievement -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('academic_achievement'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="academic_star<?= $i; ?>_edit" name="academic_achievement" value="<?= $i; ?>" />
							<label for="academic_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Attendance -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('attendance'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="attendance_star<?= $i; ?>_edit" name="attendance" value="<?= $i; ?>" />
							<label for="attendance_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Lesson Planning -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('lesson_planning'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="lesson_star<?= $i; ?>_edit" name="lesson_planning" value="<?= $i; ?>" />
							<label for="lesson_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Personality -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('personality'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="personality_star<?= $i; ?>_edit" name="personality" value="<?= $i; ?>" />
							<label for="personality_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- School Contribution -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('school_contribution'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="contribution_star<?= $i; ?>_edit" name="school_contribution" value="<?= $i; ?>" />
							<label for="contribution_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

			<!-- Documentation -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('documentation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="star-rating">
						<?php for ($i = 4; $i >= 1; $i--): ?>
							<input type="radio" id="documentation_star<?= $i; ?>_edit" name="documentation" value="<?= $i; ?>" />
							<label for="documentation_star<?= $i; ?>_edit" title="<?= $i; ?> stars"><i class="fas fa-star"></i></label>
						<?php endfor; ?>
					</div>
					<span class="error"></span>
				</div>
			</div>

		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary"><?php echo translate('save'); ?></button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- login authentication and account inactive modal -->
<div id="authentication_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="fas fa-unlock-alt"></i> <?= translate('authentication') ?>
			</h4>
		</header>
		<?php echo form_open('employee/change_password', array('class' => 'frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
			<div class="form-group">
				<label for="password" class="control-label"><?= translate('password') ?> <span class="required">*</span></label>
				<div class="input-group">
					<input type="password" class="form-control password" name="password" autocomplete="off" />
					<span class="input-group-addon">
						<a href="javascript:void(0);" id="showPassword"><i class="fas fa-eye"></i></a>
					</span>
				</div>
				<span class="error"></span>
				<div class="checkbox-replace mt-lg">
					<label class="i-checks">
						<input type="checkbox" name="authentication" id="cb_authentication">
						<i></i> <?= translate('login_authentication_deactivate') ?>
					</label>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="text-right">
				<button type="submit" class="btn btn-default mr-xs" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?= translate('update') ?></button>
				<button class="btn btn-default modal-dismiss"><?= translate('close') ?></button>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
	var authenStatus = "<?= $staff['active'] ?>";
</script>