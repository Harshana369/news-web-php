<?php $session = session();
if ($session->getFlashdata('errors')):
    $errors = $session->getFlashdata('errors'); ?>
    <div class="mb-3">
        <div class="alert alert-danger alert-message" role="alert">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif;
if ($session->getFlashdata('error')): ?>
    <div class="mb-3">
        <div class="alert alert-danger alert-message" role="alert">
            <i class="icon-times"></i>&nbsp;&nbsp;<?= $session->getFlashdata('error'); ?>
        </div>
    </div>
<?php elseif ($session->getFlashdata('success')): ?>
    <div class="mb-3">
        <div class="alert alert-success alert-message" role="alert">
            <i class="icon-check"></i>&nbsp;&nbsp;<?= $session->getFlashdata('success'); ?>
        </div>
    </div>
<?php endif; ?>