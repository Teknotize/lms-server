
<style>
    .star-rating {
        direction: rtl;
        display: inline-block;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 24px;
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
<div class="panel-body">
    <div class="text-right mb-sm">
         
    </div>
    <div class="table-responsive mb-md"> 
        <table class="table table-bordered table-hover table-condensed mb-none table-export">
            <thead>
                <tr>
                    <th>#</th> 
                    <th><?= translate('institution') ?></th>
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
                $this->db->order_by('id', 'DESC');
                $performanceResult = $this->db->get('staff_performance')->result_array();
                if (count($performanceResult)) {
                    $total_star = 0;
                    foreach ($performanceResult as $performance):

                ?>
                        <tr>
                            <td><?php echo $count++ ?></td>
                            <?php
                            $staff_id           = $performance['staff_id']; // Assuming each job has a staff_id
                            $staff_data         = $CI->Employee_model->getSingleStaff($staff_id);
                            $branch_data        = $CI->Employee_model->getSingleBranch($staff_data['branch_id']);
                            $performance_rating =  $CI->Employee_model->staff_row_rating($performance['id']);
                            // $pr     = round($performance_rating ['totalScore']);
                            $pr     = $performance_rating ['totalScore'];
                            $p_star = $performance_rating ['totalPercentage'];
                            $this->db->where('id', $performance['year_id']);
                            $year      = $this->db->get('schoolyear')->row_array();
                            $getBranch  = $CI->Employee_model->getBranch($staff['branch_id']);

                            // $total_star = $performance['academic_achievement'] + $performance['attendance'] + $performance['lesson_planning'] + $performance['personality'] + $performance['school_contribution'] + $performance['documentation'];
                            // Example value, replace with your actual value
                                
                            $rp = round($p_star);
                            //   echo"yes"; print_r($p_star); exit;
                            ?>
                            <td><?php echo $branch_data['name'];  ?></td>
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
                            <td><?php echo $rp."%"; ?></td> 
                            <?php 
                                $date_string    = $performance['evaluation_date'];  // Your input date string
                                $formatted_date = date('d M Y', strtotime($date_string));  // Format date to "12 Sep 2024"
                                
                            ?>
                            <td><?php echo $formatted_date; ?></td>
                            <td>
                                <?php 
                                if($performance['action_by']){
                                    $staff_data = $CI->Employee_model->getSingleStaff($performance['action_by']);  
                                    echo $staff_data['name'];   echo "<br/>";
                                    echo $staff_data['role']; 
                                }else{
                                    echo "Null";
                                }
                                ?>
                            </td>
                            <td>
                            <?php 
                                if($performance['verification_date']!=NULL){
                                    $date_string    = $performance['verification_date'];  // Your input date string
                                    $formatted_date = date('d M Y', strtotime($date_string));  // Format date to "12 Sep 2024"
                                    echo $formatted_date; 
                                }else{
                                    echo "Null";
                                }
                                
                                ?>
                                </td>
                            <td><?php echo $performance['status']; ?></td>
                            <td class="min-w-c">
                                <a href="javascript:void(0);" onclick="adminEditPerformance('<?= $performance['id'] ?>')" class="btn btn-circle icon btn-default">
                                    <i class="fas fa-pen-nib"></i>
                                    Edit & Approve
                                </a>
                                 
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

 

<!-- Staff performance Details Edit Modal -->

<div id="editPerformanceModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('job_status'); ?></h4>
		</header>
		<?php echo form_open('employee/performance_update', array('class' => 'form-horizontal frm-submit')); ?>
		<div class="panel-body">
			<input type="hidden" name="staff_performance_id" id="estaff_performance_id" value="">  
			<input type="hidden" name="staff_id" id="updatestaff_id" value="">  
			<?php $user_id  = get_loggedin_user_id();  $current_datetime = date('Y-m-d H:i:s'); ?>
			<input type="hidden" name="action_by" value="<?php echo $user_id ; ?>"> 
			<input type="hidden" name="verification_date" value="<?php echo $current_datetime ; ?>"> 
			<!-- Staff Name -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control"  id="estaff_name" value="" readonly />
					<span class="error"></span>
				</div>
			</div>

			<!-- Employee ID -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('employee_id'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="estaff_id" value=""  readonly />
					<span class="error"></span>
				</div>
			</div>

			<!-- Role/Designation -->
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"><?php echo translate('Role') . " /" . translate('Designation'); ?> <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text"  name="role" class="form-control" id="estaff_role"  readonly />
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