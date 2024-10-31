<?php

if (! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$prefix         = $wpdb->prefix;
$tblposts       = $prefix.'posts';
$tblpostmeta    = $prefix.'postmeta';
$tbloptions    = $prefix.'options';

$wpdb->query( "DELETE FROM {$tblposts} WHERE post_type = 'psydro_shortcode'" );
$wpdb->query( "DELETE meta FROM {$tblpostmeta} meta LEFT JOIN {$tblposts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
$wpdb->query( "DELETE FROM $tbloptions WHERE option_name = 'psydro_api_key';" );
$wpdb->query( "DELETE FROM $tbloptions WHERE option_name = 'psydro_api_key_res_error';" );
$wpdb->query( "DELETE FROM $tbloptions WHERE option_name = 'psydro_api_key_res_success';" );