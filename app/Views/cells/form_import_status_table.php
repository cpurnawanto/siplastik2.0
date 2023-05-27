<?php
$import_status_keys = array_keys($import_status[0]['data']);
?>

<table class="table">
    <thead>
        <tr>
            <?php foreach ($import_status_keys as $key) : ?>
                <th><?= ucwords(str_replace('_', ' ', $key)) ?></th>
            <?php endforeach; ?>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($import_status as $elements) : ?>
            <tr class="
            <?php if (!empty($elements['success'])) : ?> table-success
            <?php elseif (!empty($elements['errors'])) : ?> table-danger
            <?php endif; ?>
            ">
                <?php foreach ($import_status_keys as $key) : ?>
                    <td><?= $elements['data'][$key] ?></td>
                <?php endforeach; ?>
                <td>
                    <?php if (!empty($elements['success'])) : ?>
                        <?= $elements['success'] ?>
                    <?php elseif (!empty($elements['errors'])) : ?>
                        Terdapat kesalahan : <br>
                        <?php foreach ($elements['errors'] as $error) : ?>
                            - <?= $error ?><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>