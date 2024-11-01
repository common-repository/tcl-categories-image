<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class TCLCI_Meta_Box {


    protected $taxonomies;

    public function __construct() 
    {
        
        if(is_admin())
        {
           	add_action('admin_init', array($this, 'admin_init'));
           	add_action( 'edited_term',  array( $this, 'save_taxonomy_attachment_ID' ), 10, 2 );
            add_action( 'create_term',  array( $this, 'save_taxonomy_attachment_ID' ), 10, 2 );
        }
    }
 
    // Initialization
    public function admin_init()
    {
        $tclciOptions = get_option('tclci_options');
        $excludeTaxonomies = empty($tclciOptions['tclci_exclude_taxonomies']) ? [" "] : $tclciOptions['tclci_exclude_taxonomies'];
        $this->taxonomies = get_taxonomies();
        $filteredTax = array_diff_assoc( $this->taxonomies, $excludeTaxonomies);

        foreach ((array) $filteredTax as $taxonomy)
        {
        	// Add custom form fields to  taxonomies
        	add_action( "{$taxonomy}_edit_form_fields",  array( $this, 'edit_taxonomy_custom_field' ), 10, 2 );
            add_action( "{$taxonomy}_add_form_fields",  array( $this, 'add_taxonomy_custom_field' ), 10, 2 );

            // Add custom columns to custom taxonomies
            add_filter("manage_edit-{$taxonomy}_columns", array($this, 'manage_taxonomy_custom_columns'));
            add_filter("manage_{$taxonomy}_custom_column", array($this, 'manage_taxonomy_custom_column_fields'), 10, 3);
        }
    }
  
    // Add term page
	public function add_taxonomy_custom_field() 
	{
		
		echo '<div class="form-field" id="meta-box-id">
				<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'tcl-categories-image') . '</label></th>
				<td>
				    <span class="tclci-taxonomy-image-container"><img class="tcl-taxonomy-image" src="'.TCLCI_IMAGE_PLACEHOLDER.'"/></span><br/>
				    <input type="hidden" name="taxonomy_image_id"  class="tclci-taxonomy-val" value="" />
					<button class="tclci-upload-image-button button">' . __('Upload/Add image', 'tcl-categories-image') . '</button>
					<button class="tclci-remove-image-button button hidden">' . __('Remove image', 'tcl-categories-image') . '</button><br/><br/>
				</td>
	        </div>';
	}


    //Edit term page
    public function edit_taxonomy_custom_field($term) 
    {

        $taxonomy_image_url = $this->get_category_thumbnail_url($term->term_id,'medium',true);
	    $addClass = !empty($this->has_category_thumbnail($term->term_id)) ? " " : "hidden";
		echo '<tr class="form-field" id="meta-box-id">
				<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'tcl-categories-image') . '</label></th>
				<td>
				    <span class="tclci-taxonomy-image-container"><img class="tclci-taxonomy-image" src="'.esc_url($taxonomy_image_url).'"/></span>
				    <input type="hidden" name="taxonomy_image_id" id="taxonomy_image" class="tclci-taxonomy-val" value="'.esc_attr($this->get_attachment_id($term->term_id)).'" /><br />
					<button class="tclci-upload-image-button button">' . __('Upload/Edit image', 'tcl-categories-image') . '</button>
					<button  class="tclci-remove-image-button button '.esc_attr($addClass).'">' . __('Remove image', 'tcl-categories-image') . '</button>
				</td>
	        </tr>';
	}

    //Add Columns
    public function manage_taxonomy_custom_columns($columns)
    {

       $columns['_term_thumb'] = __('Thumbnail');
       return $columns;
    }
    
    //Add Content to a Column
    public function manage_taxonomy_custom_column_fields($content, $column_name, $term_id)
    {

      if ( '_term_thumb' == $column_name ) 
      {
        $content = '<span><img style="max-width:100%;" src="' . $this->get_category_thumbnail_url($term_id,'thumbnail',true) . '" alt="' . __('Thumbnail', 'tcl-categories-images') . '" class="wp-post-image" /></span>';
      }

	  return $content;
    }


    //Save term meta box values
    public function save_taxonomy_attachment_ID( $term_id ) 
    {
     
        $attachment_id = sanitize_text_field($_POST['taxonomy_image_id']);
        $term_meta_id = isset($attachment_id) ? (int) $attachment_id : null;
        if (! is_null($term_meta_id) && $term_meta_id > 0 && !empty($term_meta_id)) 
        {
            update_option( "taxonomy_$term_id", $term_meta_id );
            return;
        }
 
		delete_option('taxonomy_'.$term_id);
	} 

    public function get_category_thumbnail_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) 
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
		
		$attachment_id = $this->get_attachment_id($term_id);

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


    public function get_attachment_id($term_id)
    {
        return get_option('taxonomy_'.$term_id);
    }


    public function has_category_thumbnail($term_id)
    {

        return ($this->get_attachment_id($term_id) !== false);
    }
}
new TCLCI_Meta_Box();
?>