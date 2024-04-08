<?php
if(
    !empty(settings()->ads->header_splash)
    && !$data->user->plan_settings->no_ads
): ?>
    <div class="container my-3"><?= settings()->ads->header_splash ?></div>
<?php endif ?>
