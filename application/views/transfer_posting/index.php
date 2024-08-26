<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="<?= (empty($validation_error) ? 'active' : '') ?>">
                <a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('manage_requests') ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="list" class="tab-pane <?= (empty($validation_error) ? 'active' : '') ?>">
                <div class="mb-md">
                    <table class="table table-bordered table-hover table-condensed mb-none table-default">
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
        </div>
    </div>
</section>