<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Beranda</i></a></li>
    <?php for ($i = 0; $i < count($items); $i++) : ?>
        <?php if (empty($items[$i]['uri'])) : ?>
            <li class="breadcrumb-item active"><?= esc($items[$i]['text']) ?></li>
        <?php else : ?>
            <li class="breadcrumb-item"><a href=<?= base_url($items[$i]['uri']) ?>><?= esc($items[$i]['text']) ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>
</ol>