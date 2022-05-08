<?php

class DuplicateEvent
{
    public static function duplicate(int $post_id): int
    {
        global $wpdb;

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

        $ticket_post_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT post_id FROM wp_postmeta
                WHERE
                    (
                        meta_key = '_tec_tickets_commerce_event' OR
                        meta_key = '_tribe_rsvp_for_event' OR
                        meta_key = '_tribe_tpp_for_event'
                    ) AND meta_value = %s;
                ",
                $post_id
            )
        );

        $i = 1;
        foreach ( $ticket_post_ids as $id ) {
            $wpdb->query(
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
                FROM $wpdb->posts
                WHERE ID = $id"
            );

            $new_ticket_id = $wpdb->insert_id;

            $wpdb->query(
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
                WHERE post_id = $id;"
            );

            $wpdb->query(
                "UPDATE $wpdb->postmeta
                SET meta_value = $newpost_id
                WHERE post_id = $new_ticket_id AND meta_key IN
                (
                    '_tec_tickets_commerce_event',
                    '_tribe_rsvp_for_event',
                    '_tribe_tpp_for_event'
                );"
            );

            $wpdb->query(
                "UPDATE $wpdb->postmeta
                SET meta_value = '$newpost_id-$i-EVENT-DUPLICATE'
                WHERE post_id = $new_ticket_id AND meta_key = '_sku';"
            );

            $i++;
        }

        return $newpost_id;
    }
}
