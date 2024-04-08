<?php defined('ALTUMCODE') || die() ?>

<div>
    <p class="text-muted"><?= l('admin_settings.ads.ads_help') ?></p>

    <div class="form-group">
        <label for="header"><?= l('admin_settings.ads.header') ?></label>
        <textarea id="header" name="header" class="form-control"><?= settings()->ads->header ?></textarea>
    </div>

    <div class="form-group">
        <label for="footer"><?= l('admin_settings.ads.footer') ?></label>
        <textarea id="footer" name="footer" class="form-control"><?= settings()->ads->footer ?></textarea>
    </div>

    <div class="form-group">
        <label for="header_biolink"><?= l('admin_settings.ads.header_biolink') ?></label>
        <textarea id="header_biolink" name="header_biolink" class="form-control"><?= settings()->ads->header_biolink ?></textarea>
    </div>

    <div class="form-group">
        <label for="footer_biolink"><?= l('admin_settings.ads.footer_biolink') ?></label>
        <textarea id="footer_biolink" name="footer_biolink" class="form-control"><?= settings()->ads->footer_biolink ?></textarea>
    </div>

    <div class="form-group">
        <label for="header_splash"><?= l('admin_settings.ads.header_splash') ?></label>
        <textarea id="header_splash" name="header_splash" class="form-control"><?= settings()->ads->header_splash ?></textarea>
    </div>

    <div class="form-group">
        <label for="footer_splash"><?= l('admin_settings.ads.footer_splash') ?></label>
        <textarea id="footer_splash" name="footer_splash" class="form-control"><?= settings()->ads->footer_splash ?></textarea>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
