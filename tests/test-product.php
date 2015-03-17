<?php

class Test_Product extends LB_Test {

    public function test_page_check_should_find_post() {
        $title = "Solar Watch";
        $product = new CC_Product();
        $post_id = $product->page_exists( $title );
        $this->check( 141 == $post_id, "Expecting the page id to be null but found $post_id" );
    }

    public function test_page_check_should_NOT_find_post() {
        $title = "Missing Page";
        $product = new CC_Product();
        $post_id = $product->page_exists( $title );
        $this->check( null == $post_id, "Expecting the page id to be null but found $post_id" );
    }

}

Test_Product::run_tests();


