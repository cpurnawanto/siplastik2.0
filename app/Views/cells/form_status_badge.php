<?php
if (!empty($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        Whoops! Ada kesalahan:
        <ul class="mb-1 pl-3">
            <?php foreach ($errors as $error) : ?>
                <li class="pl-3"><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php
endif;
if (!empty($success)) : ?>
    <div class="alert alert-success" role="alert">
        <?= esc($success) ?>
    </div>
<?php endif;
