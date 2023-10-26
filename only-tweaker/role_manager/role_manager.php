<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if(!class_exists('OT_Role_Manager')) {
	class OT_Role_Manager {
		var $permission;
		var $helper;

		function __construct($helper, $permission) {
		    $this->helper = $helper;
			$this->permission = $permission;

//			$this->init();
			add_action('init', array($this, 'init'));
		}

		function init() {
			if(!current_user_can($this->permission)) return;

			add_action( 'wp_ajax_ot_rm_get', array($this, 'ajaxGet') );
			add_action( 'wp_ajax_ot_rm_get-network', array($this, 'ajaxGetNetwork') );
			add_action( 'wp_ajax_ot_rm_save', array($this, 'ajaxSave') );
			add_action( 'wp_ajax_ot_rm_save-network', array($this, 'ajaxSaveNetwork') );

			$handle = $this->helper->script( 'script', __FILE__, array('force_min' => false) );
            $this->helper->localizeScript( $handle, 'roleManagerConfig', array(
				'title'              => __( 'Role Manager', OT_SLUG ),
				'loading'            => __( 'Loading...', OT_SLUG ),
				'saving'             => __( 'Saving...', OT_SLUG ),
				'deleteConfirmation' => __( 'Do you really want to delete?', OT_SLUG ),
				'newRoleNameConfirmation' => __( 'Enter new role name:', OT_SLUG ),
				'createRole'         => __( 'Create new role...', OT_SLUG ),
				'createRoleTitle'    => __( 'New role creating', OT_SLUG ),
				'create'             => __( 'Create', OT_SLUG ),
				'save'               => __( 'Save', OT_SLUG ),
				'cancel'             => __( 'Cancel', OT_SLUG ),

				'form_allRequired'    => __( 'All form fields are required.', OT_SLUG ),
				'form_ID'             => __( 'ID', OT_SLUG ),
				'form_Name'             => __( 'Name', OT_SLUG ),
				'form_checkLengthMessage' => __( 'Length of %s must be between %s and %s.', OT_SLUG ),
				'form_checkRoleIDExistsMessage' => __( 'ID already exists.', OT_SLUG ),
				'form_checkRoleNameExistsMessage' => __( 'Name already exists.', OT_SLUG ),
				'form_checkRoleIDRegExpMessage' => __( 'Role ID may consist of a-z, 0-9, underscores, spaces and must begin with a letter.', OT_SLUG ),


				'nonce'              => wp_create_nonce( 'roleManager' )
			) );

			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style("wp-jquery-ui-dialog");
		}

		private function _getRoles() {
			global $wp_roles;
			if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();

//			$editable_roles = apply_filters('editable_roles', $wp_roles->roles);
			return $wp_roles->roles;
		}
		private function _getDeprecatedCapabilities() {
			$caps = array(
				'level_0'    => 0,
				'level_1'    => 0,
				'level_2'    => 0,
				'level_3'    => 0,
				'level_4'    => 0,
				'level_5'    => 0,
				'level_6'    => 0,
				'level_7'    => 0,
				'level_8'    => 0,
				'level_9'    => 0,
				'level_10'   => 0,
				'edit_files' => 0
			);

			//$caps['unfiltered_html'] = 0; for Multi site

			return $caps;
		}

		function ajaxGetNetwork() {
            if(!current_user_can('manage_network_options')) return;
		    return $this->ajaxGet();
        }

		function ajaxGet() {
			if(!current_user_can($this->permission)) return;
			check_ajax_referer( 'roleManager' );

//            switch_to_blog( 3 );

			wp_send_json( array(
				'roles'                   => $this->_getRoles(),
				'deprecated_capabilities' => $this->_getDeprecatedCapabilities(),
			) );
			exit;
		}

		function ajaxSaveNetwork() {
            if(!current_user_can('manage_network_options')) return;

            $blog_list = wp_get_sites();
            foreach ($blog_list AS $blog) {
                switch_to_blog( $blog['blog_id'] );
                $this->ajaxSave(true);
            }

            wp_send_json(array(
                'success' => true
            ));
            exit;
        }

		function ajaxSave($notExit = false) {
			if(!current_user_can($this->permission)) return;
			check_ajax_referer( 'roleManager' );
			if(!isset($_POST['data']) || !is_array($_POST['data'])) return;
			$data = $_POST['data'];

			global $wp_roles;
			if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();

			foreach($data as $roleId => $roleMeta) {
				if ( isset( $wp_roles->roles[$roleId] ) && $roleMeta['name'] && $roleMeta['name'] !== $wp_roles->roles[$roleId]['name'] && !empty($roleMeta['name']) ) {
					$display_name = $roleMeta['name'];
					$wp_roles->roles[$roleId]['name'] = $display_name;
					update_option( $wp_roles->role_key, $wp_roles->roles );
					$wp_roles->role_names[$roleId] = $display_name;
				}

				if($roleId == 'administrator') continue;
				if(isset($roleMeta['isDeleted']) && $roleMeta['isDeleted'] && $roleId != 'administrator') {
					remove_role( $roleId );
					continue;
				}

//				if($roleId != 'editor') continue; // DEMO TEST
				$role = get_role($roleId);

				if(!$role) {//create role
					if(isset($roleMeta['isCreated']) && !$roleMeta['isCreated']) continue;
					$newRoleCaps = array();
					if(isset($roleMeta['capabilities']) && is_array($roleMeta['capabilities'])) {
						foreach($roleMeta['capabilities'] as $capabilityId => $enabled) {
							if(filter_var($enabled, FILTER_VALIDATE_BOOLEAN))
								$newRoleCaps[$capabilityId] = true;
						}
					}
					$result = add_role(
						$roleId,
						$roleMeta['name'],
						count($newRoleCaps) ? $newRoleCaps : null
					);
					continue;
				}


//				if($roleMeta['name'] != $role->name)
//					var_dump($roleMeta['name'], $role->name);

				foreach($roleMeta['capabilities'] as $capabilityId => $enabled) {
					if($capabilityId == 'manage_options' && ($roleId == 'administrator' || $roleId == 'ut_administrator')) {
						continue;
					}

					$enabled = filter_var($enabled, FILTER_VALIDATE_BOOLEAN);
					if(!$enabled && $role->has_cap($capabilityId)) $role->remove_cap($capabilityId);
					if($enabled && !$role->has_cap($capabilityId)) $role->add_cap($capabilityId);
				}
//				var_dump($role);
			}

			if($notExit) return;

			wp_send_json(array(
				'success' => true
			));
			exit;
		}
	}
}
