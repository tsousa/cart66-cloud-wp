<?php

class Test_Cloud_Messenger extends LB_Test {

    public function test_sending_version_info() {
        $messenger = new CC_Cloud_Messenger();
        $response = $messenger->send_version_info();
        $this->check( !empty( $response ), 'response was empty' );
    }

}

Test_Cloud_Messenger::run_tests();

