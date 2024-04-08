<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

use Altum\Alerts;

class SplashPages extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!settings()->links->splash_page_is_enabled) {
            redirect();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by('splash_page_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `splash_pages` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('splash_pages?' . $filters->get_get() . '&page=%d')));

        /* Get the splash_pages list for the user */
        $splash_pages = [];
        $splash_pages_result = database()->query("SELECT * FROM `splash_pages` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $splash_pages_result->fetch_object()) $splash_pages[] = $row;

        /* Export handler */
        process_export_csv($splash_pages, 'include', ['splash_page_id', 'user_id', 'name', 'color', 'last_datetime', 'datetime'], sprintf(l('splash_pages.title')));
        process_export_json($splash_pages, 'include', ['splash_page_id', 'user_id', 'name', 'color', 'last_datetime', 'datetime'], sprintf(l('splash_pages.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'splash_pages' => $splash_pages,
            'total_splash_pages' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('splash-pages/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!settings()->links->splash_page_is_enabled) {
            redirect();
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.splash_pages')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('splash-pages');
        }

        if(empty($_POST)) {
            redirect('splash-pages');
        }

        $splash_page_id = (int) $_POST['splash_page_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$splash_page = db()->where('splash_page_id', $splash_page_id)->where('user_id', $this->user->user_id)->getOne('splash_pages', ['splash_page_id', 'name'])) {
            redirect('splash-pages');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the splash_page */
            db()->where('splash_page_id', $splash_page_id)->delete('splash_pages');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('splash_pages?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $splash_page->name . '</strong>'));

            redirect('splash-pages');
        }

        redirect('splash-pages');
    }

}
