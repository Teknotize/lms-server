<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="<?= (empty($validation_error) ? 'active' : '') ?>">
                <a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('permissions_list') ?></a>
            </li>
            <li class="<?= (!empty($validation_error) ? 'active' : '') ?>">
                <a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?= translate('add_permission') ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="list" class="tab-pane <?= (empty($validation_error) ? 'active' : '') ?>">
                <div class="mb-md">
                    <table class="table table-bordered table-hover table-condensed table_default">
                        <thead>
                            <tr>
                                <th width="50"><?= translate('sl') ?></th>
                                <th><?= translate('permission_name') ?></th>
                                <th><?= translate('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $permissions = $this->db->get('permission')->result();
                            foreach ($permissions as $row) :
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $row->name; ?></td>
                                    <?php
                                    if (get_permission('add_permissions', 'is_edit')) { ?>
                                        <td>
                                            <a href="<?= base_url('modules_and_permissions/permission_edit/' . $row->id) ?>" class="btn btn-default btn-circle icon">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane <?= (!empty($validation_error) ? 'active' : '') ?>" id="create">
                <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>

                <?php
                $query = $this->db->select('id, name')->get('permission_modules');
                $result = $query->result_array();
                $modules = array_column($result, 'name', 'id');
                ?>

                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('module') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_dropdown('module_id', $modules, set_value('module_id'), 'class="form-control"'); ?>
                        <span class="error"><?= form_error('module_id') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('name') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" value="<?= set_value('name') ?>" />
                        <span class="error"><?= form_error('name') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('prefix') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="prefix" value="<?= set_value('prefix') ?>" />
                        <span class="error"><?= form_error('prefix') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('show_view') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_checkbox('show_view', 1, true); ?>
                        <span class="error"><?= form_error('show_view') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('show_add') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_checkbox('show_add', 1, true); ?>
                        <span class="error"><?= form_error('show_add') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('show_edit') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_checkbox('show_edit', 1, true); ?>
                        <span class="error"><?= form_error('show_edit') ?></span>
                    </div>
                </div>
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?= translate('show_delete') ?> <span class="required">*</span></label>
                    <div class="col-md-6">
                        <?= form_checkbox('show_delete', 1, true); ?>
                        <span class="error"><?= form_error('show_delete') ?></span>
                    </div>
                </div>

                <footer class="panel-footer mt-lg">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-3">
                            <button type="submit" class="btn btn-default btn-block" name="submit" value="save">
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