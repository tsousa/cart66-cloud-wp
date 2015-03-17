<?php

class Test_Notifications extends LB_Test {

    public function test_dismissing_a_notification() {
        CC_Admin_Notifications::dismiss( 'example_notification' );
        $notifications = CC_Admin_Notifications::get_notifications();
        $found = in_array( 'example_notification', $notifications );
        $this->check( $found, 'Example notifications should be in the notifications array: ' . print_r( $notifications, true ) );
    }

    public function test_clearing_notification() {
        CC_Admin_Notifications::dismiss( 'clear_me' );
        CC_Admin_Notifications::clear( 'clear_me' );
        $notifications = CC_Admin_Notifications::get_notifications();
        $found = in_array( 'clear_me', $notifications );
        $this->check( false === $found, 'clear_me should have been removed from the notificatoins array: ' . print_r( $notifications, true ) );
    }

    public function test_clear_all() {
        CC_Admin_Notifications::clear_all();
        $count = count( CC_Admin_Notifications::get_notifications() );
        $this->check( 0 == $count, 'Expecting count to be 0: ' . $count );
    }

    public function test_should_NOT_show_notification_when_dismissed() {
        $name = 'no_show';
        CC_Admin_Notifications::dismiss( $name );
        $show = CC_Admin_Notifications::show( $name );
        $this->check( false == $show, 'Should not show dismissed notification: ' . $name . ' :: ' . print_r( CC_Admin_Notifications::get_notifications(), true ) );
    }

    public function test_should_show_notification_when_NOT_dismissed() {
        $name = 'show_me';
        $show = CC_Admin_Notifications::show( $name );
        $this->check( true == $show, 'Should show notification for: ' . $name . ' :: ' . print_r( CC_Admin_Notifications::get_notifications(), true ) );
    }

}

Test_Notifications::run_tests();
