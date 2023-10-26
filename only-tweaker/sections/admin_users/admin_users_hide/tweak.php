<?php

class OT_admin_users_hide_Tweak {
	function settings() {
		$user_query = new WP_User_Query(array( 'number' => 100, 'ot_show_all' => true ) );
		$users = array();
//var_dump($user_query->results);
		if ( ! empty( $user_query->results ) ) {
			foreach ( $user_query->results as $user ) {
				$users[$user->ID] = $user->display_name;

			}
		}

//		$all_users = get_users();
//		if ( ! empty( $all_users ) ) {
//			foreach ( $all_users as $user ) {
//				$users[$user->ID] = $user->display_name;
//
//			}
//		}

		return OT_Helper::field( 'admin_users_hide', 'select', array(
			'title' => __( 'Hide users', OT_SLUG ),
			'multi'    => true,
			'desc'  => __( 'Users will be totally hidden in list.', OT_SLUG ),
			'options' => $users,
		) );
	}

	function tweak() {
		if($this->value && !is_array($this->value)) {
            $this->value = array($this->value);
        }
		if($this->value && is_array($this->value)) {
			add_action('plugins_loaded', array($this, '_load'));
		}
	}

	function _load() {
		add_action('pre_user_query', array($this, 'pre_user_query'));
	}

	function pre_user_query($user_search) {
		global $wpdb;

		if(isset($user_search->query_vars['ot_show_all']) && $user_search->query_vars['ot_show_all']) {
		    return;
        }

		$ids = array();
		foreach($this->value as $id) {
			$ids[] = intval($id);
		}
		$ids = array_unique($ids);
		if(!count($ids)) return;

		$user_search->query_where = str_replace('WHERE 1=1',
			"WHERE 1=1 AND {$wpdb->users}.id NOT IN(".join(',',$ids).")", $user_search->query_where);
	}
}