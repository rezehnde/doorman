<?php
/*
Plugin Name: Doorman
Plugin URI: https://rezehnde.com/
Description: Loging access to your wordpress
Version: 1.0.0
Author URI: https://rezehnde.com
*/

/**
 * Register loging out
 *
 * @return void
 */
function register_out() {
    $user = wp_get_current_user();
    register_log($user, 'out');
}
add_action('clear_auth_cookie', 'register_out', 10);

/**
 * Register login in
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

// lowercase first letter of functions. It is more standard for PHP
function getIP() 
{
    $tmp = getenv("HTTP_CLIENT_IP");

    if ( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp;

    $tmp = getenv("HTTP_X_FORWARDED_FOR");
    if( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp;

    $tmp = getenv("REMOTE_ADDR");
    if($tmp && !strcasecmp($tmp, "unknown"))
        return $tmp;

    return("unknown");
}