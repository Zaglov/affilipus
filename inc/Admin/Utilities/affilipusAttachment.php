<?php

namespace imbaa\Affilipus\Admin\Utilities;
use imbaa\Affilipus\Core as CORE;

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }


class affilipusAttachment{

	public function __construct(){

		add_filter("attachment_fields_to_edit", array($this,"image_attachment_fields_to_edit"), null, 2);
		add_filter("attachment_fields_to_save", array($this,"image_attachment_fields_to_save"), null, 2);

	}

	function image_attachment_fields_to_edit($form_fields, $post) {

		$form_fields["imbaf_source"] = array(
			"label" => __("Bildquelle"),
			"input" => "html",
			"html" => '<spann class="value">'.get_post_meta($post->ID, "imbaf_source", true).'</spann>'
		);


		return $form_fields;
	}

	function image_attachment_fields_to_save($post, $attachment) {

		if( isset($attachment['imbaf_source']) ){
			if( trim($attachment['imbaf_source']) == '' ){
				// adding our custom error
				$post['errors']['imbaf_source']['errors'][] = __('Error text here.');
			}

			else {

				update_post_meta($post['ID'], 'imbaf_source', $attachment['imbaf_source']);
			}
		}
		return $post;
	}

}