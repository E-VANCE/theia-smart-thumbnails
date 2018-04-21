<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_action( 'init', 'TstFrontend::init' );

class TstFrontend {
	protected static $loadedImages;

	public static function init() {
		if ( TstOptions::get( 'enableInFrontEnd' ) ) {
			self::$loadedImages = array();

			add_filter( 'wp_get_attachment_image_src', 'TstFrontend::wp_get_attachment_image_src', 100000, 4 );
			add_action( 'wp_footer', 'TstFrontend::wp_footer', 100000, 4 );
			add_action( 'wp_enqueue_scripts', 'TstFrontend::wp_enqueue_scripts', 100000 );
		}
	}

	public static function wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ) {
		if ( ! $image || ! $attachment_id ) {
			return $image;
		}

		if ( ! array_key_exists( $attachment_id, self::$loadedImages ) ) {
			$focusPoint = TstPostOptions::get_meta( $attachment_id );

			self::$loadedImages[ $attachment_id ] = array(
				'urls'        => array(),
				'focusPointX' => round( $focusPoint[0], 4 ),
				'focusPointY' => round( $focusPoint[1], 4 )
			);
		}

		self::$loadedImages[ $attachment_id ]['urls'][] = $image[0];

		return $image;
	}

	public static function wp_footer() {
		?>
		<script>
			var tstLoadedImages = <?php echo json_encode( self::$loadedImages ); ?>;
		</script>
		<?php
	}

	public static function wp_enqueue_scripts() {
		wp_register_script( 'theiaSmartThumbnails-frontend.js', plugins_url( 'js/tst-frontend.js', __FILE__ ), array( 'jquery' ), TST_VERSION, true );
		wp_enqueue_script( 'theiaSmartThumbnails-frontend.js' );
	}
}
