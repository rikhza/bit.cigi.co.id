<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\models;

class SplashPages extends Model {

    public function get_splash_pages_by_user_id($user_id) {

        /* Get the user splash_pages */
        $splash_pages = [];

        /* Try to check if the user posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('splash_pages?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $splash_pages_result = database()->query("SELECT * FROM `splash_pages` WHERE `user_id` = {$user_id}");
            while($row = $splash_pages_result->fetch_object()) {
                $row->settings = json_decode($row->settings);
                $splash_pages[$row->splash_page_id] = $row;
            }

            \Altum\Cache::$adapter->save(
                $cache_instance->set($splash_pages)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $user_id)
            );

        } else {

            /* Get cache */
            $splash_pages = $cache_instance->get();

        }

        return $splash_pages;

    }

}
