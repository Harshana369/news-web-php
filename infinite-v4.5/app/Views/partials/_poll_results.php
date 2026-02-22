<?php if (!empty($poll)): ?>
    <div class="result">
        <h5 class="title"><?= esc($poll->question); ?></h5>
        <p class="total-vote"><?= trans("total_vote"); ?>&nbsp;<?= $poll->total_votes; ?></p>
        <?php if (!empty($poll->options)):
            foreach ($poll->options as $option):?>
                <span><?= esc($option->option_text); ?></span>
                <div class="progress">
                    <span><?= esc($option->percentage); ?>&nbsp;%</span>
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?= esc($option->votes); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= esc($option->percentage); ?>%"></div>
                </div>
            <?php endforeach;
        endif; ?>
        <p>
            <button type="button" onclick="viewPollOptions('<?= $poll->id; ?>');" class="a-view-results m-0"><?= trans("view_options"); ?></button>
        </p>
    </div>
<?php endif; ?>