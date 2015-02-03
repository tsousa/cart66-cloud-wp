<?php

class Test_Notification_Manager extends LB_Test {

    public function test_dismissing_a_notification() {
        $manager = new CC_Admin_Notification_Manager();
        $manager->dismiss( 'example_notification' );
        $notifications = $manager->get_notifications();
        $found = in_array( 'example_notification', $notifications );
        $this->check( $found, 'Example notifications should be in the notifications array: ' . print_r( $notifications, true ) );
    }

    public function test_clearing_notification() {
        $manager = new CC_Admin_Notification_Manager();
        $manager->dismiss( 'clear_me' );
        $manager->clear( 'clear_me' );
        $notifications = $manager->get_notifications();
        $found = in_array( 'clear_me', $notifications );
        $this->check( false === $found, 'clear_me should have been removed from the notificatoins array: ' . print_r( $notifications, true ) );
    }

    public function test_clear_all() {
        $manager = new CC_Admin_Notification_Manager();
        $manager->clear_all();
        $count = count( $manager->get_notifications() );
        $this->check( 0 == $count, 'Expecting count to be 0: ' . $count );
    }

    public function test_should_NOT_show_notification_when_dismissed() {
        $name = 'no_show';
        $manager = new CC_Admin_Notification_Manager();
        $manager->dismiss( $name );
        $show = $manager->show( $name );
        $this->check( false == $show, 'Should not show dismissed notification: ' . $name . ' :: ' . print_r( $manager->get_notifications(), true ) );
    }

    public function test_should_show_notification_when_NOT_dismissed() {
        $name = 'show_me';
        $manager = new CC_Admin_Notification_Manager();
        $show = $manager->show( $name );
        $this->check( true == $show, 'Should show notification for: ' . $name . ' :: ' . print_r( $manager->get_notifications(), true ) );
    }
}

Test_Notification_Manager::run_tests();
