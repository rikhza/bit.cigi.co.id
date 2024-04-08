<?php defined('ALTUMCODE') || die() ?>

<div class="form-group">
    <label for="cron"><?= l('admin_settings.cron.cron') ?></label>
    <input id="cron" name="cron" type="text" class="form-control" value="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron?key=' . settings()->cron->key ?>" readonly="readonly" />
    <small class="form-text text-muted"><?= sprintf(l('admin_settings.cron.last_execution'), isset(settings()->cron->cron_datetime) ? \Altum\Date::get_timeago(settings()->cron->cron_datetime) : '-') ?></small>
</div>

<div class="form-group">
    <label for="cron_broadcasts"><?= l('admin_settings.cron.broadcasts') ?></label>
    <input id="cron_broadcasts" name="cron_broadcasts" type="text" class="form-control" value="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron/broadcasts?key=' . settings()->cron->key ?>" readonly="readonly" />
    <small class="form-text text-muted"><?= sprintf(l('admin_settings.cron.last_execution'), isset(settings()->cron->broadcasts_datetime) ? \Altum\Date::get_timeago(settings()->cron->broadcasts_datetime) : '-') ?></small>
</div>
