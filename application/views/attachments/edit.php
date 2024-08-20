<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li>
						<a href="<?=base_url('attachments')?>"><i class="fas fa-list-ul"></i> <?=translate('attachments')?></a>
					</li>
					<li  class="active">
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit_attachments')?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="create">
						<?php echo form_open_multipart('attachments/save', array('class' => 'form-bordered form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="attachment_id" value="<?=$data['id']?>">
							<?php if (is_superadmin_loggedin() ): ?>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('institution')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$arrayBranch = $this->app_lib->getSelectList('branch');
										// Add "All" option as the second element of the array
                                        //$allOption = array('all_branches' => 'All');
                                        //$arrayBranch = array_slice($arrayBranch, 0, 1, true) + $allOption + array_slice($arrayBranch, 1, count($arrayBranch) - 1, true);
										//print_r($arrayBranch);
										
										if($data['branch_id'] == 'unfiltered'){
										    $data['branch_id'] = 'all_branches';
										}
										
										echo form_dropdown("branch_id", $arrayBranch, $data['branch_id'], "class='form-control' id='branch_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"></span>
								</div>
							</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('title')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="title" value="<?=$data['title']?>" />
									<span class="error"></span>
								</div>
							</div>
							
							<div style="margin-bottom:30px;" id="show_when_branch">
							    
							    <?php if (is_superadmin_loggedin() ): ?>
    							<div class="form-group">
    								<label class="control-label col-md-3"><?=translate('LMS Grade')?> <span class="required">*</span></label>
    								<div class="col-md-6">
    									<?php
    										$arrayLmsGrade = $this->app_lib->getSelectListByColName('lms_grades', null, 'grade_name');
    										echo form_dropdown("lms_grade_id", $arrayLmsGrade, $data['lms_grade_id'], "class='form-control' id='lms_grade_id'
    										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
    									?>
    									<span class="error"></span>
    								</div>
    							</div>
    							
    							<div class="form-group">
    								<label class="control-label col-md-3"><?=translate('LMS Subject')?> <span class="required">*</span></label>
    								<div class="col-md-6">
    									<?php
    										$arrayLmsSubject = $this->app_lib->getSelectListByColName('lms_subjects', null, 'subject_name');
    										echo form_dropdown("lms_subject_id", $arrayLmsSubject, $data['lms_subject_id'], "class='form-control' id='lms_subject_id'
    										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
    									?>
    									<span class="error"></span>
    								</div>
    							</div>
    							
    							<?php endif; ?>
							    
    							
							</div>
							
							<?php
							    $displayProperty = "block";
							    if($data['branch_id'] == 'all_branches') {
							        $displayProperty = "none";
							    } else {
							        $displayProperty = "block";
							    }
							?>
							<div style="margin-bottom:10px; display:<?php echo $displayProperty; ?>;" id="type_div">
    							<div class="form-group">
    								<label class="control-label col-md-3"><?=translate('type')?> <span class="required">*</span></label>
    								<div class="col-md-6">
    									<?php
    										$arrayType = $this->app_lib->getSelectByBranch('attachments_type', $data['branch_id']);
    										echo form_dropdown("type_id", $arrayType, $data['type_id'], "class='form-control' id='type_id' data-plugin-selectTwo
    										data-width='100%' data-minimum-results-for-search='Infinity' ");
    									?>
    									<span class="error"></span>
    								</div>
    							</div>
    						</div>
    						<?php
							    $displayProperty = "block";
							    if($data['branch_id'] == 'all_branches') {
							        $displayPropertyWhenBranch = "none";
							    } else {
							        $displayPropertyWhenBranch = "block";
							    }
							?>
    						<div style="margin-bottom:5px; display:<?php echo $displayPropertyWhenBranch; ?>;" id="remove_when_branch">
    							<div class="form-group">
    								<div class="col-md-offset-3">
    									<div class="ml-md checkbox-replace">
    										<label class="i-checks"><input type="checkbox" name="all_class_set" id="all_class_set" <?=($data['class_id'] == 'unfiltered' ? 'checked' : '');?>><i></i> Available For All Classes</label>
    									</div>
    								</div>
    								<div id="class_div" <?php if($data['class_id'] == 'unfiltered') { ?> style="display: none" <?php } ?>>
    									<div class="mt-sm">
    										<label class="control-label col-md-3"><?=translate('class')?> <span class="required">*</span></label>
    										<div class="col-md-6">
    
    											<?php
    												$arrayClass = $this->app_lib->getClass($data['branch_id']);
    												echo form_dropdown("class_id", $arrayClass, $data['class_id'], "class='form-control' id='class_id' onchange='getSubjectByClass(this.value)'
    												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
    											?>
    											<span class="error"></span>
    										</div>
    									</div>
    								</div>
    							</div>
    							<div class="form-group" id="sub_div" <?php if($data['class_id'] == 'unfiltered') { ?> style="display: none" <?php } ?>>
    								<div class="col-md-offset-3">
    									<div class="ml-md checkbox-replace">
    										<label class="i-checks"><input type="checkbox" name="subject_wise" id="subject_wise" <?=($data['subject_id'] == 'unfiltered' ? 'checked' : '');?>><i></i> Not According Subject</label>
    									</div>
    								</div>
    								<div id="subject_div" <?php if($data['subject_id'] == 'unfiltered') { ?> style="display: none" <?php } ?>>
    									<div class="mt-sm">
    										<label class="control-label col-md-3"><?=translate('subject')?> <span class="required">*</span></label>
    										<div class="col-md-6">
    											<?php
    												if(!empty($data['class_id'])){
    													$arraySubject = array("" => translate('select'));
    													$assigns = $this->db->select('subject_id')->where('class_id', $data['class_id'])->get('subject_assign')->result();
    													foreach ($assigns as $assign){
    														$arraySubject[$assign->subject_id] = get_type_name_by_id('subject', $assign->subject_id);
    													}
    												}else{
    													$arraySubject = array("" => translate('select_class_first'));
    												}
    												echo form_dropdown("subject_id", $arraySubject, $data['subject_id'], "class='form-control' id='subject_id'
    												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
    											?>
    											<span class="error"></span>
    										</div>
    									</div>
    								</div>
    							</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('publish_date')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="date" value="<?=$data['date']?>" data-plugin-datepicker />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label  class="col-md-3 control-label"><?=translate('description')?></label>
								<div class="col-md-6">
									<textarea type="text" rows="5" class="form-control" name="remarks" ><?=$data['remarks']?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?= translate('attachment_file') ?> <span class="required">*</span></label>
								<div class="col-md-6 mb-md">
									<input type="hidden" name="file_name" value="<?= $data['file_name'] ?>" id="file_name"/>
									<input type="hidden" name="enc_name" value="<?= $data['enc_name'] ?>" id="enc_name"/>
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
							<!-- <div class="form-group">
								<label class="control-label col-md-3"><?=translate('attachment_file')?> <span class="required">*</span></label>
								<div class="col-md-6 mb-md">
									<input type="file" name="attachment_file" class="dropify" data-height="120" data-allowed-file-extensions="*" />
									<span class="error"></span>
								</div>
							</div> -->
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?=translate('update')?>
										</button>
									</div>
								</div>	
							</footer>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</section>
	</div>
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