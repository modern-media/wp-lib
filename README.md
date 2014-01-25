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


Data attributes set on the `.mm-wp-lib-uploader` elemant:  
 * 	`data-label`: sets the title and button text for the WordPress uploader.  
 * 	`data-size`: sets the size of the preview image	

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








