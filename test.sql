SELECT rownum, t1.ID, firstname, lastname, email, t2.user_id, t2.meta_key, t3.meta_value AS WP_first_name, t4.user_email
FROM `wp_aeh_import_full` AS t1
INNER JOIN `wp_usermeta` AS t2
ON (t1.ID = t2.meta_value AND t2.meta_key = 'aeh_imis_id')
INNER JOIN `wp_usermeta` AS t3
ON t3.meta_key = 'first_name' AND t2.user_id = t3.user_id
INNER JOIN `wp_users` AS t4
ON t4.ID = t2.user_id