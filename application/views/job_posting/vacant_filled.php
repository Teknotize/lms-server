<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><?= translate('select_time_period') ?></h4>
    </header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
    <div class="panel-body">
        <div class="row mb-sm">
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"><?= translate('institution') ?></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
            </div>
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"><?= translate('start_date') ?> <span class="required">*</span></label>
                    <?=
                    form_input(['type' => 'date', 'name' => 'start_date'], set_value('start_date'), 'class="form-control" requried') ?>
                </div>
            </div>
            <div class="col-md-4 mb-sm">
                <div class="form-group">
                    <label class="control-label"><?= translate('end_date') ?> <span class="required">*</span></label>
                    <?=
                    form_input(['type' => 'date', 'name' => 'end_date'], set_value('end_date'), 'class="form-control" requried') ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="panel-footer">
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <button type="submit" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?= translate('filter') ?></button>
            </div>
        </div>
    </footer>
    <?php echo form_close(); ?>
</section>

<?php if (isset($job_postings)) { ?>
    <section class="panel">
        <div class="tabs-custom">
            <div class="tab-content">
                <div id="list" class="tab-pane active">
                    <table class="table table-bordered table-hover table-condensed mb-none table-export">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= translate('institution') ?></th>
                                <th><?= translate('title') ?></th>
                                <th><?= translate('qualification') ?></th>
                                <th><?= translate('experience') ?></th>
                                <th><?= translate('contract_type') ?></th>
                                <th><?= translate('posts') ?></th>
                                <th><?= translate('due_date') ?></th>
                                <th><?= translate('description') ?></th>
                                <th><?= translate('status') ?></th>
                                <th><?= translate('created_by') ?></th>
                                <th><?= translate('action_by') ?></th>
                                <th><?= translate('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            foreach ($job_postings as $row):
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?= $row['branch_name'] ? translate($row['branch_name']) : '---' ?></td>
                                    <td><?= $row['title'] ? translate($row['title']) : '---' ?></td>
                                    <td><?= $row['qualification'] ? $row['qualification'] : '---' ?></td>
                                    <td><?= $row['experience'] ? $row['experience'] : '---' ?></td>
                                    <td><?= $row['contract_type'] ? translate($row['contract_type']) : '---' ?></td>
                                    <td><?= $row['no_of_posts'] ? $row['no_of_posts'] : '---' ?></td>
                                    <td><?= $row['due_date'] ? $row['due_date'] : '---' ?></td>
                                    <td><?= $row['description'] ? $row['description'] : '---' ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'filled'): ?>
                                            <span class="badge badge-success"><?= translate($row['status']) ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning"><?= translate($row['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['created_by_name'] ? $row['created_by_name'] : '---' ?></td>
                                    <td><?= $row['action_by_name'] ? $row['action_by_name'] : '---' ?></td>

                                    <td class="min-w-c">
                                        <?php if (get_permission('award', 'is_edit')) { ?>
                                            <!--update link-->
                                            <a href="<?php echo base_url('job_posting/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        <?php }
                                        if (get_permission('job_posting', 'is_delete')) { ?>
                                            <!-- delete link -->
                                            <?php echo btn_delete('job_posting/delete/' . $row['id']); ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php } ?>