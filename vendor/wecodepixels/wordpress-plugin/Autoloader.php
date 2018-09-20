<?php

/*
 * Copyright 2012-2018 WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnailsFramework;

class Autoloader {
    // Root folder of the plugin.
    private $root;
    
    // Namespace of the plugin classes.
    private $namespace;

    public function __construct( $root, $namespace ) {
        $this->root   = $root;
        $this->namespace = $namespace;

        spl_autoload_register( array( $this, 'autoload' ) );
    }

    public function autoload( $class ) {
        // Ignore classes that don't match our namespace.
        if ( strpos( $class, $this->namespace . '\\' ) !== 0 ) {
            return;
        }

        $path = $this->root . '/src/' . str_replace( "\\", "/", $class ) . '.php';
        include $path;
    }
}
