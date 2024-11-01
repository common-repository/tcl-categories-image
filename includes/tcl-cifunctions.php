<?php 

/**
  * Public function to get category image URL
  */
function tclTaxonomyThumbUrl($term_id = NULL, $size = 'full', $return_placeholder = FALSE) 
{
    	$taxonomy_image_url = "";
		if (!$term_id) 
		{
			if (is_category())
				$term_id = get_query_var('cat');
			elseif (is_tag())
				$term_id = get_query_var('tag_id');
			elseif (is_tax()) 
			{
				$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$term_id = $current_term->term_id;
			}
		}
		
		$attachment_id = getAttachmentId($term_id);

	    if(!empty($attachment_id)) 
	    {
		    $taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
			$taxonomy_image_url = $taxonomy_image_url[0];
		}
		

	    if ($return_placeholder)
			return (isset($taxonomy_image_url) &&  $taxonomy_image_url!= '') ? $taxonomy_image_url : TCLCI_IMAGE_PLACEHOLDER;
		else
			return $taxonomy_image_url;
}

/**
  * Public function to get category image 
  */
function tclTaxonomyImage($term_id = NULL, $size = 'full', $return_placeholder = FALSE)
{?>
    	<img src="<?php echo tclTaxonomyThumbUrl($term_id = NULL, $size = 'full', $return_placeholder = FALSE);?>" />
<?php 
}


function getAttachmentId($term_id)
{
    return get_option('taxonomy_'.$term_id);
}


function hasCategoryThumbnail($term_id)
{

    return (getAttachmentId($term_id) !== false);
}