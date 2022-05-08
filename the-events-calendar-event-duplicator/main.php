<?php
/**
 * Plugin Name: The Events Calendar Event Duplicator
 * Description: A plugin the fully duplicates the event calendar events
 * Version: 1.0
 * Author: Schentrup Software LLC
 **/

require_once 'duplicate_event.php';

/**
 * Run on plugin activation
 *
 * @return void
 */
function teced_plugin_init() {
	//ensure the events calendar is isnstalled
    //This can be done by checking the correct tables are in place I think

    if ( current_user_can( 'edit_others_posts' ) ) {
        add_filter( 'post_row_actions','teced_action_row', 10, 2 );
        add_action( 'admin_action_teced_duplicate_event', 'teced_duplicate_event' );
    }
}

/**
 * Filters through the post types and adds our new action to course post types
 *
 * @param string[] $actions
 * @param WP_Post $post
 * @return array
 */
function teced_action_row(array $actions, WP_Post $post): array {

    if ($post->post_type == 'tribe_events'){
        $actions['event_duplicate'] = '<a href="admin.php?action=teced_duplicate_event&post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
    }

    return $actions;
}

function teced_duplicate_event(): void {
    global $wpdb;

    $post_id = ( isset( $_GET['post'] ) ) ? ( $_GET['post'] ) : ( null );

    // Verify there is a post with such a number.
    $post = get_post( (int) $post_id );
    if ( empty( $post ) ) {
        return;
    }

    $new_post_id = DuplicateEvent::duplicate($post_id);

    // Redirect to new posts edit page.
    $redirect = admin_url( 'post.php?post=' . $new_post_id . '&action=edit' );
    wp_safe_redirect( $redirect );

    // We are done.
    die;
}

add_action( 'plugins_loaded', 'teced_plugin_init' );
