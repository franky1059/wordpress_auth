<?php



class Component_Wordpress {

	public function __construct($wordpress_dir = null, $wordpress_db_host = null, $wordpress_db_user = null, $wordpress_db_passwd = null, $wordpress_url = null)
	{

		if(is_null($wordpress_dir)) {
			$wordpress_dir = WP_DIR;
		}

		if(is_null($wordpress_db_host)) {
			$wordpress_db_host = WP_DB_HOST;
		}

		if(is_null($wordpress_db_user)) {
			$wordpress_db_user = WP_DB_USER;
		}

		if(is_null($wordpress_db_passwd)) {
			$wordpress_db_passwd = WP_DB_PASSWD;
		}

		if(is_null($wordpress_url)) {
			$wordpress_url = WP_URL;
		}	

		//require_once $wordpress_dir.'wp-config.php';
		//require_once $wordpress_dir.'wp-load.php';
		//define('WP_USE_THEMES', false);
		//require_once $wordpress_dir.'wp-blog-header.php';
	}


}