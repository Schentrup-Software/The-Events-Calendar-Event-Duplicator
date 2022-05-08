INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
SELECT post_author, post_date, post_date_gmt, post_content, CONCAT(post_title, ' - Duplicate'), post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count
FROM wp_posts
WHERE ID = 262;

SET @Event_Id = (SELECT id FROM wp_posts ORDER BY id DESC LIMIT 1);

INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
SELECT @Event_Id AS post_id, meta_key, meta_value
FROM wp_postmeta
WHERE post_id = 262;


INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
SELECT post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count
FROM wp_posts
WHERE ID = 263;

SET @Ticket_Id = (SELECT id FROM wp_posts ORDER BY id DESC LIMIT 1);

INSERT INTO wp_postmeta (post_id, meta_key, meta_value)
SELECT @Ticket_Id AS post_id, meta_key, meta_value
FROM wp_postmeta
WHERE post_id = 263;

UPDATE wp_postmeta
SET meta_value = @Event_Id
WHERE post_id = @Ticket_Id AND meta_key = '_tribe_tpp_for_event'

UPDATE wp_postmeta
SET meta_value = CONCAT(@Event_Id, '-1-COURSE-REGISTRATION')
WHERE post_id = @Ticket_Id AND meta_key = '_sku'
