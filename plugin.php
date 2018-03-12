<?php
/**
 * Plugin Name: Similar Posts - WP REST API Addon
 * Description: This plugin adds a REST interface.
 * Author: Clintal
 * Author URI: https://www.clintal.com
 * Version: 2.75
 * License: LGPL
 **/

add_action( 'rest_api_init', 'slug_register_similarposts' );
function slug_register_similarposts() {
    register_rest_route( 'similarposts/v1', 'similarposts', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'slug_get_similarposts',
    ) );
    // register_rest_field( 'post',
    //     'similar_posts',
    //     array(
    //         'get_callback'    => 'slug_get_similarposts',
    //         'update_callback' => null,
    //         'schema'          => null,
    //     )
    // );
}

// function slug_get_similarposts( $object, $field_name, $request ) {
function slug_get_similarposts( WP_REST_Request $request ) {
    $GLOBALS['similar_posts_current_ID'] = $request['id']; // post ID
    $output = SimilarPosts::execute();
    $xml = new SimpleXMLElement($output);
    $results = array();
    foreach($xml as $node) {
        $similarpost = array();
        if ($node->count()) {
            $similarpost['url'] = $node->div[0]->a;
            $similarpost['title'] = $node->div[1]->a;
            $similarpost['summary'] = $node->div[1]->p;
            $similarpost['thumb_url'] = $node->div[0]->a;
            $results[] = $similarpost;
        }
    }
    return $results;
}