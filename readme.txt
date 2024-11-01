=== TCL Categories Image ===
Contributors: shishirdev, wpmirage
Tags: category image, categories images, category images, categories image, taxonomy image, taxonomy images,categories-image
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 5.2.4
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0

TCL Categories Images Plugin allow users to add an image to category or  custom taxonomies.You can easily assign an image to each category/taxonomy or tag and then display image for category/taxonomy/archive template via following functions.

Usage: 

`<?php if (function_exists('tclTaxonomyImage')) tclTaxonomyImage(); ?>` to display image
`<?php if (function_exists('tclTaxonomyThumbUrl')) echo tclTaxonomyThumbUrl(); ?>` to get URL

Here, You can specify image size  as a second parameter 

`<?php if (function_exists('tclTaxonomyImage')) tclTaxonomyImage(NULL,'thumbnail'); ?>` to display image
`<?php if (function_exists('tclTaxonomyThumbUrl')) echo tclTaxonomyThumbUrl(NULL,'thumbnail'); ?>` to get URL

== Installation ==
Categories Images Plugin allow users to add an image to category or  custom taxonomies.

== Installation ==
You can install it like other plugins.You can add plugin through admin panel
1.Visit the Plugins > Add New and search for TCL Categories Image.
2. Click to install and then activate
3.Go to posts->categories or any other taxonomy and add category image

OR you can also upload plugins file to plugins folder and activate it through plugins tab.

== Screenshots ==
1. Categories image submenu under settings tab
2. Categories image setting detail page
3. Add new category page where you can add image to a category
4. Edit a category page where you can edit/add image to a category