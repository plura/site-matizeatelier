<?php

// Local JSON — ensure saves go to /acf-json/ (load path is the default)
add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );


