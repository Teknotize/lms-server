<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="<?= (empty($validation_error) ? 'active' : '') ?>">
                <a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?= translate('manage_promotions') ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="list" class="tab-pane <?= (empty($validation_error) ? 'active' : '') ?>">
                <div class="mb-md">
                    <table class="table table-bordered table-hover table-condensed mb-md mt-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= translate('name') ?></th>
                                <th><?= translate('staff_id') ?></th>
                                <th><?= translate('requested_department') ?></th>
                                <th><?= translate('requested_scale') ?></th>
                                <th><?= translate('effective_from') ?></th>
                                <th><?= translate('status') ?></th>
                                <th><?= translate('requested_by') ?></th>
                                <th><?= translate('action_by') ?></th>
                                <?php
                                if (get_permission('promotions', 'is_edit')) {
                                ?>
                                    <th><?= translate('actions') ?></th>
                                <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            if (count($promotions) > 0) {
                                foreach ($promotions as $row):
                            ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td>
                                            <a href="<?= base_url('employee/profile/' . $row['emp_id']) ?>" style="color: #4d4d4d" target="_blank">
                                                <?= translate($row['name']) ?>
                                            </a>
                                        </td>
                                        <td><?= $row['staff_id'] ?></td>
                                        <td><?= ($row['dep_id'] === '0') ? '---' : translate($row['department']) ?></td>
                                        <td><?= ($row['scale_id'] === '0') ? '---' : translate($row['scale']) ?></td>
                                        <td><?= $row['effective_from'] ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'approved'): ?>
                                                <span class="badge badge-success"><?= translate($row['status']) ?></span>
                                            <?php elseif ($row['status'] == 'rejected'): ?>
                                                <span class="badge badge-danger"><?= translate($row['status']) ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-warning"><?= translate($row['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ($row['created_by'] === '0' || $row['created_by'] === null) ? '---' : translate($row['created_by_name']) ?></td>
                                        <td><?= ($row['action_by'] === '0' || $row['action_by'] === null) ? '---' : translate($row['action_by_name']) ?></td>
                                        <td class="min-w-c">
                                            <?php
                                            if (get_permission('promotions', 'is_edit')) {
                                            ?>
                                                <a href="<?= base_url('promotions/edit/' . $row['id']) ?>" class="btn btn-circle icon btn-default">
                                                    <i class="fas fa-pen-nib"></i>
                                                </a>
                                            <?php
                                            }
                                            if ($row['status'] === 'pending') {
                                            ?>
                                                <button class='btn btn-success icon btn-circle' onclick="approve_reject_model('<?= base_url('promotions/approve_reject/' . $row['id'] . '/approved') ?>','Are you sure?','Do you want to approve this request','Promotion Request Approved.')"><i class='fas fa-check'></i></button>
                                                <button class='btn btn-danger icon btn-circle' onclick="approve_reject_model('<?= base_url('promotions/approve_reject/' . $row['id'] . '/rejected') ?>','Are you sure?','Do you want to reject this request','Promotion Request Rejected.')"><i class='fas fa-times'></i></button>
                                            <?php
                                                // if ($row['emp_id'] === get_loggedin_user_id()) {
                                                // 	echo btn_delete('promotion/delete/' . $row['id']);
                                                // }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    $count++;
                                endforeach;
                                ?>
                            <?php
                            } else {
                                echo "<tr><td colspan='11'><h5 class='text-danger text-center'>" . translate('no_information_available') . "</h5></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>