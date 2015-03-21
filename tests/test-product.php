<?php

class Test_Product extends LB_Test {

    public function _test_page_check_should_find_post() {
        $title = "Solar Watch";
        $product = new CC_Product();
        $post_id = $product->page_exists( $title );
        $this->check( 141 == $post_id, "Expecting the page id to be null but found $post_id" );
    }

    public function _test_page_check_should_NOT_find_post() {
        $title = "Missing Page";
        $product = new CC_Product();
        $post_id = $product->page_exists( $title );
        $this->check( null == $post_id, "Expecting the page id to be null but found $post_id" );
    }

    public function test_attach_image_to_post() {
        $post_id = 96; // Bose SoundLink on wpdev
        $url =  'http://cart66-com.s3.amazonaws.com/images/fast-track/black-tee.png';
        $meta_key = '_product_image_2';
        $product = new CC_Product();
        $attachment_id = $product->attach_image_to_post( $post_id, $url );
        if ( is_numeric( $attachment_id ) ) {
            update_post_meta( $post_id, $meta_key, $attachment_id );
        }
        $this->check( is_numeric( $attachment_id ), print_r( $attachment_id, true ) );
    }

}

Test_Product::run_tests();


