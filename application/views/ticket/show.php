<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('ticket/index') ?>">
				  <i class="fas fa-list-ul"></i> <?=translate('ticket_list')?>
				</a>
			</li>
			<li class="active">
				<a href="#add" data-toggle="tab">
				 <i class="far fa-edit"></i> <?=translate('Reply_ticket')?>
				</a>
			</li>
		</ul>
		<?php 
		       $this->db->where('id', $ticket_id);
			   $ticket = $this->db->get('tickets')->row_array();

			   $this->db->where('ticket_id', $ticket_id);
			   $ticket_replies = $this->db->get('ticket_replies')->result_array();

			   $this->db->where('id', $ticket['user_id']);
				$tuser = $this->db->get('staff')->row_array(); 
		?>
		<div class="tab-content">
			<div class="tab-pane active" id="add">
					<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit-data'));?>
					<input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">	
					 
					<div class="row">
						<div class="col-md-2 align-content-start text-center">
							<img src="<?= base_url('uploads/images/staff/') . ($tuser['photo']); ?>" alt="Profile " class="me-2 rounded-circle" style="width: 50px; height: 50px;">
							<h6 class="mb-0 my-2 fw-lighter"><?php echo $tuser['name']?></h6>
						</div>
						<label class="control-label col-md-1"><?=translate('subject')?></label>
						<div class="col-md-6">
								<h3><?php echo $ticket['subject'] ?> </h3>
						</div> 
						<label class="col-md-1 control-label"><?=translate('Ticket Status')?> <span class="required">*</span></label>
						<div class="col-md-2">
							<select id="ticketStatus" name="status" >
								<option value="open" <?php echo $ticket['status']=='open'?'selected':'' ?>>Open</option>
								<option value="closed" <?php echo $ticket['status']=='closed' ?'selected':'' ?>>Closed</option>
							</select>
							<span class="error"></span>
						</div>
					</div>   
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('description')?></label>
						<div class="col-md-9">
						    <?php echo $ticket['message'] ?>
							<span class="text-white-50  "><?php echo  get_nicetime($ticket['created_at']) ?></span>
							<span class="text-white-50"><?php echo  $ticket['created_at'] ?></span>
						</div> 
						<?php if($ticket['attachment']!='null'){ ?>
							<label class="col-md-3 control-label"><?=translate('Attachment')?></label>
							<div class="col-md-9">
								<a target="_blank" href="<?=base_url('uploads/frontend/tickets/' . $ticket['attachment'] )?>">
									<img src="<?=base_url('uploads/frontend/tickets/' . $ticket['attachment'] )?>" height="60" />
								</a>
							</div>
						<?php }?>
					</div> 
                  <!-- replies start here  -->
				   <?php foreach($ticket_replies as $tr){ ?>
					<?php 
							if($tr['user_id']!=='null'){
								$this->db->where('id', $tr['user_id']);
							    $user = $this->db->get('staff')->row_array(); 		
							}
							
							if($tr['agent_id']!=='null'){
								$this->db->where('id', $tr['agent_id']);
								$agent  = $this->db->get('staff')->row_array();
								 
							}
							 
					?>
					<div class=" row">
						<div class="col-md-2 align-content-start text-center">
						<img src="<?= base_url('uploads/images/staff/') . ($tr['agent_id'] !== 'null' ? $agent['photo'] : $user['photo']); ?>" alt="Profile Logo" class="me-2 rounded-circle" style="width: 50px; height: 50px;">
                        <?php if($tr['agent_id']!=='null'){ ?>
							<h6 class="mb-0 my-2 fw-lighter"><?php echo "Agent :". $agent['name']; ?> </h6>
						<?php }else{?>
							<h6 class="mb-0 my-2 fw-lighter"><?php echo "You :". $user['name']; ?> </h6>
						<?php }?>
						</div>  
						<div class="col-md-10"> 
						   <?php echo $tr['message'] ?>
							<span class="text-white-50  "><?php echo  get_nicetime($tr['created_at']) ?></span>
							<span class="text-white-50"><?php echo  $tr['created_at'] ?></span>
						</div>  
						<?php if($tr['attachment']!='null'){ ?>
							<div class="col-md-9">
								<a target="_blank" href="<?=base_url('uploads/frontend/ticketreply/' . $tr['attachment'] )?>">
								<img style="height:100px;margin-left: 100px;" src="<?=base_url('uploads/frontend/ticketreply/' . $tr['attachment'] )?>" height="60" />
								</a> 
							</div> 
						<?php }?>
					</div>
                    <hr>
					<?php } ?>
					<!-- replies end here  -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('description')?></label>
						<div class="col-md-6">
							<textarea name="message" class="summernote"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('attachment')?></label>
						<div class="col-md-6">
							<input type="hidden" name="old_attachment" value="<?php echo $ticket['attachment'] ?>">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="attachment" />
									</span>
									<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
								</div>
							</div>
							<span class="error"></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>

		</div>
	</div>
</section>

<script type = "text/javascript">
    $(document).ready(function() {
        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY/MM/DD'
            }
        });

        $('#branch_id').on('change', function() {
            var branchID = $(this).val();
            $.ajax({
                url: "<?=base_url('ajax/getDataByBranch')?>",
                type: 'POST',
                data: {
                    branch_id: branchID,
                    table: 'event_types'
                },
                success: function(data) {
                    $('#type_id').html(data);
                }
            });
            $("#selected_audience").empty();
        });
        $('#audition').on('change', function() {
            var audition = $(this).val();
            var branchID = ($('#branch_id').length ? $('#branch_id').val() : "");
            auditionAjax(audition, branchID);
        });
        auditionAjax(<?php echo $event['audition'] ?>, <?php echo $event['branch_id'] ?>);
    });

	function auditionAjax(audition = '', branchID = '') {
	    if (audition == "1" || audition == null) {
	        $("#selected_user").hide("slow");
	    } else {
	        if (audition == "2") {
	            $.ajax({
	                url: base_url + 'ajax/getClassByBranch',
	                type: 'POST',
	                data: {
	                    branch_id: branchID
	                },
	                success: function(data) {
	                    $('#selected_audience').html(data);
	                }
	            });
	            $("#selected_user").show('slow');
	            $("#selected_label").html("<?=translate('class')?> <span class='required'>*</span>");
	        }
	        if (audition == "3") {
	            $.ajax({
	                url: "<?=base_url('event/getSectionByBranch')?>",
	                type: 'POST',
	                data: {
	                    branch_id: branchID
	                },
	                success: function(data) {
	                    $('#selected_audience').html(data);
	                }
	            });
	            $("#selected_user").show('slow');
	            $("#selected_label").html("<?=translate('section')?> <span class='required'>*</span>");
	        }
	        setTimeout(function() {
	            var JSONObject = JSON.parse('<?php echo $event['selected_list'] ?>');
	            for (var i = 0, l = JSONObject.length; i < l; i++) {
	                $("#selected_audience option[value='" + JSONObject[i] + "']").prop("selected", true);
	            }
	            $('#selected_audience').trigger('change.select2');
	        }, 200);
	    }
	}
</script>