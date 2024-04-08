<?php
if(
    !empty(settings()->ads->footer_splash)
    && !$data->user->plan_settings->no_ads
): ?>
    <div class="container my-3"><?= settings()->ads->footer_splash ?></div>
<?php endif ?>
