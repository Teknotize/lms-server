<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li>
                <a href="<?= base_url('transfer_posting/index') ?>"><i class="fas fa-list-ul"></i> <?= translate('transfer_posting') ?></a>
            </li>
            <li class="active">
                <a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('edit_transfer_posting') ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="edit">
                <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
                <!--  -->
                <input type="hidden" name="emp_id" required value="<?= $request['emp_id'] ?>">

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('employee_name') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_input("employee_name", $req_user['name'], "class='form-control' disabled") ?>
                        <span class="error"><?= form_error('employee_name') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('current_school') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="hidden" name="current_branch_id" value="<?= $request['current_branch_id'] ?>">
                        <?= form_dropdown("current_branch_id_disabled", $institutes, $request['current_branch_id'], "class='form-control' id='tp_current_branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
                        <span class="error"><?= form_error('current_branch_id') ?></span>
                    </div>
                </div>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('new_school') ?></label>
                    <div class="col-md-6">
                        <?php
                        $new_institutes = $institutes;
                        unset($new_institutes[$request['current_branch_id']]);
                        ?>
                        <?= form_dropdown("new_branch_id", $new_institutes, $request['new_branch_id'], "class='form-control' id='tp_new_branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'") ?>
                        <span class="error"><?= form_error('new_branch_id') ?></span>
                    </div>
                </div>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('new_role') ?> </label>
                    <div class="col-md-6">
                        <?php
                        $branch_id = $request['new_branch_id'] ? $request['new_branch_id'] : $request['current_branch_id'];
                        $designation_list = $this->app_lib->getDesignation($branch_id);
                        echo form_dropdown("designation_id", $designation_list, $request['designation_id'], "class='form-control' id='tp_designation_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                        ?>
                        <span class="error"><?= form_error('designation_id') ?></span>
                    </div>
                </div>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('current_department') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="hidden" name="current_dep_id" value="<?= $request['current_dep_id'] ?>">
                        <?= form_dropdown("current_dep_id_disabled", $departments, $request['current_dep_id'], "class='form-control' id='current_dep_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required disabled") ?>
                        <span class="error"><?= form_error('current_dep_id') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('new_department') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_dropdown("new_dep_id", $departments, $request['new_dep_id'], "class='form-control' id='tp_department_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' required") ?>
                        <span class="error"><?= form_error('new_dep_id') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('effective_from') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_input(['type' => 'date', 'name' => 'effective_from'], $request['effective_from'], 'class="form-control" requried') ?>
                        <span class="error"><?= form_error('effective_from') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('notes') ?></label>
                    <div class="col-md-6">
                        <?= form_textarea(['name' => 'notes'], $request['notes'], 'class="form-control"') ?>
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