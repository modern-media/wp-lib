# Modern Media WordPress Library

This package includes various base classes, patterns and utilities that we use across WordPress installations.

## Installing the library with composer:

```
	"repositories": [
		
		{
			"type": "vcs",
			"url": "https://github.com/modern-media/wp-lib"
		}

	],
	"require": {
		"modern-media/wp-lib": "dev-master"
	}
```

## Elements

### Scripts

The Scripts class handles enqueueing Modern Media WP Library JavaScripts. Other elements that rely  on these scripts should enqueue them via:  

    Scripts::inst()->enqueue($script_id)

### Character Counter JavaScript

    wp-lib/assets/js/char-counter.js
    
This script updates an element of class `.mm-wp-lib-char-count` with the character count of an input or textarea. Example markup:

    <input
		type="text"
		class="widefat"
		name="meta_tags_og_description"
		id="meta_tags_og_description"
		placeholder="Default og:description of your site"
		value="foo bar"
	>
	<p>
	    Characters: 
	    <span 
	        class="mm-wp-lib-char-count" 
	        data-target="#meta_tags_og_description"
	    ></span>
	</p>

Data attributes set on the `.mm-wp-lib-char-count` element:  
 * 	`data-target`: sets selector of the input or textarea twhose characters will be counted.

### Image Uploader JavaScript

This script adds the WordPress uploader functionality to elements of class `mm-wp-lib-uploader`.
 
Example Markup:

    <div 
        class="mm-wp-lib-uploader" 
        data-label="Choose Image" 
        data-preview-size="medium">
        <input type="hidden" name="image_id" value="34">
	    <div class="holder"></div>
	    <p><a href="#" class="choose button">Upload/Choose Image</a></p>
	    <p><a href="#" class="remove">Remove Image</a></p>
    </div>

Elements:
 
 * The `input` element should be a hidden input with the image's post ID as the value.
 * The `.holder` element displays a preview of the image.
 * The `.choose` element pops up the WordPress Image Uploader
 * The `.remove` element remove the image (sets the value of the input to '').


Data attributes set on the `.mm-wp-lib-uploader` element:  
 * 	`data-label`: sets the title and button text for the WordPress uploader.  
 * 	`data-size`: sets the size of the preview image	
### Client Timezone 

    /** Singleton pattern **/
    ModernMedia\WPLib\ClientTimezone::inst();

This element sets a cookie on the client browser with javascript. The value of this cookie is the offset in minutes between the client time and UTC. The cookie key is `mm_wp_lib_client_timezone`. 

To make sure the javascript is enqueued on the front end, use the following code before `wp_enqueue_scripts`:

    ClientTimezone::inst()->enqueue_front();

To make sure the javascript is enqueued in admin, use the following code before `admin_enqueue_scripts`:

    ClientTimezone::inst()->enqueue_admin();
    
To get a local copy of a UTC Carbon Date use:

    $local = ClientTimezone::inst()->utc_to_local($utc);
    
In both PHP and Javascript, we follow PHP's convention that locations west of UTC have negative offsets. This is the opposite of the js convention.



### Meta Tags


This element adds common meta tags, including `description`, `author`, and `og:*` tags, to the `<head>` section. Specifically:

#### description and og:description

- Singular pages (post, page, etc):
	- The author can provide a custom description via metabox in the edit screen. This value is used if it exists.
	- If the author has provided a hand-crafted excerpt, then that value is used.
	- If neither of those values exist, we take the first 160 characters of the post content.
	

You must specifically enable this functionality from a boot script in `wp-content/mu-plugins`:

```
<?php
// in wp-content/mu-plugins/boot.php...
use ModernMedia\WPLib\MetaTags\MetaTags;
MetaTags::inst();
```

#### Namespace and Classes

```
namespace ModernMedia\WPLib\MetaTags;
```

- ModernMedia\WPLib\MetaTags\MetaTags
- ModernMedia\WPLib\MetaTags\Data\SiteMetaData
- ModernMedia\WPLib\MetaTags\Admin\SiteMetaTagsPanel
- ModernMedia\WPLib\MetaTags\Admin\MetaTagsMetaBox


## Domain Mapper

This component is a simpler replacement for more complex domain mapping tools.  You need to edit `wp-config.php` and add a `sunrise.php` file to the `wp-content` directory. It allows you to manually map different domains to WordPress blog IDs.

In `wp-config.php`:

```
$modern_media_domain_map = array(
	'example.com' => 1,
	'another-example.com' => 2,
);
```
The array should have the domain names as keys and the blog IDs as values.

In `wp-content/sunrise.php`:

```
<?php
use ModernMedia\WPLib\Network\DomainMapper;
new DomainMapper;
```








