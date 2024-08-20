<style>
	/* Custom CSS for the layout */
	.tabs-left {
		float: left;
		border-right: 1px solid #ddd;
		height: 100%;
	}

	.tabs-left>li {
		float: none;
		margin-bottom: -1px;
		width: 100%;
	}

	.tabs-left>li.active>a,
	.tabs-left>li.active>a:hover,
	.tabs-left>li.active>a:focus {
		border-color: #428bca #ddd #ddd #ddd;
	}

	.tabs-left>li>a {
		min-width: 120px;
		margin-right: 0;
		margin-bottom: 3px;
		text-align: right;
	}

	.tabs-left>li:last-child>a {
		margin-bottom: 0;
	}

	.tab-content {
		overflow: hidden;
	}

	/* Clear the float for the right-side content */
	.content-right {
		overflow: hidden;
	}
</style>
<section class="panel">
	<ol class="breadcrumb">
		<li><?= translate('lms_library') ?></li>
		<?php $bnch = $this->db->where('id', $this->data['branch_id'])->get('branch')->row(); ?>
		<?php $grd = $this->db->where('id', $this->data['grade_id'])->where('branch_id', $bnch->id)->get('class')->row(); ?>
		<?php $sub = $this->db->where('id', $this->data['subject_id'])->get('subject')->row(); ?>
		<?php
		if (is_student_loggedin()) { ?>
			<li><?php echo $bnch->name; ?></li>
		<?php } else { ?>
			<li class="active"><a href="<?= base_url('digital_library/branch/' . $this->data['branch_id']) ?>"><?php echo $bnch->name; ?></a></li>
		<?php }
		?>

		<?php
		if (is_student_loggedin()) { ?>
			<li><?php echo $grd->name; ?></li>
		<?php } else { ?>
			<li class="active"><a href="<?= base_url('digital_library/grade/' . $this->data['branch_id'] . '/' . $this->data['grade_id']) ?>"><?php echo $grd->name; ?></a></li>
		<?php }
		?>


		<li class="active"><a href="<?= base_url('digital_library/subject/' . $this->data['branch_id'] . '/' . $this->data['grade_id'] . '/' . $this->data['subject_id']) ?>"><?php echo $sub->name; ?></a></li>
	</ol>

	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?= (empty($validation_error) ? 'active' : '') ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('topics') ?></a>
			</li>
			<!-- <div class="pagination">
				<?php foreach ($pagination_links as $page_number => $page_link) : ?>
					<?php $active_class = ($current_page == $page_number) ? 'active' : ''; ?>
					<a href="<?php echo $page_link; ?>" class="<?php echo $active_class; ?>">
						<?php echo $page_number; ?>
					</a>
				<?php endforeach; ?>
			</div> -->
			<ul style="float:right;" class="pagination">
				<?php foreach ($pagination_links as $arr) : ?>
					<li class="<?php echo ($current_page == $arr['page']) ? 'active' : ''; ?>">
						<a href="<?php echo $arr['link']; ?>">Week <?php echo $arr['page']; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</ul>

		<div class="tab-content">

			<div class="container-fluid">
				<?php

				/* if(is_student_loggedin()){

					} else {
						
					} */

				/* $this->db->select('subject.*, attachments.*'); // Selects all columns from both tables
				$this->db->from('subject');
				$this->db->join('attachments', 'subject.id = attachments.subject_id', 'inner');
				$this->db->where('attachments.class_id', $this->data['grade_id']);
				$this->db->where('attachments.subject_id', $this->data['subject_id']);
				$this->db->group_by('attachments.id');

				$attachments = $this->db->get('class')->result(); */


				if ($attachments) :
				?>
					<div class="">
						<div class="col-lg-3">
							<div style="max-height: 400px; overflow-y:scroll;" class="scroller-video">
								<!-- Left side tabs -->
								<ul class="nav nav-tabs tabs-left">
									<?php $i = 1; ?>
									<?php foreach ($attachments as $attachment) : ?>
										<li class="<?php echo $i == 1 ? 'active' : ''; ?>">
											<a style="padding:5px;" href="#tab_<?php echo $attachment->id; ?>" data-toggle="tab">
												<?php echo $attachment->title; ?>
												<span class="label label-danger"><?php echo strtolower($attachment->type_name); ?></span>
											</a>
										</li>
										<?php $i++; ?>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="col-lg-9 content-right">
							<!-- Right side content -->
							<div class="tab-content">
								<?php $j = 1; ?>
								<?php foreach ($attachments as $attachment) : ?>
									<div class="tab-pane <?php echo $j == 1 ? 'active' : ''; ?>" id="tab_<?php echo $attachment->id; ?>">
										<h4 style="color:#29a65a;"><?php echo $attachment->title; ?></h4>
											<?php if (strtolower($attachment->type_name) == 'video') : ?>
												<video width="100%" controls id="attachment_video_<?php echo $attachment->id; ?>">
													<source src="<?php echo base_url('uploads/attachments/' . $attachment->enc_name); ?>" type="video/mp4">
													Your browser does not support HTML video.
												</video>
											<?php else : ?>
												<div id="pdf-container">
													<object data="<?php echo base_url('uploads/attachments/' . $attachment->enc_name); ?>" type="application/pdf" width="100%" height="500px">
														<p>Sorry, your browser doesn't support embedded PDFs. <a href="<?php echo base_url('uploads/attachments/' . $attachment->enc_name); ?>">Click here to download the PDF file.</a></p>
													</object>
												</div>
											<?php endif; ?>
											<p style="text-align: justify;"><?php echo $attachment->remarks; ?></p>
											<div style="display: block;">
												<small style="float:left; color:#ccc;">Published By: <span style="color:#000;"><?php echo get_type_name_by_id('staff', $attachment->uploader_id); ?></span></small>
												<small style="float:right; color:#ccc;">Published On: <span style="color:#000;"><?php echo _d($attachment->date); ?></span></small>
											</div>
									</div>
									<?php $j++; ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php else : ?>
					<p>No Content available in this grade.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php if ($resources) : ?>
	<div class="panel-group" id="accordion">
		<div class="panel panel-accordion">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#documents" aria-expanded="false">
						<i class="fas fa-folder-open"></i> Resources </a>
				</h4>
			</div>
			<div id="documents" class="accordion-body collapse" aria-expanded="false" style="height: 0px;">
				<div class="panel-body">
					<section class="panel">
						<div class="tabs-custom">

							<div class="tab-content">

								<div class="container-fluid">
									<?php
									if ($resources) :
									?>
										<div class="">
											<div class="col-lg-3">
												<div style="max-height: 400px; overflow-y:scroll;" class="scroller-video">
													<!-- Left side tabs -->
													<ul class="nav nav-tabs tabs-left">
														<?php $i = 1; ?>
														<?php foreach ($resources as $resource) : ?>
															<li class="<?php echo $i == 1 ? 'active' : ''; ?>">
																<a style="padding:5px;" href="#tab_<?php echo $resource->id; ?>" data-toggle="tab">
																	<?php echo $resource->title; ?>
																	<span class="label label-danger"><?php echo strtolower($resource->type_name); ?></span>
																</a>
															</li>
															<?php $i++; ?>
														<?php endforeach; ?>
													</ul>
												</div>
											</div>
											<div class="col-lg-9 content-right">
												<!-- Right side content -->
												<div class="tab-content">
													<?php $j = 1; ?>
													<?php foreach ($resources as $resource) : ?>
														<div class="tab-pane <?php echo $j == 1 ? 'active' : ''; ?>" id="tab_<?php echo $resource->id; ?>">
															<div>
																<div style="float:left;">
																	<h4 style="color:#29a65a;"><?php echo $resource->title; ?></h4>
																</div>
																<div style="float: right;">
																	<button onclick="toggleFullScreen(<?php echo $resource->id; ?>)" class="btn btn-success" id="fullscreen-button_<?php echo $resource->id; ?>" title="Fullscreen">
																		<i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
																<?php if (strtolower($resource->type_name) == 'video') : ?>
																	<video width="100%" controls id="attachment_video_<?php echo $resource->id; ?>">
																		<source src="<?php echo base_url('uploads/attachments/' . $resource->enc_name); ?>" type="video/mp4">
																		Your browser does not support HTML video.
																	</video>
																<?php else : ?>
																	<div id="pdf-container_<?php echo $resource->id; ?>">
																		<object data="<?php echo base_url('uploads/attachments/' . $resource->enc_name); ?>" type="application/pdf" width="100%" height="550px">
																			<p>Sorry, your browser doesn't support embedded PDFs. <a href="<?php echo base_url('uploads/attachments/' . $resource->enc_name); ?>">Click here to download the PDF file.</a></p>
																		</object>
																	</div>
																<?php endif; ?>
																<p style="text-align: justify;"><?php echo $resource->remarks; ?></p>
																<div style="display: block;">
																	<small style="float:left; color:#ccc;">Published By: <span style="color:#000;"><?php echo get_type_name_by_id('staff', $resource->uploader_id); ?></span></small>
																	<small style="float:right; color:#ccc;">Published On: <span style="color:#000;"><?php echo _d($resource->date); ?></span></small>
																</div>
														</div>
														<?php $j++; ?>
													<?php endforeach; ?>
												</div>
											</div>
										</div>
									<?php else : ?>
										<p>No Content available in this grade.</p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<script>
	// Get all video elements
	const videoElements = document.querySelectorAll('video');

	// Add an event listener to each tab that will pause the playing video
	document.querySelectorAll('.tabs-left a[data-toggle="tab"]').forEach(tab => {
		tab.addEventListener('click', function() {
			// Pause all video elements
			videoElements.forEach(video => {
				video.pause();
			});
		});
	});

	function toggleFullScreen($resource_id) {
		const pdfContainer = document.getElementById("pdf-container_"+$resource_id);
        const fullscreenButton = document.getElementById("fullscreen-button_"+$resource_id);

		if (document.fullscreenElement) {
			// If in fullscreen mode, exit fullscreen
			if (document.exitFullscreen) {
				document.exitFullscreen();
				$(pdfContainer).find('object').css('height', '500px');
				$(pdfContainer).css('height', '500px');
				$("#pdf-container_"+$resource_id).css('height', '500px');
			}

			$(pdfContainer).find('object').css('height', '500px');
			$("#pdf-container_"+$resource_id).css('height', '500px');

			//pdfContainer.classList.remove("fullscreen");
		} else {
			// If not in fullscreen mode, enter fullscreen
			if (pdfContainer.requestFullscreen) {
				pdfContainer.requestFullscreen();
			}

			//$('#pdf-container object').css('height', '100%');
			$(pdfContainer).find('object').css('height', '100%');

			//pdfContainer.classList.add("fullscreen");
		}
	}

	/* const pdfContainer = document.getElementById("pdf-container");
        const fullscreenButton = document.getElementById("fullscreen-button");

        fullscreenButton.addEventListener("click", () => {
            if (document.fullscreenElement) {
                // If in fullscreen mode, exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
					//$('#pdf-container object').css('height', '500px');
                }

				//$('#pdf-container object').css('height', '500px');

                //pdfContainer.classList.remove("fullscreen");
            } else {
                // If not in fullscreen mode, enter fullscreen
                if (pdfContainer.requestFullscreen) {
                    pdfContainer.requestFullscreen();
                }

				$('#pdf-container object').css('height', '100%');

                //pdfContainer.classList.add("fullscreen");
            }
        }); */
</script>