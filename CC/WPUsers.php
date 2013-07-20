<?php
class CC_WPUsers {
  
  public function create_user(array $user_data) {
    // Check to see if the user already exists
    $user_id = username_exists($user_data['username']);
    if(!$user_id && !email_exists($user_data['email'])) {
      // Create user
      $user_id = wp_create_user($user_data['username'], $user_data['password'], $user_data['email']);
      add_role('Cart66Cloud', 'Cart66 Cloud Account', array('read' => rue));
      $user = new WP_User($user_id);
      $user->set_role('Cart66Cloud');
      
      // Manually set the password that is already hashed
      global $wpdb;
      $sql = sprintf("UPDATE %s SET user_pass='%s' WHERE ID=%d", $wpdb->users, $user_data['password'], (int) $user_id);
      $wpdb->query($sql);
      // Update all other user data
      $user_data['ID'] = $user_id;
      unset($user_data['username']);
      unset($user_data['password']);
      wp_update_user($user_data);
    }
    return $user_id;
    
  }
  
  public function login_user($user_id) {
    $user_data = get_userdata($user_id);
    $user_login = $user_data->$user_login;
    wp_set_current_user($user_id, $user_login);
    wp_set_auth_cookie($user_id);
    do_action('wp_login', $user_login);
  }
  
  public function login_request($user_login, $user) {
    // This function runs everytime there is a WordPress login attempt
    
  }
  
}