<?php

use TaylorNetwork\Linkify\Linkify;

return [

	/*
	 |------------------------------------------------- 
	 | Convert URLs To
	 |-------------------------------------------------
	 |
	 | Options:
	 | 		- Linkify::ConvertMarkdown
	 |		- Linkify::ConvertHTML
	 | 		- Linkify::ConvertCustom (you need to define this)
	 |
	 */
	 'convertTo' => Linkify::ConvertMarkdown,

	 /*
	 |------------------------------------------------- 
	 | Additonal Link Attributes
	 |-------------------------------------------------
	 |
	 | Anything in this array will be added to the
	 | 	<a> tag when converting to HTML. For example
	 |	to add target="_blank" to all <a> tags generated,
	 |	add 'target' => '_blank'.
	 |
	 */
	 'linkAttributes' => [],

	 /*
	 |------------------------------------------------- 
	 | Check for Existing Formatting
	 |-------------------------------------------------
	 |
	 | Do you want to check that the link is not already
	 |	linkified using a different method, or 
	 | 	saved that way?
	 |
	 */
	 'checkForExistingFormatting' => true,

	 /*
	 |------------------------------------------------- 
	 | Custom Parse Callback
	 |-------------------------------------------------
	 |
	 | Where the custom parse callback is stored. 
	 | You could place an actual callback here, but 
	 |	ideally override the linkifyCustomParse function
	 |	on the MakesLinks trait.
	 |
	 */
	 'customCallback' => null,


	/*
	 |------------------------------------------------- 
	 | URL RegEx Pattern
	 |-------------------------------------------------
	 |
	 | Don't change this unless necessary.
	 |
	 */
	'pattern' => '~(?xi)
		              (?:
		                ((ht|f)tps?://)                    # scheme://
		                |                                  #   or
		                www\d{0,3}\.                       # "www.", "www1.", "www2." ... "www999."
		                |                                  #   or
		                www\-                              # "www-"
		                |                                  #   or
		                [a-z0-9.\-]+\.[a-z]{2,4}(?=/)      # looks like domain name followed by a slash
		              )
		              (?:                                  # Zero or more:
		                [^\s()<>]+                         # Run of non-space, non-()<>
		                |                                  #   or
		                \(([^\s()<>]+|(\([^\s()<>]+\)))*\) # balanced parens, up to 2 levels
		              )*
		              (?:                                  # End with:
		                \(([^\s()<>]+|(\([^\s()<>]+\)))*\) # balanced parens, up to 2 levels
		                |                                  #   or
		                [^\s`!\-()\[\]{};:\'".,<>?«»“”‘’]  # not a space or one of these punct chars
		              )
		         ~',

];