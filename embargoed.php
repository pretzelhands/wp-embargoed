<?php

/**
 * Plugin Name:       Embargoed
 * Description:       A plugin to block all requests from Russia to any WordPress site and display a pro-Ukraine message instead
 * Version:           1.0
 * Requires PHP:      7.2
 * Author:            Richard Blechinger
 * Author URI:        https://pretzelhands.com
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 */

require __DIR__ . '/vendor/autoload.php';

use GeoIp2\Database\Reader;

function get_user_ip()
{
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'];
}

add_action( 'init', function() {
    $reader = new Reader( __DIR__ . '/data/geoip.mmdb' );
    $record = $reader->country( get_user_ip() );

    // Go Ukraine 🇺🇦
    if ( $record && $record->country->isoCode === 'RU' ) {
        $template = file_get_contents( __DIR__ . '/data/embargoed.html' );

        echo $template;
        die();
    }
} );
