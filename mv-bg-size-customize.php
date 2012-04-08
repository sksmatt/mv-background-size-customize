<?php
/*
Plugin Name: Customize Background-Size
Plugin URI: http://www.mattvarone.com
Description: Adds an option to modify the background-size property trough the theme customize controls.
Author: Matt Varone
Version: 1.0
Author URI: http://www.mattvarone.com
*/


/**
* Initialize
*
* @package Customize Background-Size
* @since    1.0
* @return   void
*/

if ( ! function_exists( 'mv_customize_background_size_init' ) ) {
    function mv_customize_background_size_init() {
        if ( is_admin() ) {
            
            load_plugin_textdomain( 'mv-customize-background-size', false, plugin_dir_path( __FILE__ ) . 'lan' );
            
            add_action( 'customize_register', 'mv_customize_background_size_add_size_control', 10  );
            add_action( 'customize_controls_print_footer_scripts', 'mv_customize_background_size_preview_script', 10 );
        } else {
            add_action( 'wp_head', 'mv_background_size_output_callback', 10  );
        }
    }
}
add_action( 'plugins_loaded', 'mv_customize_background_size_init' );


/** 
* Add Background-Size Control
*
* @package  Customize Background-Size
* @since    1.0
* @return   void
*/

if ( ! function_exists( 'mv_customize_background_size_control' ) ) {
    function mv_customize_background_size_add_size_control() {
        
        global $customize;
        
        if ( ! $customize ) return;
    		    		
		$customize->add_setting( 'mv_background_size', array( 
			'default'           => 'auto', 
			'control'           => 'select', 
			'sanitize_callback' => 'mv_background_size_sanitize_background_size', 
		 ) );
		
		$customize->add_control( 'mv_background_size', array( 
			'label'             => __( 'Background Size', 'mv_background_size' ), 
			'section'           => 'background', 
			'visibility'        => 'background_image', 
			'theme_supports'    => 'custom-background', 
			'type'              => 'radio', 
			'choices'           => array( 
    		            'auto'      => __( 'Auto', 'mv-customize-background-size' ), 
    		            'contain'   => __( 'Contain', 'mv-customize-background-size' ), 
    		            'cover'     => __( 'Cover', 'mv-customize-background-size' ),
    		            ), 
		) );
    }
}


/** 
* Sanitize Background-Size Values
*
* @package  Customize Background-Size
* @since    1.0
* @return   string
*/

if ( ! function_exists( 'mv_background_size_sanitize_background_size' ) ) {
    function mv_background_size_sanitize_background_size( $value ) {
        $safe = array( 'auto', 'contain', 'cover' );
        return in_array( $value, $safe ) ? $value : $safe[0];
    }
}


/** 
* Background-Size Output Callback
*
* @package  Customize Background-Size
* @since    1.0
* @return   void
*/

if ( ! function_exists( 'mv_background_size_output_callback' ) ) {
    function  mv_background_size_output_callback() {
        $style = get_theme_mod( 'mv_background_size', 'auto' );
        printf( '<style type="text/css"> body.custom-background { -webkit-background-size: %1$s; -moz-background-size: %1$s; background-size: %1$s; } </style>'."\n", trim( $style ) );
    }
}


/** 
* Preview Script
*
* @package  Customize Background-Size
* @since    1.0
* @return   void
*/

if ( ! function_exists( 'mv_customize_background_size_preview_script' ) ) {
    function mv_customize_background_size_preview_script() {
        wp_enqueue_script( 'mv-customize-background-size', plugin_dir_url( __FILE__ ) . 'js/mv-customize-background-size-preview.js' );
    }
}


/** 
* Check Compatibility on Activation
*
* @package  Customize Background-Size
* @since    1.0
* @return   void
*/

if ( ! function_exists( 'mv_customize_background_size_activation' ) ) {
	function mv_customize_background_size_activation() {
		// check compatibility
		if ( version_compare( get_bloginfo( 'version' ), '3.3' ) >= 0 )
		deactivate_plugins( basename( __FILE__ ) );
	}
	
	register_activation_hook( __FILE__, 'mv_customize_background_size_activation' );
}