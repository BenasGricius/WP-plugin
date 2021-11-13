<?php
/**
* Plugin Name:Fetcher
* * Description: Fetches random posts from an external API and loops them on the prefered pos/page. Just add [random_posts count='prefered posts number'  order='asc or desc'] intro field of editor of page/post.
*/

function bartag_func( $atts ) {
	$a = shortcode_atts( array(
		'count' =>'',
		'order' => '',
	), $atts );		
	
	$request = wp_remote_get( 'https://jsonplaceholder.typicode.com/posts' ); /// makes a call to an API and returns and array about the response

	if( is_wp_error( $request ) ) {
		return false; // check if request is an error
	}
	
	$body = wp_remote_retrieve_body( $request );
	
	$posts = json_decode( $body );			
	
	if( ! empty( $posts ) ) {
		$user_selected_posts = array_slice($posts,0,$a['count']); //create new array out of selected numbers			

		echo '<div style="max-width:1300px; padding:0 20px; margin:0 auto;margin-bottom: 40px;">';
		echo '<ul style="display:flex; flex-wrap:wrap; padding:0; justify-content:space-between;">';				
		if($a['order']=='asc'){
			foreach( $user_selected_posts as $post ) {

				echo '<li style="width:45%; min-width:48%; list-style:none; margin-bottom: 40px;">';
					echo '<div style="font-size:24px; font-weight:bold; text-transform:uppercase; margin-bottom:20px;">';
					echo $post->title;
					echo '</div>';
					echo '<div class="post-text">';
					echo $post->body;
					echo '</div>';
				echo '</li>';
			}
		}	
		elseif($a['order']=='desc'){
			$posts_count = $a['count']-1;					
			while($posts_count > -1) {
				echo '<li style="width:45%; min-width:48%; list-style:none; margin-bottom: 40px;";>';
					echo '<div style="font-size:24px; font-weight:bold; text-transform:uppercase; margin-bottom:20px;";>';							
					echo $user_selected_posts[$posts_count]->title;
					echo '</div>';
					echo '<div class="post-text">';
					echo $user_selected_posts[$posts_count]->body;
					echo '</div>';
				echo '</li>';		
				
				$posts_count--;
			}
		}else{
			echo '<li>';
				
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
		
	}

}

add_shortcode( 'random_posts', 'bartag_func' );