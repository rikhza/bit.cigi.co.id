<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('splash-pages') ?>"><?= l('splash_pages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('splash_page_update.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-droplet mr-1"></i> <?= l('splash_page_update.header') ?></h1>

        <?= include_view(THEME_PATH . 'views/splash-pages/splash_page_dropdown_button.php', ['id' => $data->splash_page->splash_page_id, 'resource_name' => $data->splash_page->name]) ?>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->splash_page->name ?>" maxlength="64" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="title"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.title') ?></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= $data->splash_page->title ?>" maxlength="256" />
                </div>

                <div class="form-group" data-character-counter="textarea">
                    <label for="description" class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('splash_pages.input.description') ?></span>
                        <small class="text-muted" data-character-counter-wrapper></small>
                    </label>
                    <textarea id="description" name="description" class="form-control" maxlength="2048"><?= $data->splash_page->description ?></textarea>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="secondary_button_name"><i class="fas fa-fw fa-pen-to-square fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.secondary_button_name') ?></label>
                            <input type="text" id="secondary_button_name" name="secondary_button_name" class="form-control" value="<?= $data->splash_page->settings->secondary_button_name ?>" maxlength="256" />
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="secondary_button_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.secondary_button_url') ?></label>
                            <input type="text" id="secondary_button_url" name="secondary_button_url" class="form-control" value="<?= $data->splash_page->settings->secondary_button_url ?>" maxlength="1024" />
                        </div>
                    </div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="auto_redirect" name="auto_redirect" type="checkbox" class="custom-control-input" <?= $data->splash_page->auto_redirect ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="auto_redirect"><i class="fas fa-fw fa-square-up-right fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.auto_redirect') ?></label>
                    <small class="form-text text-muted"><?= l('splash_pages.input.auto_redirect_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="link_unlock_seconds"><i class="fas fa-fw fa-stopwatch fa-sm text-muted mr-1"></i> <?= l('splash_pages.input.link_unlock_seconds') ?></label>
                    <div class="input-group">
                        <input id="link_unlock_seconds" type="number" min="0" step="1" name="link_unlock_seconds" class="form-control" value="<?= $data->splash_page->link_unlock_seconds ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('splash_pages.input.link_unlock_seconds_help') ?></small>
                </div>

                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                    <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('splash_pages.advanced') ?>
                </button>

                <div class="collapse" id="advanced_container">
                    <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>">
                            <label for="custom_css"><i class="fab fa-fw fa-sm fa-css3 text-muted mr-1"></i> <?= l('splash_pages.input.custom_css') ?></label>
                            <textarea id="custom_css" class="form-control" name="custom_css" maxlength="8192"><?= $data->splash_page->settings->custom_css ?></textarea>
                        </div>
                    </div>

                    <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>">
                            <label for="custom_js"><i class="fab fa-fw fa-sm fa-js-square text-muted mr-1"></i> <?= l('splash_pages.input.custom_js') ?></label>
                            <textarea id="custom_js" class="form-control" name="custom_js" maxlength="8192"><?= $data->splash_page->settings->custom_js ?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'splash_page',
    'resource_id' => 'splash_page_id',
    'has_dynamic_resource_name' => true,
    'path' => 'splash-pages/delete'
]), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
