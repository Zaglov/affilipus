<?php


namespace imbaa\Affilipus\Admin\Utilities;



class imbafStats {



	public function productStats(){



		global $wpdb;


		$stats = array(

			'publish_count' => $wpdb -> get_results("SELECT COUNT(*) AS publish_count FROM {$wpdb->posts} WHERE post_type = 'imbafproducts' AND post_status = 'publish';",ARRAY_A )[0]['publish_count'],
			'draft_count' => $wpdb -> get_results("SELECT COUNT(*) AS draft_count FROM {$wpdb->posts} WHERE post_type = 'imbafproducts' AND post_status = 'draft';",ARRAY_A )[0]['draft_count'],
			'temporary_count' => $wpdb -> get_results("SELECT COUNT(*) AS temporary_count FROM {$wpdb->posts} WHERE post_type = 'imbafproducts' AND post_status = 'hidden';",ARRAY_A )[0]['temporary_count']
		);



		$stats['total_imported'] = $stats['publish_count']+$stats['draft_count'];


		return $stats;

	}


	public function mediaStats(){



		global $wpdb;


		$stats = array(

			'picture_count_amazon' => $wpdb -> get_results("SELECT COUNT(*) AS picture_count_amazon FROM {$wpdb->postmeta} WHERE meta_key = 'imbaf_source' AND meta_value = 'amazon';",ARRAY_A )[0]['picture_count_amazon']

		);





		return $stats;

	}


}