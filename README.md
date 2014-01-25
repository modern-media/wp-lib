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

This is a javascript

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








