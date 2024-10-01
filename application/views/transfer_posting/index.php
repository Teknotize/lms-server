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
                    <table class="table table-bordered table-hover table-condensed mb-none table-export">
                        <thead>
                            <tr>
                                <th width="50"><?= translate('sl') ?></th>
                                <th><?= translate('name') ?></th>
                                <th><?= translate('staff_id') ?></th>
                                <th><?= translate('requested_role') ?></th>
                                <th><?= translate('requested_department') ?></th>
                                <th><?= translate('requested_institute') ?></th>
                                <th><?= translate('effective_from') ?></th>
                                <th><?= translate('status') ?></th>
                                <th><?= translate('requested_by') ?></th>
                                <th><?= translate('action_by') ?></th>
                                <th><?= translate('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            if (count($transfer_posting_requests) > 0) {
                                foreach ($transfer_posting_requests as $row):
                            ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td>
                                            <a href="<?= base_url('employee/profile/' . $row['emp_id']) ?>" style="color: #4d4d4d" target="_blank">
                                                <?= translate($row['emp_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= $row['staff_id'] ?></td>
                                        <td><?= ($row['designation_id'] === '0' || $row['designation_id'] === null) ? '---' : $row['designation'] ?></td>
                                        <td><?= ($row['new_dep_id'] === '0' || $row['new_dep_id'] === null) ? '---' : $row['department'] ?></td>
                                        <td><?= ($row['new_branch_id'] === '0' || $row['new_branch_id'] === null) ? '---' : $row['branch'] ?></td>
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
                                        <td><?= ($row['created_by'] === '0' || $row['created_by'] === null) ? '---' : $row['created_by_name'] ?></td>
                                        <td><?= ($row['action_by'] === '0' || $row['action_by'] === null) ? '---' : $row['action_by_name'] ?></td>
                                        <td class="min-w-c">
                                            <?php
                                            if (get_permission('transfer_posting', 'is_edit')) {
                                            ?>
                                                <a href="<?= base_url('transfer_posting/edit/' . $row['id']) ?>" class="btn btn-circle icon btn-default">
                                                    <i class="fas fa-pen-nib"></i>
                                                </a>
                                                <?php
                                                if ($row['status'] === 'pending') {
                                                ?>
                                                    <button class='btn btn-success icon btn-circle' onclick="approve_reject_model('<?= base_url('transfer_posting/approve_reject/' . $row['id'] . '/approved') ?>','Are you sure?','Do you want to approve this request','Transfer Request Approved.')"><i class='fas fa-check'></i></button>
                                                    <button class='btn btn-danger icon btn-circle' onclick="approve_reject_model('<?= base_url('transfer_posting/approve_reject/' . $row['id'] . '/rejected') ?>','Are you sure?','Do you want to reject this request','Transfer Request Rejected.',true)"><i class='fas fa-times'></i></button>
                                            <?php }
                                            } ?>
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