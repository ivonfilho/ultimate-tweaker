<?php

class OT_admin_posts_show_ids_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_posts_show_ids', array(
			'title' => __( 'Show ID\'s column on lists', OT_SLUG )
		) );
	}

	function tweak() {
		add_action('admin_init', array($this, '_tableUsers'), 1000);
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}

	function _tableUsers(  ) {
		if (is_multisite())
		{
			add_action('manage_sites_custom_column', array($this, '_echo'), 1000, 2);
			add_action('manage_blogs_custom_column', array($this, '_echo'), 1000, 2);
			add_filter('wpmu_blogs_columns', array($this, '_header'), 1000, 2);
		}
		
		$data = array(
			array(1, 'users'),
			array(0, 'link', 'link-manager', 'link-manager'),
			array(0, 'posts', 'posts', 'post'),
			array(0, 'pages', 'pages', 'page'),
			array(0, 'media'),
		);

		if ($custom_post_types = get_post_types(array('_builtin' => false))) {
			foreach ($custom_post_types  as $custom_post_type) {
				$data[] = array(0, $custom_post_type);
			}
		}

		if ($taxonomies = get_taxonomies()) {
			foreach ($taxonomies  as $taxonomy) {
				$data[] = array(1, $taxonomy);
			}
		}

		foreach($data as $d) {
			add_action("manage_" . $d[1] . "_custom_column", array($this, ($d[0] == 1 ? '_return' : '_echo')), 1000, 3);
			add_filter("manage_" . (isset($d[2]) ? $d[2] : $d[1]) . "_columns", array($this, '_header'), 1000, 3);
			add_filter("manage_edit-" . (isset($d[2]) ? $d[2] : $d[1]) . "_columns", array($this, '_header'), 1000, 3);
			add_filter("manage_edit-" . (isset($d[3]) ? $d[3] : $d[1]) . "_sortable_columns", array($this, '_registerColumn'), 1000);
			add_filter("manage_" . (isset($d[3]) ? $d[3] : $d[1]) . "_sortable_columns", array($this, '_registerColumn'), 1000);
		}
	}

	public static function _return($value, $column, $id)
	{
		switch ($column) {
			case 'utlist_id':
				$value = (int) $id;
				break;

			default:
				$value = '';
				break;
		}

		return $value;
	}

	public function _header($columns)
	{
		$column_id = array('utlist_id' =>  __('ID', OT_SLUG));

		$columns = array_slice( $columns, 0, 1, true ) + $column_id + array_slice( $columns, 1, null, true );

		return $columns;
	}

	public function _echo($column, $value)
	{
		switch ($column)
		{
			case 'utlist_id' :
				echo $value;
		}
	}

	public function _registerColumn($columns)
	{
		$columns['utlist_id'] =  __('ID', OT_SLUG);
		return $columns;
	}


	function admin_enqueue_scripts() {
		$css = '.column-utlist_id { width:4em;text-align:right }';
		wp_add_inline_style( 'admin', $css );
	}
}