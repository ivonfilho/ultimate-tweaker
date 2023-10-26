<?php

class OT_admin_posts_show_featured_image_column_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_posts_show_featured_image_column', array(
			'title' => __( 'Show featured image column on lists', OT_SLUG )
		) );
	}

	function tweak() {
		add_action('admin_init', array($this, '_show'), 1000);
	}

	function _show(  ) {
		$post_types = array_merge(array('posts','pages'), get_post_types(array('_builtin' => false)));

		foreach ($post_types  as $post_type)
		{
			add_action('manage_' . $post_type . '_custom_column', array($this, '_echo'), 1000, 2);
			add_filter('manage_' . $post_type . '_columns', array($this, '_header'), 1000, 2);
		}
	}
	
	function _echo($column, $value) {
		switch ($column)
		{
			case 'ut_list_featured_image' :
				global $post;
				$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
				$title = get_the_title(get_post_thumbnail_id());

				if($image_attributes) { ?>
					<a href = "<?php echo $image_attributes[0];?>" alt = "<?php echo $title;?>" title = "<?php echo $title;?>" >
					<img src = "<?php echo $image_attributes[0]; ?>" style = "max-width: 150px;" />
				</a >
				<?php } else { ?>
					<a href="<?php echo admin_url();?>/post.php?post=<?php echo $post->ID;?>&action=edit#postimagediv"
					   alt="<?php echo _e('Not Set', OT_SLUG);?>"
					   title="<?php echo _e('Set Featured Image', OT_SLUG);?>"><?php _e('Not Set', OT_SLUG); ?></a>
				<?php }

				break;
		}
	}

	public function _header($columns)
	{
		global $post;

		if(post_type_supports(get_post_type($post), 'thumbnail'))
		{
			$columns['ut_list_featured_image'] =  __('Featured Image', OT_SLUG);
		}

		return $columns;
	}
}