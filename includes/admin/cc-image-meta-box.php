<?php

function cc_list_product_image_slots( $cpt = false ){
    $image_slots = array(
        'image1' => '_product_image_1',
        'image2' => '_product_image_2',
        'image3' => '_product_image_3',
        'image4' => '_product_image_4',
        'image5' => '_product_image_5',
	);
	$images = apply_filters('cc_list_product_images', $image_slots, $cpt );
	return $images;
}

function add_image_metabox() {
	$post_types = apply_filters( 'cc_post_types_with_images', array( 'cc_product' ) );
	foreach( $post_types as $post ) {
		add_meta_box( 'cc-gallery-images', __('Add Photos'), 'cc_gallery_images', $post, 'normal', 'core' );
    }
}

add_action( 'admin_init', 'add_image_metabox' );

function cc_save_image_metabox( $post_id ) { 
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;
    
   	$images = cc_list_product_image_slots();
    foreach( $images as $key => $value ){
	    if ( isset( $_POST[ $key ] ) ) {
			check_admin_referer('cc-gallery-images-save_' . $_POST['post_ID'], 'cc-gallery-images-nonce' );
			update_post_meta($post_id, $value, esc_html( $_POST[ $key ] ) ); 
		}
	}
}

add_action('save_post', 'cc_save_image_metabox'); 

function cc_gallery_images( $post ) {
	$list_images = cc_list_product_image_slots();

	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'quicktags' );
	wp_enqueue_script( 'jquery-ui-resizable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-position' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'wpdialogs' );
	wp_enqueue_script( 'wplink' );
	wp_enqueue_script( 'wpdialogs-popup' );
	wp_enqueue_script( 'wp-fullscreen' );
	wp_enqueue_script( 'editor' );
	wp_enqueue_script( 'word-count' );
	wp_enqueue_script( 'img-mb', CC_URL . 'resources/js/get-images.js', array( 'jquery','media-upload','thickbox','set-post-thumbnail' ) );
	wp_enqueue_style( 'thickbox' );

	wp_nonce_field( 'cc-gallery-images-save_' . $post->ID, 'cc-gallery-images-nonce' );

	echo '<div id="droppable">';
	$z =1;
	foreach( $list_images as $k => $i ){
		$meta = get_post_meta( $post->ID, $i, true );
		$img = (isset($meta)) ? '<img src="'. wp_get_attachment_thumb_url( $meta ) . '" width="100" height="100" alt="" draggable="false">' : '';
		echo '<div class="image-entry" draggable="true">';
		echo '<input type="hidden" name="' . $k .'" id="' . $k . '" class="id_img" data-num="' . $z . '" value="' . $meta . '">';
		echo '<div class="img-preview" data-num="'.$z.'">'.$img.'</div>';
		echo '<a href="javascript:void(0);" class="get-image button-secondary" data-num="'.$z.'">'._x('Add New','file').'</a><a href="javascript:void(0);" class="del-image button-secondary" data-num="'.$z.'">'.__('Delete').'</a>';
		echo '</div>';
		$z++;
	}
	echo '</div>';

    $page = CC_View::get( CC_PATH . 'views/admin/html-image-meta-box.php' );
    echo $page;
}

function cc_get_product_image_ids( $thumbnail = false, $id = false ){
	global $post;
	$the_id = ($id) ? $id : $post->ID;

	$list_images = cc_list_product_image_slots( get_post_type( $id ) );

	$a = array();
	foreach( $list_images as $key => $img ) {
		if ( $i = get_post_meta( $the_id, $img, true ) ) {
			$a[ $key ] = $i;
        }
	}

	if( $thumbnail ){
		$thumb_id = get_post_thumbnail_id( $the_id );
        if ( ! empty( $thumb_id ) ) {
            array_unshift( $a, get_post_thumbnail_id( $the_id ) );
        }
	} 

	return $a;
}

function cc_get_product_image_sources( $size = 'medium', $thumbnail = false, $id = false ) {
	if ( $id ) {
        $images = $thumbnail ? cc_get_product_image_ids( true, $id ) : cc_get_product_image_ids( false, $id );
    }
	else { 
        $images = $thumbnail ? cc_get_product_image_ids( true ) : cc_get_product_image_ids();
    }

	$o = array();

    foreach($images as $k => $i) {
		$o[ $k ] = wp_get_attachment_image_src( $i, $size );
    }

	return $o;
}

function cc_get_multi_product_image_sources( $small = 'thumbnail', $large = 'full', $thumbnail = false, $id = false ) {
	if($id) {
        $images = $thumbnail ? cc_get_product_image_ids( true, $id ) : cc_get_product_image_ids( false, $id );
    }
	else {
        $images = $thumbnail ? cc_get_product_image_ids( true ) : cc_get_product_image_ids();
    }

	$o = array();

    foreach( $images as $k => $i ) {
		$o[ $k ] = array( wp_get_attachment_image_src( $i, $small ), wp_get_attachment_image_src( $i, $large ) );
    }

	return $o;
}
