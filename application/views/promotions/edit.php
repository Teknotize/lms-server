<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li>
                <a href="<?= base_url('promotions/index') ?>"><i class="fas fa-list-ul"></i> <?= translate('manage_promotions') ?></a>
            </li>
            <li class="active">
                <a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('edit_promotion') ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="edit">
                <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
                <!--  -->
                <input type="hidden" name="promotion_id" required value="<?= $promotions['id'] ?>">
                <input type="hidden" name="emp_id" required value="<?= $promotions['emp_id'] ?>">

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('name') ?></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'text', 'name' => 'emp_name'], $promotions['name'], 'class="form-control" disabled') ?>
                    </div>
                </div>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('employee_id') ?></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'text', 'name' => 'employee_id'], $promotions['staff_id'], 'class="form-control" disabled') ?>
                    </div>
                </div>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('phone') ?></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'text', 'name' => 'phone_number'], $promotions['mobileno'], 'class="form-control" disabled') ?>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('current_department') ?></label>
                    <div class="col-md-6">
                        <?= form_dropdown("current_dep_id_disabled", $departments, $promotions['current_department'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
                        <span class="error"><?= form_error('current_dep_id') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('assign_department_(Optional)') ?> </label>
                    <div class="col-md-6">
                        <?= form_dropdown("new_dep_id", $departments, $promotions['dep_id'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'") ?>
                        <span class="error"><?= form_error('new_dep_id') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('current_scale') ?> </label>
                    <div class="col-md-6">
                        <?= form_dropdown("current_scale", $payscales, $promotions['current_scale'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' disabled") ?>
                        <span class="error"><?= form_error('current_scale') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('promotion_scale') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_dropdown("promotion_scale", $payscales, $promotions['scale_id'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required") ?>
                        <span class="error"><?= form_error('promotion_scale') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('qualification') ?></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'text', 'name' => 'qualification'], $promotions['qualification'], 'class="form-control" disabled') ?>
                        <span class="error"><?= form_error('qualification') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('years_of_experience') ?></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'text', 'name' => 'years_of_experience'], $promotions['total_experience'], 'class="form-control" disabled') ?>
                        <span class="error"><?= form_error('years_of_experience') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('ratings') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_dropdown("ratings", $ratings, $promotions['rating'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required") ?>
                        <span class="error"><?= form_error('ratings') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('effective_from') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'date', 'name' => 'effective_from'], $promotions['effective_from'], 'class="form-control" requried') ?>
                        <span class="error"><?= form_error('effective_from') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('notes') ?></label>
                    <div class="col-md-6">
                        <?= form_textarea(['name' => 'notes'], $promotions['notes'], 'class="form-control"') ?>
                        <span class="error"><?= form_error('notes') ?></span>
                    </div>
                </div>


                <footer class="panel-footer mt-lg">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-3">
                            <button type="submit" class="btn btn-default btn-block" name="submit" value="update">
                                <i class="fas fa-plus-circle"></i> <?= translate('update') ?>
                            </button>
                        </div>
                    </div>
                </footer>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>