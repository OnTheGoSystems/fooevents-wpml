<?php
/**
 * Plugin Name: FooEvents & WPML
 * Description: Glue plugin for FooEvents & WPML to play well together
 * Plugin URI: https://github.com/OnTheGoSystems/fooevents-wpml
 * Author: OnTheGoSystems
 * Author URI: https://github.com/OnTheGoSystems
 * Version: 1.0
 */

add_action( 'wpml_loaded', 'fooevents_wpml_loaded' );

function fooevents_wpml_loaded() {
	add_action( 'pre_get_posts', 'fooevents_get_tickets_in_event_wpml' );
}

function fooevents_get_tickets_in_event_wpml( $wp_query ) {
	$q = $wp_query->query_vars;
	if ( isset( $q['meta_query'] ) && isset( $q['post_type'] ) && in_array( 'event_magic_tickets', (array) $q['post_type'] ) ) {
		foreach ( (array) $q['meta_query'] as $i => $meta_query ) {
			if ( $meta_query['key'] === 'WooCommerceEventsProductID' && is_numeric( $meta_query['value'] ) ) {
				$trid = apply_filters( 'wpml_element_trid', null, $meta_query['value'], 'post_event_magic_tickets' );
				$values = apply_filters( 'wpml_get_element_translations', null, $trid, 'post_event_magic_tickets' );
				$q['meta_query'][ $i ]['value'] = wp_list_pluck( $values, 'element_id' );

				$wp_query->query_vars = $q;
			}
		}
	}
}