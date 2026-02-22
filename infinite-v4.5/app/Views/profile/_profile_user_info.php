<div class="profile-details">
    <div class="left">
        <img src="<?= getUserAvatar($user->avatar); ?>" alt="<?= esc($user->username); ?>" class="img-profile" width="200" height="200">
    </div>
    <div class="right">
        <div class="row">
            <div class="col-12">
                <h1 class="username"><?= esc($user->username); ?></h1>
            </div>
            <div class="col-12">
                <p class="p-last-seen">
                    <span class="last-seen <?= isUserOnline($user->last_seen) ? 'last-seen-online' : ''; ?>"> <i class="icon-circle"></i> <?= trans("last_seen"); ?>&nbsp;<?= timeAgo($user->last_seen); ?></span>
                </p>
            </div>
            <div class="col-12">
                <p class="description">
                    <?= esc($user->about_me); ?>
                </p>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center text-muted gap-2">
                    <div><?= trans("member_since"); ?>&nbsp;<?= dateFormatDefault($user->created_at); ?></div>
                    <?php if ($user->show_email_on_profile): ?>
                        <div class="separator text-light">|</div>
                        <div><i class="icon-envelope text-icon-color"></i>&nbsp;<?= esc($user->email); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 profile-buttons">
                    <?php if (authCheck()): ?>
                        <div class="d-block position-relative">
                        <?php if (user()->id != $user->id): ?>
                            <form action="<?= base_url('follow-unfollow-user'); ?>" method="post" class="form-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="following_id" value="<?= $user->id; ?>">
                                <?php if (isUserFollows($user->id, user()->id)): ?>
                                    <button class="btn btn-default btn-follow"><i class="icon-user-minus"></i>&nbsp;&nbsp;<?= trans("unfollow"); ?></button>
                                <?php else: ?>
                                    <button class="btn btn-default btn-follow"><i class="icon-user-plus"></i>&nbsp;&nbsp;<?= trans("follow"); ?></button>
                                <?php endif; ?>
                            </form>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="d-block position-relative">
                            <a href="<?= langBaseUrl('login'); ?>" class="btn btn-default btn-follow"><i class="icon-user-plus"></i>&nbsp;&nbsp;<?= trans("follow"); ?></a>
                        </div>
                    <?php endif; ?>

                    <ul class="d-flex align-items-center flex-wrap gap-2">
                        <?php $socialLinks = getSocialLinksArray($user, true);
                        if (!empty($socialLinks)):
                            foreach ($socialLinks as $socialLink):
                                if (!empty($socialLink['value'])):
                                    if ($socialLink['name'] == 'personal_website_url'):?>
                                        <li><a href="<?= $socialLink['value']; ?>" target="_blank"><i class="icon-globe"></i></a></li>
                                    <?php else: ?>
                                        <li><a href="<?= $socialLink['value']; ?>" target="_blank"><i class="icon-<?= esc($socialLink['name']); ?>"></i></a></li>
                                    <?php endif;
                                endif;
                            endforeach;
                        endif;
                        if ($user->show_rss_feeds): ?>
                            <li><a href="<?= langBaseUrl('rss/author/' . $user->slug); ?>"><i class="icon-rss"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
