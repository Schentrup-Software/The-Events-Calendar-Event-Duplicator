<?php

class DuplicatePost
{
    public static function duplicate(int $post_id): int
    {
        global $wpdb;
        /*
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $wpdb->posts
                (
                    post_author,
                    post_date,
                    post_date_gmt,
                    post_content,
                    post_title,
                    post_excerpt,
                    post_status,
                    comment_status,
                    ping_status,
                    post_password,
                    post_name,
                    to_ping,
                    pinged,
                    post_modified,
                    post_modified_gmt,
                    post_content_filtered,
                    post_parent,
                    guid,
                    menu_order,
                    post_type,
                    post_mime_type,
                    comment_count
                )
                SELECT
                    post_author,
                    post_date,
                    post_date_gmt,
                    post_content,
                    CONCAT(post_title, ' - Duplicate'),
                    post_excerpt,
                    post_status,
                    comment_status,
                    ping_status,
                    post_password,
                    post_name,
                    to_ping,
                    pinged,
                    post_modified,
                    post_modified_gmt,
                    post_content_filtered,
                    post_parent,
                    guid,
                    menu_order,
                    post_type,
                    post_mime_type,
                    comment_count
                FROM $wpdb->posts
                WHERE ID = %s",
                $post_id
            )
        );

        $newpost_id = $wpdb->insert_id;

        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $wpdb->postmeta
                (
                    post_id,
                    meta_key,
                    meta_value
                )
                SELECT
                    $newpost_id AS post_id,
                    meta_key,
                    meta_value
                FROM $wpdb->postmeta
                WHERE post_id = %s;",
                $post_id
            )
        );
*/
        $tribe_ticket_repository = new Tribe__Tickets__Ticket_Repository();

        $ticket_post_ids = $tribe_ticket_repository->filter_by_event($post_id);

        return 5;

        /*foreach ( $ticket_post_ids as $id ) {
            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO $wpdb->posts
                    (
                        post_author,
                        post_date,
                        post_date_gmt,
                        post_content,
                        post_title,
                        post_excerpt,
                        post_status,
                        comment_status,
                        ping_status,
                        post_password,
                        post_name,
                        to_ping,
                        pinged,
                        post_modified,
                        post_modified_gmt,
                        post_content_filtered,
                        post_parent,
                        guid,
                        menu_order,
                        post_type,
                        post_mime_type,
                        comment_count
                    )
                    SELECT
                        post_author,
                        post_date,
                        post_date_gmt,
                        post_content,
                        CONCAT(post_title, ' - Duplicate'),
                        post_excerpt,
                        post_status,
                        comment_status,
                        ping_status,
                        post_password,
                        post_name,
                        to_ping,
                        pinged,
                        post_modified,
                        post_modified_gmt,
                        post_content_filtered,
                        post_parent,
                        guid,
                        menu_order,
                        post_type,
                        post_mime_type,
                        comment_count
                    FROM $wpdb->posts
                    WHERE ID = %s",
                    $id
                )
            );

            $new_ticket_id = $wpdb->insert_id;

            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO $wpdb->postmeta
                    (
                        post_id,
                        meta_key,
                        meta_value
                    )
                    SELECT
                        $new_ticket_id AS post_id,
                        meta_key,
                        meta_value
                    FROM $wpdb->postmeta
                    WHERE post_id = %s;",
                    $id
                )
            );

            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE wp_postmeta
                    SET meta_value = $newpost_id
                    WHERE post_id = $new_ticket_id AND meta_key = '_tribe_tpp_for_event';",
                    $id
                )
            );
        }*/
    }
}
