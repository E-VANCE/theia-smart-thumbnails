<?php

/*
 * Copyright 2012-2018, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

class Frontend {
    protected static $loadedImages;

    public static function staticConstruct() {
        add_action( 'init', __NAMESPACE__ . '\\Frontend::init' );
    }

    public static function init() {
        if ( Options::get( 'enableInFrontEnd' ) ) {
            self::$loadedImages = array();

            add_filter( 'wp_get_attachment_image_src', __NAMESPACE__ . '\\Frontend::wp_get_attachment_image_src', 100000, 4 );
            add_action( 'wp_footer', __NAMESPACE__ . '\\Frontend::wp_footer', 100000, 4 );
            add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\Frontend::wp_enqueue_scripts', 100000 );
        }
    }

    public static function wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ) {
        if ( ! $image || ! $attachment_id ) {
            return $image;
        }

        if ( ! array_key_exists( $attachment_id, self::$loadedImages ) ) {
            $focusPoint = PostOptions::get_meta( $attachment_id );

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
        wp_register_script( 'theiaSmartThumbnails-frontend.js', plugins_url( 'js/tst-frontend.js', __FILE__ ), array( 'jquery' ), THEIA_SMART_THUMBNAILS_VERSION, true );
        wp_enqueue_script( 'theiaSmartThumbnails-frontend.js' );
    }
}
