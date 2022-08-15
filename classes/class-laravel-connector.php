<?php

namespace simpleCRM\classes;

if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

class LaravelConnector {
  public function __construct() {
    $this->load_hooks();
  }

  protected function load_hooks() {
    add_action('wp_ajax_nopriv_create_subscriber', [$this, 'create_subscriber']);
  }

  /**
   * @return void
   */
  public function create_subscriber() {
    $user = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $user = wp_authenticate($user, $password);

    if ($user instanceof \WP_User && $user->has_cap('manage_options')) {
      $request = (array)file_get_contents('php://input');
      $request = json_decode($request[0], true);
      $name = filter_var($request['name']);
      $user_email = filter_var($request['email'], FILTER_VALIDATE_EMAIL);
      $laravel_user_id = filter_var($request['laravel_user_id'], FILTER_VALIDATE_INT);
      $user_name = $user_email;
      $phone_number = filter_var($request['phone_number']);
      $desired_budget = filter_var($request['desired_budget'], FILTER_VALIDATE_FLOAT);
      $message = filter_var($request['message']);
      $laravel_user_url = filter_var($request['laravel_user_url'], FILTER_VALIDATE_URL);

      $user_id = username_exists( $user_email );

      if ( ! $user_id && false == email_exists( $user_email ) ) {
        $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
        $userdata = array(
          'user_login' =>  $user_name,
          'display_name' =>  $name,
          'first_name' =>  $name,
          'user_email' =>  $user_email,
          'user_pass'  =>  $random_password
        );
        $user_id = wp_insert_user( $userdata ) ;

        if (!empty($user_id)) {
          update_user_meta($user_id, 'phone_number', $phone_number);
          update_user_meta($user_id, 'desired_budget', $desired_budget);
          update_user_meta($user_id, 'message', $message);
          update_user_meta($user_id, 'laravel_user_id', $laravel_user_id);
          update_user_meta($user_id, 'laravel_user_url', $laravel_user_url);
          wp_send_json_success(new \WP_User($user_id));
        } else {
          wp_send_json_error(__('Can not create user'), 400);
        }
      } else {
        wp_send_json_error(__('User already exists.  Password inherited.'));
      }
    }
  }
}