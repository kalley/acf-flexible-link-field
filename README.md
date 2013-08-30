# ACF { Flexible Link Field

Adds a 'Flexible Link' field type for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

-----------------------

### Overview

This field works similarly to the link popup from the WYSIWYG field, giving the user the option between entering in a URL or choosing from a list of pages
from within the Wordpress site. It works similar to the [Page Link](http://www.advancedcustomfields.com/resources/field-types/page-link/) field, giving the developer
the option to limit the post_type that will be available (though someone could just go enter in the URL directly). It also give the option to limit the depth of the
page type. Here's an example (parentheses denote depth):

*Consider this hierarchy*

 - services **(1)**
   - home **(2)**
     - plumbing **(3)**
     - repair **(3)**
   - business **(2)**
     - hvac **(3)**

So if you don't want the user to see plumbing, repair and hvac, give it a depth of 2.


### Compatibility

This add-on will work with:

* version 4 and up

### Installation

This add-on can be treated as both a WP plugin and a theme include.

**Install as Plugin**

1. Copy the 'acf-flexible_link' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

**Include within theme**

1.    Copy the 'acf-flexible_link' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-flexible_link.php file)

```php
include_once('acf-flexible_link/acf-flexible_link.php');
```

### More Information

Please read the readme.txt file for more information
