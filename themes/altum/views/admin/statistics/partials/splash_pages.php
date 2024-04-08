<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fas fa-fw fa-droplet fa-xs text-primary-900"></i> <?= l('admin_statistics.splash_pages.header') ?></h2>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['splash_pages']) ?></span> <?= l('admin_statistics.splash_pages.chart') ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="splash_pages"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Prepare chart */
    let splash_pages_chart = document.getElementById('splash_pages').getContext('2d');
    color_gradient = splash_pages_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    /* Display chart */
    new Chart(splash_pages_chart, {
        type: 'line',
        data: {
            labels: <?= $data->splash_pages_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('admin_statistics.splash_pages.chart')) ?>,
                data: <?= $data->splash_pages_chart['splash_pages'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
