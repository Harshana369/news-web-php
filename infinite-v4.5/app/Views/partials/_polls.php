<?php $polls = getPolls($activeLang->id);
if (!empty($polls)): ?>
    <div class="widget-title">
        <h4 class="title"><?= trans("voting_poll"); ?></h4>
    </div>
    <div class="widget-body">
        <?php foreach ($polls as $poll):
            if ($poll->status == 1): ?>
                <div id="poll_<?= $poll->id; ?>" class="poll">
                    <div class="question">
                        <form id="formPoll_<?= $poll->id; ?>" data-form-id="<?= $poll->id; ?>" class="poll-form" method="post">
                            <input type="hidden" name="poll_id" value="<?= $poll->id; ?>">
                            <h5 class="title"><?= esc($poll->question); ?></h5>
                            <?php if (!empty($poll->options)):
                                foreach ($poll->options as $option): ?>
                                    <div class="option">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="option" id="pollOption<?= $option->id; ?>" value="<?= $option->id; ?>">
                                            <label class="form-check-label" for="pollOption<?= $option->id; ?>"><?= esc($option->option_text); ?></label>
                                        </div>
                                    </div>
                                <?php endforeach;
                            endif; ?>
                            <p class="button-cnt">
                                <button type="submit" class="btn btn-default" aria-label="vote"><?= trans("vote"); ?></button>
                                <button type="button" onclick="viewPollResults('<?= $poll->id; ?>');" class="a-view-results" aria-label="show results"><?= trans("view_results"); ?></button>
                            </p>
                            <div id="poll-required-message-<?= $poll->id; ?>" class="poll-error-message">
                                <?= trans("please_select_option"); ?>
                            </div>
                            <div id="poll-error-message-<?= $poll->id; ?>" class="poll-error-message">
                                <?= trans("voted_message"); ?>
                            </div>
                        </form>
                    </div>
                    <div class="result" id="poll-results-<?= $poll->id; ?>">
                        <?= view("partials/_poll_results", ['poll' => $poll]); ?>
                    </div>
                </div>
            <?php endif;
        endforeach; ?>
    </div>
<?php endif; ?>