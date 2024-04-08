<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\Domain;

class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'type'], ['url'], ['datetime', 'clicks', 'url']));
        $filters->set_default_order_by('link_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = \Altum\Cache::cache_function_result('links_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('links', 'count(*)');
        });
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('links?' . $filters->get_get() . '&page=%d')));

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Get the links list for the project */
        $links_result = database()->query("
            SELECT 
                *
            FROM 
                `links`
            WHERE 
                `user_id` = {$this->user->user_id}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $links = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = $row->domain_id ? $domains[$row->domain_id]->scheme . $domains[$row->domain_id]->host . '/' . $row->url : SITE_URL . $row->url;
            $links[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get statistics */
        if(count($links)) {
            $links_chart = [];
            $start_date_query = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s');

            $track_links_result_query = "
                SELECT
                    COUNT(`id`) AS `pageviews`,
                    SUM(`is_unique`) AS `visitors`,
                    DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `formatted_date`
                FROM
                    `track_links`
                WHERE   
                    `user_id` = {$this->user->user_id} 
                    AND (`datetime` BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ";

            $links_chart = \Altum\Cache::cache_function_result('track_links?user_id=' . $this->user->user_id, null, function() use ($track_links_result_query) {
                $links_chart = [];

                $track_links_result = database()->query($track_links_result_query);

                /* Generate the raw chart data and save logs for later usage */
                while($row = $track_links_result->fetch_object()) {
                    $label = \Altum\Date::get($row->formatted_date, 5);

                    $links_chart[$label] = [
                        'pageviews' => $row->pageviews,
                        'visitors' => $row->visitors
                    ];
                }

                return $links_chart;
            }, 60 * 60 * 12);

            $links_chart = get_chart_data($links_chart);
        }

        /* Some statistics for the widgets */
        if(settings()->links->shortener_is_enabled) {
            $link_links_total = \Altum\Cache::cache_function_result('link_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'link')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->files_is_enabled) {
            $file_links_total = \Altum\Cache::cache_function_result('file_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'file')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->vcards_is_enabled) {
            $vcard_links_total = \Altum\Cache::cache_function_result('vcard_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'vcard')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->biolinks_is_enabled) {
            $biolink_links_total = \Altum\Cache::cache_function_result('biolink_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'biolink')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->events_is_enabled) {
            $event_links_total = \Altum\Cache::cache_function_result('event_links_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->where('type', 'event')->getValue('links', 'count(*)');
            });
        }

        if(settings()->links->qr_codes_is_enabled) {
            $qr_codes_total = \Altum\Cache::cache_function_result('qr_codes_total?user_id=' . $this->user->user_id, null, function() {
                return db()->where('user_id', $this->user->user_id)->getValue('qr_codes', 'count(`qr_code_id`)');
            });
        }

        /* Delete Modal */
        $view = new \Altum\View('links/link_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Create Link Modal */
        $domains = (new Domain())->get_available_domains_by_user($this->user);
        $data = [
            'domains' => $domains
        ];

        $view = new \Altum\View('links/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run($data), 'modals');

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Prepare the Links View */
        $data = [
            'links'             => $links,
            'pagination'        => $pagination,
            'filters'           => $filters,
            'projects'          => $projects,
            'links_types'       => require APP_PATH . 'includes/links_types.php',
        ];
        $view = new \Altum\View('links/links_content', (array) $this);
        $this->add_view_content('links_content', $view->run($data));

        /* Prepare the View */
        $data = [
            'links_chart'       => $links_chart ?? false,

            /* Widgets stats */
            'event_links_total'         => $event_links_total ?? null,
            'qr_codes_total'            => $qr_codes_total ?? null,
            'vcard_links_total'         => $vcard_links_total ?? null,
            'link_links_total'          => $link_links_total ?? null,
            'file_links_total'          => $file_links_total ?? null,
            'biolink_links_total'       => $biolink_links_total ?? null,
        ];

        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
