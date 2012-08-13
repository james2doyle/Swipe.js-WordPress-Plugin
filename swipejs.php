<?php
/*
Plugin Name: SwipeJS
Plugin URI: http://ohdoylerules.com/swipejs/
Description: WordPress plugin for swipe.js: the no-dependency, responsive, 1:1 touch-capable slider built by Brad Birdsall.
Version: 1.0
Author: James Doyle
Author URI: http://ohdoylerules.com
License: GPL & MIT
Resource: https://github.com/bradbirdsall/Swipe or http://swipejs.com/
Resource Description: Built on the swipe.js plugin by Brad Birdsall(http://bradbirdsall.com/)
*/

/*  Copyright 2012  James Doyle  (email : james2doyle@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function sjs_register_scripts() {
	// load script only on non admin pages
	if (!is_admin()) {
		// grab minified js file
		wp_register_script('sjs_min-script', plugins_url('swipe.min.js', __FILE__));
		// add file
		wp_enqueue_script('sjs_min-script');
	}
}

function sjs_register_styles() {
	// grab stylesheet
	wp_register_style('sjs_styles', plugins_url('sjs-style.css', __FILE__));
	wp_enqueue_style('sjs_styles');
}

function sjs_function($type='sjs_function') {
	$args = array(
		'post_type' => 'sjs_images',
		// show all posts
		'posts_per_page' => -1,
		// order ASC so the first post added is shown first
		'order' => 'ASC'
	);
	//------- start markup --------
	$result = '<div id="sjs-slider">';
	// start ul tag
	$result .= '<ul>';
	// initiate loop
	$loop = new WP_Query($args);
	while ($loop->have_posts()) {
		$loop->the_post();
		$the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);
		$result .='<li style="display:block;"><img title="'.get_the_title().'" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt="'.get_the_title().'"/></li>';
	}
	// close ul tag
	$result .= '</ul>';
	// if the pagination is true
	if (get_option('sjs_pagination') == 'on') {
		// the first pagination control is already added. this is because the first a tag needs a current class. this is easier done with markup than detecting with javascript
		$result .= "<div id='sjs-pagination'><a href=\"javascript:;\" onclick=\"sjsslider.slide(0,400)\" class=\"current\">0</a></div>";
	}
	// if controls are set to true
	if (get_option('sjs_controls') == 'on') {
		$result .= "<div id='sjs-controls'><a href='#' class='sjs-prev' onclick='sjsslider.prev();return false;'>&lt;</a><a href='#' class='sjs-next' onclick='sjsslider.next();return false;'>&gt;</a></div>";
	}
	// close the sjs-slider div
	$result .= '</div>';
	return $result;
}

function sjs_admin() {
	// options page
	include('sjs_import_admin.php');
}

function sjs_admin_actions() {
	// options menu
	add_options_page('SwipeJS', 'SwipeJS', 'manage_options', 'swipejs', 'sjs_admin');
}


function sjs_init() {
	// sjs shortcode
	add_shortcode('sjs-slideshow', 'sjs_function');
	// creating custom post type
	$args = array(
		'public' => true,
		'label' => 'SwipeJS',
		'supports' => array(
			// parameters for posts
			'title',
			'thumbnail'
		)
	);
	// name of post type
	register_post_type('sjs_images', $args);
}

function render_Script(){
	// if the delay is not 0 store the value
	if (get_option('sjs_delay') != 0) {
		$auto = ",auto: \"".get_option('sjs_delay')."\"";
	} else {
		// autoslide is off and needs to be null
		$auto = NULL;
	}
	// set global vars. if pagination is on, calculate the number of slides and print in the a tags
	// initiate slider. callback = if pagination is on, remove the current class from all and add it to the currently active node
	$render = "<script type='text/javascript'>
		var list='',innerslider;
		var count = 0;
		var pagi = document.getElementById('sjs-pagination');
		if (pagi != null) {
			var theslider = document.getElementById('sjs-slider');
			innerslider = theslider.childNodes[0];
			var count = innerslider.childNodes.length;
			for (var i=1; i<count; i++) {
				list += '<a href=\"javascript:;\" onclick=\"sjsslider.slide('+i+',400)\">'+i+'</a>';
			}
			pagi.innerHTML += list;
		}
		var sjsslider = new Swipe(document.getElementById('sjs-slider'), {
			//there was some funky action on first run. It acted as if there was an extra slide
			//startSlide: \"".get_option('sjs_start')."\",
			speed: \"".get_option('sjs_speed')."\"".$auto.",
			callback: function(event, index, elem) {
				if (pagi != null) {
					for (var i=0; i<count; i++) {
						var current = pagi.childNodes[i];
						current.setAttribute('class','');
					}
					var current = pagi.childNodes[index];
					current.setAttribute('class','current');
				}
			}
		});</script>";
	echo $render;
}
// add actions
add_action('init', 'sjs_init');
add_action('admin_menu', 'sjs_admin_actions');
add_action('wp_print_scripts', 'sjs_register_scripts');
add_action('wp_print_styles', 'sjs_register_styles');
add_action('wp_footer', 'render_Script');
 ?>