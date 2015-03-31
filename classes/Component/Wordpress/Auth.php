<?php



class Component_Wordpress_Auth extends Component_Wordpress  {

	static function userCreate($values = array(), $args = array(), Component_Message &$msg)
	{
		$created_user_id = null;

		$component_wp_auth_obj = new Component_Wordpress_Auth();
		$created_user_id = $component_wp_auth_obj->createUser($values, $args, $msg);

		return $created_user_id;
	}



	public function createUser($values = array(), $args = array(), Component_Message &$msg)
	{
		$created_user_id = null;
		$args_wp_insert_user = array();

		if(!isset($values['email']) || empty($values['email'])) {
			$msg->addError('email is a required field');
		} else {
			$args_wp_insert_user['user_email'] = $values['email'];
			$args_wp_insert_user['user_login'] = $args_wp_insert_user['user_email'];
		}

		if(!isset($values['password']) || empty($values['password'])) {
			$msg->addError('password is a required field');
		} else {
			$args_wp_insert_user['user_pass'] = $values['password'];
		}	

		if(!$msg->hasErrors()) {		
			$created_user_id = wp_insert_user($args_wp_insert_user);
			if(is_wp_error($created_user_id)) {
				$msg->addError('There were errors creating wp user');
				foreach($created_user_id->get_error_messages() as $message) {
					$msg->addError($message);
				}
			} 
		} 	

		return $created_user_id;
	}



	static function userDelete($email = null, $args = array(), Component_Message &$msg)
	{
		$delete_success = false;

		$component_wp_auth_obj = new Component_Wordpress_Auth();
		$delete_success = $component_wp_auth_obj->deleteUser($email, $args, $msg);

		return $delete_success;
	}



	public function deleteUser($email = null, $args = array(), Component_Message &$msg)
	{
		$delete_success = false;

		if(is_null($email)) {
			$msg->addError('email is a required field');
		}

		if(!$msg->hasErrors()) {	
			$wp_user = get_user_by_email( $email );
			if($wp_user->ID) {
				$delete_success = wp_delete_user($wp_user->ID);
			}
		}	

		return $delete_success;		
	}



	static function userLogin($values = array(), $args = array(), Component_Message &$msg)
	{
		$user = null;

		if(!isset($values['username']) || empty($values['username'])) {
			$msg->addError('username is a required field');
		}

		if(!isset($values['password']) || empty($values['password'])) {
			$msg->addError('password is a required field');
		}

		if(!$msg->hasErrors()) {
			$creds = array();
			$creds['user_login'] = $values['username'];
			$creds['user_password'] = $values['password'];
			$creds['remember'] = array_key_exists('remember', $values) ? (bool) $values['remember'] : false;

			// Try to sign on
			$user = wp_signon( $creds );
			// Check if there was an error
			if( is_wp_error( $user ) ) {
				// Set our processing errors
				if( in_array( 'incorrect_password', $user->get_error_codes() ) ) {
					$msg->addError('Incorrect password.');
				}
				if( in_array( 'empty_password', $user->get_error_codes() ) ) {
					$msg->addError('Missing password.');
				}
				if( in_array( 'invalid_username', $user->get_error_codes() ) ) {
					$msg->addError('Incorrect username.');
				}
				if( in_array( 'empty_username', $user->get_error_codes() ) ) {
					$msg->addError('Missing username.');
				}
				if( in_array( 'too_many_retries', $user->get_error_codes() ) ) {
					$msg->addError('Too many attempts. Please try again later.');
				}

				$user = null;
			} 
		}		

		return $user;
	}



	static function userLogout($values = array(), $args = array(), Component_Message &$msg)
	{
		wp_logout();
	}

}