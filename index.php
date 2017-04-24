<?php

/*
	Plugin Name: Галерея для сайта
	Description: Для вывода галереи используйте шорткод вида: [gallery ids="1,2,3"], где ids это ID картинок

	Author: stylesam
	Author URI: http://stylesam.com/
	Version: 1.0
	License: MIT
*/


class SLSMGallery
{
	public function __construct()
	{
		remove_shortcode( 'gallery' );
		add_shortcode( 'gallery', [$this, 'slsm_gallery'] );

		add_action( 'wp_enqueue_scripts', [$this, 'slsm_styles_scripts']);
	}

	public function slsm_gallery($atts)
	{
		$img_id = explode( ',', $atts['ids'] );
		if( !$img_id[0] )
		{
			return "<div class='slsm-gallery'>В галерее нет картинок!</div>";
		}

		$gallery = "<div class='slsm-gallery'>";
			foreach( $img_id as $img_item )
			{
				$img_data = get_posts([
					'p' => $img_item,
					'post_type' => 'attachment'
				]);

				$img_title = $img_data[0]->post_title;
				//$img_descr = $img_data[0]->post_content;
				$img_caption = $img_data[0]->post_excerpt;
				$img_thumb = wp_get_attachment_image_src( $img_item );
				$img_thumb_full = wp_get_attachment_image_src( $img_item, 'full' );

				$gallery .= "<a href='{$img_thumb_full[0]}' data-lightbox='gallery' data-title='{$img_caption}'><img src='{$img_thumb[0]}' alt='{$img_title}' width='{$img_thumb[1]}' height='{$img_thumb[2]}'></a>";
			}
		$gallery .= "</div>";

		return $gallery;
	}

	public function slsm_styles_scripts()
	{
		wp_register_script( 'slsm_lightbox-js', plugins_url('/js/lightbox.min.js', __FILE__), ['jquery'] );
		wp_register_style('slsm_lightbox-css', plugins_url('/css/lightbox.css', __FILE__));

		wp_enqueue_script('slsm_lightbox-js');
		wp_enqueue_style('slsm_lightbox-css');
	}
}

$slsm_gallery = new SLSMGallery();