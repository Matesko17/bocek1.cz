<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

// Register the cron job on activation
function my_custom_cron_job_activation() {
    if ( ! wp_next_scheduled( 'my_custom_daily_event' ) ) {
        wp_schedule_event( strtotime( 'midnight' ), 'daily', 'my_custom_daily_event' );
    }
}
add_action( 'wp', 'my_custom_cron_job_activation' );

// Define the function to be executed by the cron job
function my_custom_daily_task() {
}
add_action( 'my_custom_daily_event', 'my_custom_daily_task' );

// Remove the cron job on deactivation
function my_custom_cron_job_deactivation() {
    wp_clear_scheduled_hook( 'my_custom_daily_event' );
}
register_deactivation_hook( __FILE__, 'my_custom_cron_job_deactivation' );