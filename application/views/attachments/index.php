<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#attachments" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('attachments') ?></a>
					</li>
					<?php if (get_permission('attachments', 'is_add')) : ?>
						<li>
							<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('create_attachments') ?></a>
						</li>
					<?php endif; ?>
				</ul>
				<div class="tab-content">
					<div id="attachments" class="tab-pane active">
						<div class="mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none table-export">
								<thead>
									<tr>
										<th><?= translate('sl') ?></th>
										<?php if (is_superadmin_loggedin()) { ?>
											<th><?= translate('institution') ?></th>
										<?php } ?>
										<th><?= translate('title') ?></th>
										<th><?= translate('type') ?></th>
										<th><?= translate('class') ?></th>
										<th><?= translate('subject') ?></th>
										<!-- <th><?= translate('LMS Grade') ?></th> -->
										<!-- <th><?= translate('LMS Subject') ?></th> -->
										<th><?= translate('description') ?></th>
										<th><?= translate('publisher') ?></th>
										<th><?= translate('date') ?></th>
										<th><?= translate('action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $count = 1;
									foreach ($attachmentss as $row) : ?>
										<tr>
											<td><?php echo $count++; ?></td>
											<?php if (is_superadmin_loggedin()) { ?>
												<td><?php echo (empty($row['branch_name']) ? '<span class="text-dark">All</span>' : $row['branch_name']); ?></td>
											<?php } ?>
											<td><?php echo $row['title']; ?></td>
											<td><?php echo (empty($row['type_name']) ? '<span class="text-dark">Unfiltered</span>' : $row['type_name']); ?></td>
											<td><?php echo (empty($row['class_name']) ? '<span class="text-dark">All</span>' : $row['class_name']); ?></td>
											<td><?php echo (empty($row['subject_name']) ? '<span class="text-dark">Unfiltered</span>' : $row['subject_name']); ?></td>
											<!-- <td><?php echo $row['lms_grade_name']; ?></td> -->
											<!-- <td><?php echo $row['lms_subject_name']; ?></td> -->
											<td><?php echo $row['remarks']; ?></td>
											<td><?php echo get_type_name_by_id('staff', $row['uploader_id']); ?></td>
											<td><?php echo _d($row['date']); ?></td>
											<td class="action">
												<?php
												$extension = strtolower(pathinfo($row['enc_name'], PATHINFO_EXTENSION));
												if ($extension == 'mp4') {
												?>
													<a href="javascript:void(0);" onclick="playVideo('<?= $row['id'] ?>');" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?= translate('play') ?>">
														<i class="far fa-play-circle"></i>
													</a>
												<?php  } ?>
												<!--download link-->
												<a href="<?= base_url('attachments/download?file=' . $row['enc_name']) ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?= translate('download') ?>">
													<i class="fas fa-cloud-download-alt"></i>
												</a>
												<?php if (get_permission('attachments', 'is_edit')) : ?>

													<?php if ($row['branch_id'] == 'unfiltered') { ?>
														<?php if (is_superadmin_loggedin()) { ?>
															<!--update link-->
															<a href="<?= base_url('attachments/edit/' . $row['id']) ?>" class="btn btn-default btn-circle icon">
																<i class="fas fa-pen-nib"></i>
															</a>
														<?php } ?>
													<?php } else { ?>
														<!--update link-->
														<a href="<?= base_url('attachments/edit/' . $row['id']) ?>" class="btn btn-default btn-circle icon">
															<i class="fas fa-pen-nib"></i>
														</a>
													<?php } ?>
												<?php endif;
												if (get_permission('attachments', 'is_delete')) : ?>
													<?php if ($row['branch_id'] == 'unfiltered') { ?>
														<?php if (is_superadmin_loggedin()) { ?>
															<!-- delete link -->
															<?php echo btn_delete('attachments/delete/' . $row['id']); ?>
														<?php } ?>
													<?php } else { ?>
														<!-- delete link -->
														<?php echo btn_delete('attachments/delete/' . $row['id']); ?>
													<?php } ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="create">
						<?php echo form_open('attachments/save', array('class' => 'form-bordered form-horizontal frm-submit-data')); ?>
						<?php if (is_superadmin_loggedin()) : ?>
							<div class="form-group">
								<label class="control-label col-md-3"><?= translate('institution') ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									// Add "All" option as the second element of the array
									//$allOption = array('all_branches' => 'All');
									//$arrayBranch = array_slice($arrayBranch, 0, 1, true) + $allOption + array_slice($arrayBranch, 1, count($arrayBranch) - 1, true);
									//print_r($arrayBranch);
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"></span>
								</div>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= translate('title') ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="title" value="<?= set_value('title') ?>" />
								<span class="error"></span>
							</div>
						</div>

						<div style="margin-bottom:30px;" id="show_when_branch">

							<?php if (is_superadmin_loggedin()) : ?>
								<div class="form-group">
									<label class="control-label col-md-3"><?= translate('LMS Grade') ?> <span class="required">*</span></label>
									<div class="col-md-6">
										<?php
										$arrayLmsGrade = $this->app_lib->getSelectListByColName('lms_grades', null, 'grade_name');
										echo form_dropdown("lms_grade_id", $arrayLmsGrade, set_value('lms_grade_id'), "class='form-control' id='lms_grade_id'
    										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3"><?= translate('LMS Subject') ?> <span class="required">*</span></label>
									<div class="col-md-6">
										<?php
										$arrayLmsSubject = $this->app_lib->getSelectListByColName('lms_subjects', null, 'subject_name');
										echo form_dropdown("lms_subject_id", $arrayLmsSubject, set_value('lms_subject_id'), "class='form-control' id='lms_subject_id'
    										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"></span>
									</div>
								</div>

							<?php endif; ?>


						</div>

						<div style="margin-bottom:10px;" id="type_div">
							<div class="form-group">
								<label class="control-label col-md-3"><?= translate('type') ?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
									$arrayType = $this->app_lib->getSelectByBranch('attachments_type', $branch_id);
									echo form_dropdown("type_id", $arrayType, set_value('type_id'), "class='form-control' id='type_id' data-plugin-selectTwo
    										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
						</div>
						<div style="margin-bottom:5px;" id="remove_when_branch">
							<div class="form-group">
								<div class="col-md-offset-3">
									<div class="ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="all_class_set" id="all_class_set"><i></i> Available For All Classes</label>
									</div>
								</div>
								<div id="class_div">
									<div class="mt-sm">
										<label class="control-label col-md-3"><?= translate('class') ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<?php
											$arrayClass = $this->app_lib->getClass($branch_id);
											echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSubjectByClass(this.value)'
    												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
											?>
											<span class="error"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group" id="sub_div">
								<div class="col-md-offset-3">
									<div class="ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="subject_wise" id="subject_wise"><i></i> Not According Subject</label>
									</div>
								</div>
								<div id="subject_div">
									<div class="mt-sm">
										<label class="control-label col-md-3"><?= translate('subject') ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<?php
											$arraySubject = array("" => translate('select_class_first'));
											echo form_dropdown("subject_id", $arraySubject, set_value('subject_id'), "class='form-control' id='subject_id'
    												data-plugin-selectTwo data-width='100%' ");
											?>
											<span class="error"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?= translate('publish_date') ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="date" value="<?= date('Y-m-d') ?>" data-plugin-datepicker />
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?= translate('description') ?></label>
							<div class="col-md-6">
								<textarea type="text" rows="5" class="form-control" name="remarks"><?= set_value('remarks') ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?= translate('attachment_file') ?> <span class="required">*</span></label>
							<div class="col-md-6 mb-md">
								<input type="hidden" name="file_name" value="" id="file_name" />
								<input type="hidden" name="enc_name" value="" id="enc_name" />
								<input type="file" id="attachment_file" name="attachment_file" class="dropify" data-height="120" data-allowed-file-extensions="*" />
								<span class="error"></span>
							</div>
							<div class="col-md-3">
								<div class="progress" style="display: none;">
									<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete</span>
									</div>
								</div>
								<span id="upload-status"></span>
							</div>
						</div>
						<footer class="panel-footer mt-lg">
							<div class="row">
								<div class="col-md-2 col-md-offset-3">
									<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
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
	</div>
</div>
<div class="zoom-anim-dialog modal-block mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-play-circle"></i> <?php echo translate('play') . " " . translate('video'); ?></h4>
		</header>
		<div class="panel-body">
			<div id='quick_view'></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-video-dismiss"><?php echo translate('close'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		var form = $('.frm-submit-data');
		var progressBar = $('.progress');
		var progressStatus = $('#upload-status');
		var saveButton = form.find('[type="submit"]');

		var csrfToken = "<?= $this->security->get_csrf_hash(); ?>";

		var originalFileInput = $('#attachment_file');

		$('.dropify').dropify();


		var drEvent = $('.dropify').dropify();

		drEvent.on('dropify.afterClear', function(event, element) {
			$.ajax({
				url: "<?= base_url('attachments/delete_file') ?>",
				type: 'POST',
				data: {
					file_name: $('#file_name').val(),
					enc_name: $('#enc_name').val()
				},
				success: function(data) {
					result = JSON.parse(data);
					if (result.status == "success") {
						progressStatus.text('File Removed Successfully!');
						$('#file_name').val('');
						$('#enc_name').val('');
					} else {
						progressStatus.text('File Does Not Exist!');
					}
				}
			});
		});

		// Function to replace the file input with a clone
		function resetFileInput() {
			var newFileInput = originalFileInput.clone(true);
			originalFileInput.after(newFileInput);
			originalFileInput.remove();
			originalFileInput = newFileInput;
		}

		// Function to get CSRF token from a meta tag
		function getCsrfToken() {
			return $('meta[name="csrf-token"]').attr('content');
		}

		// Handle file input change event
		$('#attachment_file').on('change', function() {
			$('#file_name').val('');
			$('#enc_name').val('');
			// Disable the save button during file upload
			saveButton.prop('disabled', true);

			// Show the progress bar
			progressBar.show();

			// Create a FormData object to store the file
			var formData = new FormData();
			formData.append('attachment_file', this.files[0]);

			// AJAX request to upload the file
			$.ajax({
				url: '<?= base_url("attachments/upload_file") ?>', // Replace with the correct URL for file upload
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				headers: {
					'X-CSRF-Token': getCsrfToken()
				},
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener('progress', function(event) {
						if (event.lengthComputable) {
							var percentComplete = (event.loaded / event.total) * 100;
							progressBar.find('.progress-bar').css('width', percentComplete + '%');
							progressBar.find('.sr-only').text(percentComplete.toFixed(0) + '% Complete');
						}
					}, false);
					return xhr;
				},
				success: function(response) {
					var result = JSON.parse(response);
					if (result.status == "success") {
						saveButton.prop('disabled', false);
						progressBar.hide();

						progressStatus.text('Upload completed!');
						$('#file_name').val(result.file_name);
						$('#enc_name').val(result.enc_name);
						
						
						// Manually reset the file input
						$('#attachment_file').val(''); // Clear the file input value
						$('.dropify-preview').remove(); // Remove the preview
						$('.dropify-infos').hide(); // Hide the file details
						$('#attachment_file').attr('disabled', 'disabled');
					} else {
						progressStatus.text('Error! ' + result.message);
					}
					// File upload completed, enable the save button


					// Display upload status (e.g., "Upload completed!")

				},
				error: function(xhr, status, error) {
					// Handle any error during the file upload
					progressBar.hide();
					progressStatus.text('Upload failed: ' + error);
				}
			});
		});
	});

	$(document).ready(function() {
		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			var selectedBranch = $(this).val();
			var typeDropdown = $('#type_id');
			var typeDiv = $('#type_div');
			var removeWhenBranch = $('#remove_when_branch');
			var showWhenBranch = $('#show_when_branch');

			// If the "All" option is selected, hide the "type" dropdown
			if (selectedBranch === 'all_branches') {
				//typeDropdown.val('').prop('disabled', true).select2('destroy').hide();
				typeDiv.slideUp('slow');
				removeWhenBranch.slideUp('slow');

				showWhenBranch.slideDown('slow');
			} else {
				//typeDropdown.prop('disabled', false).show().select2();
				typeDiv.slideDown('slow');
				removeWhenBranch.slideDown('slow');

				showWhenBranch.slideUp('slow');
			}

			getClassByBranch(this.value);
			$.ajax({
				url: "<?= base_url('ajax/getDataByBranch') ?>",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'attachments_type'
				},
				success: function(data) {
					$('#type_id').html(data);
				}
			});
		});

		// modal dismiss
		$(document).on("click", ".modal-video-dismiss", function(e) {
			e.preventDefault();
			$.magnificPopup.close();
			$('#attachment_video').trigger('pause');
		});
	});

	function playVideo(id) {
		$.ajax({
			url: base_url + 'attachments/playVideo',
			type: 'POST',
			data: {
				'id': id
			},
			dataType: "html",
			success: function(data) {
				$('#quick_view').html(data);
				mfp_modal('#modal');
			}
		});
	}
</script>