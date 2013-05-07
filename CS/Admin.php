<?php

class CS_Admin {

  protected $_options = null;

  public function __construct() {
    $this->_options = get_option('csm_access_notifications');
  }

  public function add_members_submenu() {
    add_submenu_page(
      'cloudswipe',
      __('CloudSwipe Members', 'cloudswipe_members'),
      __('Members', 'cloudswipe_members'),
      'administrator',
      'cloudswipe_members',
      array('CS_Admin', 'render_members_settings_page')
    );
  }

  public function render_members_settings_page() {
    $view = CS_PATH . 'views/admin/settings.phtml';
    echo CS_View::get($view);
  }

  public function register_settings() {

    add_settings_section(
      'csm_access_notifications',                                    // ID
      __('Access Notifications', 'cloudswipe_members'),              // Title
      array('CS_Admin','render_access_notifications_description'),  // Callback to render options
      'cloudswipe_members'                                           // Page where options will be located
    ); 

    $login_required = new stdClass();
    $login_required->id = 'login_required';
    $login_required->title = __('Login required', 'cloudswipe_members');
    $login_required->description = __('Text displayed when a user must log in to access the content', 'cloudswipe_members');

    $not_included = new stdClass();
    $not_included->id = 'not_included';
    $not_included->title = __('Not included', 'cloudswipe_members');
    $not_included->description = __('Text displayed when the content being accessed is not included in the member\'s subscription', 'cloudswipe_members');

    $fields = array($login_required, $not_included);
    $this->add_settings_fields_for_section($fields, 'cloudswipe_members', 'csm_access_notifications');

    register_setting(
      'csm_access_notifications',
      'csm_access_notifications'
    );
  }

  public function add_settings_fields_for_section($fields, $page, $section) {
    foreach($fields as $field) {
      $id = $section . '-' . $field->id;
      $name = $section . '[' . $field->id . ']';
      $description = $field->description;
      $title = $field->title;
      $callback = array($this, 'render_' . $field->id);
      $args = array('id' => $id, 'name' => $name, 'description' => $description);
      add_settings_field( $id, $title, $callback, $page, $section, $args );
    }
  }

  public function render_access_notifications_description() {
    //echo '<p>CSM Access Notifications</p>';
  }

  public function render_login_required($args) {
    $value = $this->get_option('login_required');
    $out = '<textarea id="' . $args['id'] . '" name="' . $args['name']. '" rows="10" cols="50" class="large-text">' . $value . '</textarea>';
    $out .= '<label for="' . $args['id'] . '">' . $args['description'] . '</label>';
    echo $out;
  }

  public function render_not_included($args) {
    $value = $this->get_option('not_included');
    $out = '<textarea id="' . $args['id'] . '" name="' . $args['name'] . '" rows="10" cols="50" class="large-text">' . $value . '</textarea>';
    $out .= '<label for="' . $args['id'] . '">' . $args['description'] . '</label>';
    echo $out;
  }

  public function get_option($key) {
    return isset($this->_options[$key]) ? $this->_options[$key] : '';
  }

}
