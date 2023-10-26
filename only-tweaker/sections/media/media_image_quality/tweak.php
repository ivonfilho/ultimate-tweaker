<?php
class OT_media_image_quality_Tweak {
	function isVisible() {
		return OT_Helper::getRequestRole() == '';
	}

	function settings() {
		return OT_Helper::field('media_image_quality', 'slider', array(
			'title'       => __( 'JPEG Quality', OT_SLUG ),
			'default'       => 90,
			'min'           => 0,
			'step'          => 1,
			'max'           => 100,
			'display_value' => 'text'
		));
	}

	function tweak() {
		add_filter( 'jpeg_quality', array( &$this, 'jpeg_quality_return' ) );
	}

	public function jpeg_quality_return() {
		return absint( $this->value );
	}
}