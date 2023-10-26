<?php

class OT_post_author_notification_Tweak {
	function settings() {
        $f = array();

        $f[] =  OT_Helper::switcher( 'post_author_notification', array(
			'title' => __( 'Automatically email contributor when their post is published', OT_SLUG ),
		) );

        $f[] = OT_Helper::field( '_post_author_notification_custom_email', 'text', array(
            'right_title' => __( 'Custom global email:', OT_SLUG ),
            'required'    => array( 'post_author_notification', '=', '1' ),
        ) );

        return $f;
	}

	function tweak() {
		add_action( 'publish_post', array( &$this, '_do' ) );
	}

	function _do( $post_id ) {
		$post   = get_post( $post_id );
		$author = get_userdata( $post->post_author );

		$email = $author->user_email;

		if(isset($this->options->_post_author_notification_custom_email) && $this->options->_post_author_notification_custom_email) {
		    $email = $this->options->_post_author_notification_custom_email;
        }


		$message = "
Hi " . $author->display_name . ",
Your post, " . $post->post_title . " has just been published. Thank you!
";
		wp_mail( $email, "Your article is published and online", $message );
	}
}