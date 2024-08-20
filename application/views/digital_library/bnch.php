<style>
	.subject_object:hover {
		background-color: lightgreen;
		border: 1px solid darkgreen;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
		color: white;

		transition: background-color 0.5s ease;
		transition: border 0.3s ease;
		transition: box-shadow 0.2s ease;
		transition: color 0.1s ease;
	}
</style>
<section class="panel">
	<ol class="breadcrumb">
		<li><?= translate('lms_library') ?></li>
		<?php $bnch = $this->db->where('id', $this->data['branch_id'])->get('branch')->row(); ?>
		<li class="active"><a href="<?= base_url('digital_library/branch/' . $this->data['branch_id']) ?>"><?php echo $bnch->name; ?></a></li>
	</ol>
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?= (empty($validation_error) ? 'active' : '') ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('classes') ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?= (empty($validation_error) ? 'active' : '') ?>">
				<div class="mb-md">
					<?php
					$count = 1;
					/* $this->db->select('class.*, attachments.*'); // Selects all columns from both tables
					$this->db->from('subject');
					$this->db->join('attachments', 'subject.id = attachments.subject_id', 'inner');
					$this->db->where('attachments.class_id', $this->data['grade_id']);
					$this->db->group_by('subject.id'); */

					$subjects = $this->db->where('branch_id', $this->data['branch_id'])->get('class')->result();

					$colors = array(
						'#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3',
						'#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39',
						'#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#607d8b'
					);

					if ($subjects) :
						foreach ($subjects as $row) :
							$initials = '';
							$words = explode(' ', $row->name);
							foreach ($words as $word) {
								$initials .= strtoupper(substr($word, 0, 1));
							}

							$selected_color = $colors[array_rand($colors)];

					?>

							<div style="border: 1px solid <?php echo $selected_color; ?>;" class="subject_object">
								<a href="<?=base_url('digital_library/grade/'. $row->branch_id.'/'. $row->id)?>" style="text-decoration: none; color: inherit;">
									<div class="media">
										<div class="media-left">
											<div class="avatar" style="width: 100px; height: 100px; font-size: 60px; text-align: center; padding-top: 40px; color: #fff;background-color:<?php echo $selected_color; ?>;"><?php echo $initials; ?></div>
										</div>
										<div style="vertical-align: middle;" class="media-body">
											<h4 style="font-size: xx-large;" class="media-heading"><?php echo $row->name; ?></h4>
										</div>
									</div>
								</a>
							</div>
							<hr />
						<?php endforeach; ?>
					<?php else : ?>
						<p>No Content available in this grade.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>