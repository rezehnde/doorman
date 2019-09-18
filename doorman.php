<?php
/*
Plugin Name: Doorman
Plugin URI: https://github.com/rezehnde/doorman
Description: Loging access to your wordpress
Version: 1.0.0
Author URI: https://rezehnde.com
*/

/**
 * Register logout
 *
 * @return void
 */
function register_out() {
    $user = wp_get_current_user();
    register_log($user, 'out');
}
add_action('clear_auth_cookie', 'register_out', 10);

/**
 * Register login
 *
 * @param STRING $user_login
 * @param WP_User $user
 * @return void
 */
function register_in( $user_login, $user ) {
    register_log($user, 'in');
}
add_action('wp_login', 'register_in', 10, 2);

/**
 * Writes a log file with user information and operation details
 *
 * @param WP_User $user
 * @param STRING $operation
 * @return void
 */
function register_log($user, $operation) {
    $record = array(
        current_time('Y-m-d H:m:s'),
        getIP(),
        '\''.$user->user_login.'\'',
        '\''.$user->roles[0].'\'',
        '\''.$operation.'\''
    );
    file_put_contents(plugin_dir_path(__FILE__).'/doorman.log', implode(', ', $record).PHP_EOL, FILE_APPEND);
}

/**
 * Get client IP
 *
 * @return STRING
 */
function getIP() {
    $clientip = '';
    foreach ( array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {
        if ( isset( $_SERVER[ $key ] ) ) {
            foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
                $clientip = $ip;
                break;
            }
        }
    }
    return $clientip;
}