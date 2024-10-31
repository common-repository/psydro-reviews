<?php

function psydro_curl_request( $url = '' ) {

    $response = json_decode(wp_remote_retrieve_body( wp_remote_get( str_replace('%s', $url, PSYDRO_REVIEWS_API_BASE_URL.'%s?key='. psydro()->setting->get('api_key'))) ), true);
    return $response;
}


add_filter( 'post_updated_messages', 'psydro_updated_messages' );
function psydro_updated_messages( $messages ) {
    global $post, $post_ID;
    $messages['psydro_shortcode'] = array(
         0 => '', // Unused. Messages start at index 1.
         1 => sprintf( __( 'Slider updated. ', 'Psydro Reviews' ) ),
         2 => '',
         3 => '',
         4 => __( 'Slider updated.', 'Psydro Reviews' ),
         5 => isset( $_GET['revision'] ) ? sprintf( __( 'Slider restored to revision from %s', 'Psydro Reviews' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
         6 => sprintf( __( 'Slider published.', 'Psydro Reviews' )),
         7 => __( 'Slider saved.', 'Psydro Reviews' ),
         8 => sprintf( __( 'Slider submitted.', 'Psydro Reviews' ) ),
    );
    return $messages;
}

add_action('admin_print_styles', 'psydro_remove_this_stuff');
function psydro_remove_this_stuff() {
 if ( get_post_type() === 'psydro_shortcode' ) {
 	?>
<style>
  #misc-publishing-actions, #minor-publishing-actions {
  display:none;
  }
</style>  
<?php 
} }


add_filter( 'post_row_actions', 'psydro_remove_row_actions', 10, 1 );
function psydro_remove_row_actions( $actions )
{
if( get_post_type() === 'psydro_shortcode' )
    unset( $actions['view'] );
return $actions;
}

add_filter( 'post_row_actions', 'psydro_remove_view_actions', 10, 1 );
function psydro_remove_view_actions( $actions )
{
if( get_post_type() === 'psydro_shortcode' )
    unset( $actions['view'] );
return $actions;
}

add_action( 'add_meta_boxes', function($post_type) {
  global $wp_meta_boxes;
    
  if($post_type !== 'psydro_shortcode') {
    return;
  }

  $exceptions = array(
        'rm-meta-box-id',
        'submitdiv'
    );


  if(!empty($wp_meta_boxes)) : foreach($wp_meta_boxes as $page => $page_boxes) :

            if(!empty($page_boxes)) : foreach($page_boxes as $context => $box_context) :

                    if(!empty($box_context)) : foreach($box_context as $box_type) :

                            if(!empty($box_type)) : foreach($box_type as $id => $box) :

                                    if(!in_array($id, $exceptions)) :
                                        remove_meta_box($id, $page, $context);
                                    endif;

                                endforeach;
                            endif;

                        endforeach;
                    endif;

                endforeach;
            endif;

        endforeach;
    endif;

}, 99, 2);