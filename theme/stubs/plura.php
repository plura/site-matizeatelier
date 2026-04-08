<?php
/**
 * Stub declarations for the Plura WordPress plugin.
 * For IDE type-checking only — not executed at runtime.
 *
 * @see https://github.com/plura/wp-plugin-plura/
 */

// ─── Enqueue ─────────────────────────────────────────────────────────────────

/**
 * Enqueue multiple CSS/JS files using absolute paths, URLs, or patterns.
 *
 * @param array  $scripts  Map of file paths/URLs to options arrays, or plain list of paths.
 *                         Options per entry: 'handle', 'deps', 'media'.
 *                         Pattern paths with "%s" resolve to both .css and .js variants.
 * @param bool   $cache    Use filemtime for cache-busting (true) or time() (false).
 * @param string $prefix   Prefix for auto-generated handles.
 * @param bool   $admin    Whether to enqueue in admin (default true). Pass false for frontend only.
 * @return void
 */
function plura_wp_enqueue( array $scripts, bool $cache = true, string $prefix = '', bool $admin = true ): void {}

/**
 * Enqueue a single CSS or JS asset.
 *
 * @param string $type    'css' or 'js'.
 * @param string $file    Absolute path or URL.
 * @param array  $options Optional: 'handle', 'deps', 'media'.
 * @param bool   $cache   Use filemtime for version.
 * @param string $prefix  Handle prefix.
 * @return void
 */
function plura_wp_enqueue_asset( string $type, string $file, array $options = [], bool $cache = true, string $prefix = '' ): void {}

/**
 * Convert an absolute file path to a WordPress URL.
 *
 * @param string $file Absolute file path.
 * @return string
 */
function plura_wp_file_url( string $file ): string { return ''; }

// ─── Component ───────────────────────────────────────────────────────────────

/**
 * Render a Plura component from a manifest.json path.
 *
 * Includes optional index.php, loads index.html, inlines SVGs, resolves
 * relative URLs, enqueues assets, and runs do_shortcode() on the output.
 *
 * @param string      $manifest  Absolute path to manifest.json (or relative to theme dir).
 * @param string      $id        Optional id attribute for the wrapper div.
 * @param bool        $img2svg   Inline local SVG <img> tags as <svg> (default true).
 * @param string|null $context   Optional context string passed to filters.
 * @return string
 */
function plura_wp_component( string $manifest, string $id = '', bool $img2svg = true, ?string $context = null ): string { return ''; }

/**
 * Replace local SVG <img> tags with inline <svg> elements.
 *
 * @param string $html      HTML string to process.
 * @param string $base_path Base directory for resolving relative paths.
 * @param bool   $wrap      Wrap inline SVG in a div.
 * @return string
 */
function plura_img2svg( string $html, string $base_path = '', bool $wrap = false ): string { return ''; }

// ─── Posts ───────────────────────────────────────────────────────────────────

/**
 * Build a WP_Query with extended filtering options.
 *
 * @param int|null    $active              Filter by active meta field.
 * @param string      $active_key          Meta key for active filter.
 * @param array|int   $exclude             Post IDs to exclude.
 * @param array|int   $ids                 Specific post IDs to retrieve.
 * @param int         $limit               Posts per page (-1 for all).
 * @param string      $order               'ASC' or 'DESC'.
 * @param string      $orderby             WP_Query orderby value.
 * @param bool        $rand                Randomise results.
 * @param string|array $type               Post type(s).
 * @param array|int   $terms               Taxonomy term IDs.
 * @param string      $taxonomy            Taxonomy name.
 * @param int|null    $timeline            Timeline filter.
 * @param string      $timeline_end_key    Meta key for timeline end date.
 * @param string      $timeline_start_key  Meta key for timeline start date.
 * @param array       $params              Extra WP_Query args.
 * @param string      $context             Context string for filters.
 * @return \WP_Query
 */
function plura_wp_posts_query(
	?int $active = null,
	string $active_key = '',
	array|int $exclude = [],
	array|int $ids = [],
	int $limit = -1,
	string $order = 'DESC',
	string $orderby = 'date',
	bool $rand = false,
	string|array $type = 'post',
	array|int $terms = [],
	string $taxonomy = '',
	?int $timeline = null,
	string $timeline_end_key = '',
	string $timeline_start_key = '',
	array $params = [],
	string $context = '',
): \WP_Query { return new \WP_Query(); }

/**
 * Render a list of posts, each via plura_wp_post().
 *
 * @param string|array $type                Post type(s).
 * @param int|null     $active              Active meta filter.
 * @param string       $active_key          Meta key for active filter.
 * @param array|int    $exclude             Post IDs to exclude.
 * @param array|int    $ids                 Specific post IDs.
 * @param int          $limit               Posts per page.
 * @param string       $order               'ASC' or 'DESC'.
 * @param string       $orderby             WP_Query orderby value.
 * @param int          $rand                Randomise (1 = yes, 0 = no).
 * @param array|int    $terms               Term IDs.
 * @param string       $taxonomy            Taxonomy name.
 * @param int|null     $timeline            Timeline filter.
 * @param string       $timeline_datetime_format Datetime format for timeline output.
 * @param string       $timeline_datetime_source Source format for parsing raw timeline dates.
 * @param string       $timeline_end_key    Meta key for timeline end date.
 * @param string       $timeline_start_key  Meta key for timeline start date.
 * @param string       $class               CSS class for the wrapper.
 * @param array        $data                Data attributes for the wrapper.
 * @param string       $datetime_format     Date format string.
 * @param int          $link                0 = link inner elements, 1 = wrap block in link, -1 = no links.
 * @param bool|string  $read_more           false = hidden, true = default label, string = custom label.
 * @param string|null  $label               Read more label override.
 * @param bool         $wrap                Wrap output in a container div.
 * @param array|null   $posts               Pre-fetched posts array (skips query).
 * @param array        $params              Extra WP_Query args.
 * @param string|null  $context             Context string passed to plura_wp_post filters.
 * @param string       $output              'html' or 'array'.
 * @return string|array
 */
function plura_wp_posts(
	string|array $type = 'post',
	?int $active = null,
	string $active_key = '',
	array|int $exclude = [],
	array|int $ids = [],
	int $limit = -1,
	string $order = 'DESC',
	string $orderby = 'date',
	int $rand = 0,
	array|int $terms = [],
	string $taxonomy = '',
	?int $timeline = null,
	string $timeline_datetime_format = 'l, F jS, Y g:i A',
	string $timeline_datetime_source = 'Y-m-d H:i:s',
	string $timeline_end_key = '',
	string $timeline_start_key = '',
	string $class = '',
	array $data = [],
	string $datetime_format = 'l, F jS, Y g:i A',
	int $link = 0,
	bool|string $read_more = true,
	?string $label = null,
	bool $wrap = true,
	?array $posts = null,
	array $params = [],
	?string $context = null,
	string $output = 'html',
): string|array { return ''; }

/**
 * Render a single post card/block.
 *
 * @param \WP_Post|int $post                     Post object or ID.
 * @param string       $class                    CSS classes.
 * @param string       $datetime_format          Date format (default: post_date).
 * @param int          $link                     0 = link inner elements, 1 = wrap block in link, -1 = no links.
 * @param array|string $meta                     Meta key(s) to display.
 * @param bool|string  $read_more                false = hidden, true = default label, string = custom label.
 * @param bool         $wrap                     Wrap output in a container.
 * @param string       $timeline_datetime_format Datetime format for timeline output.
 * @param string       $timeline_datetime_source Source format for parsing raw timeline dates.
 * @param string       $timeline_end_key         Meta key for timeline end date.
 * @param string       $timeline_start_key       Meta key for timeline start date.
 * @param string|null  $context                  Context tag for filters (e.g. 'archive', 'homepage').
 * @param int|null     $index                    Post index within a list.
 * @return string
 */
function plura_wp_post(
	\WP_Post|int $post,
	string $class = '',
	string $datetime_format = 'l, F jS, Y g:i A',
	int $link = 0,
	array|string $meta = [],
	bool|string $read_more = true,
	bool $wrap = true,
	string $timeline_datetime_format = 'l, F jS, Y g:i A',
	string $timeline_datetime_source = 'Y-m-d H:i:s',
	string $timeline_end_key = '',
	string $timeline_start_key = '',
	?string $context = null,
	?int $index = null,
): string { return ''; }

/**
 * Filter: plura_wp_post
 *
 * Allows modifying the rendered content array for a single post.
 * Return a new array to replace the default entry; returning the same
 * array leaves output unchanged.
 *
 * Hook signature:
 *   add_filter( 'plura_wp_post', function(
 *       array   $entry,    // associative array of rendered HTML parts (e.g. 'featured-image', 'title')
 *       WP_Post $post,     // the post object
 *       ?string $context,  // context string passed to plura_wp_posts() / plura_wp_post()
 *       ?int    $index,    // 0-based loop index within plura_wp_posts(), null when called directly
 *       array   $original, // unfiltered entry array (same keys as $entry before any filter ran)
 *   ): array { ... }, 10, 5 );
 */

/**
 * Render a post's featured image.
 *
 * @param \WP_Post|int $post    Post object or ID.
 * @param string       $size    Image size (default: 'large').
 * @param array        $atts    img tag attributes.
 * @param string|null  $context Context for filters.
 * @return string|null
 */
function plura_wp_post_featured_image( \WP_Post|int $post, string $size = 'large', array $atts = [], ?string $context = null ): ?string { return ''; }

/**
 * Render a post title, optionally linked.
 *
 * @param \WP_Post|int $post    Post object or ID.
 * @param string|false $tag     HTML tag (default 'h3'). Pass false for plain text.
 * @param bool         $link    Whether to wrap the title in a permalink.
 * @param string|null  $context Context for filters.
 * @return string|null
 */
function plura_wp_post_title( \WP_Post|int $post, string|false $tag = 'h3', bool $link = false, ?string $context = null ): ?string { return ''; }

// ─── Images ──────────────────────────────────────────────────────────────────

/**
 * Return image data array for a given attachment.
 *
 * @param int|\WP_Post $attachment Attachment ID or post object.
 * @param string       $size       Image size (default: 'large').
 * @return array|null  Keys: src, width, height, alt, srcset, sizes — or null if invalid.
 */
function plura_wp_image_data( int|\WP_Post $attachment, string $size = 'large' ): ?array { return null; }

/**
 * Generate a responsive <img> tag for an attachment.
 *
 * @param int|\WP_Post    $attachment  Attachment ID or post object.
 * @param string          $size        Image size (default: 'large').
 * @param array           $atts        Additional img attributes.
 * @param string|false    $loading     Loading strategy: 'lazy', 'eager', or false to omit.
 * @return string|null    HTML <img> tag or null if invalid.
 */
function plura_wp_image( int|\WP_Post $attachment, string $size = 'large', array $atts = [], string|false $loading = 'lazy' ): ?string { return ''; }

/**
 * Render a gallery of image attachments.
 *
 * @param array|null          $ids                   Explicit attachment IDs; null or [] to skip.
 * @param int|\WP_Post|null   $source                Post ID/object to read from; null to skip.
 * @param string|null         $source_key            Meta/ACF field key on $source; null to skip.
 * @param bool                $source_featured_image Prepend the featured image of $source. Default false.
 * @param bool                $unique                Remove duplicate image IDs. Default true.
 * @param string|null         $class                 Additional CSS class(es) for the wrapper.
 * @param string|null         $context               Context string passed to filters.
 * @return string
 */
function plura_wp_gallery(
	?array $ids = null,
	int|\WP_Post|null $source = null,
	?string $source_key = null,
	bool $source_featured_image = false,
	bool $unique = true,
	?string $class = null,
	?string $context = null,
): string { return ''; }

/**
 * Return featured image src data for a post (URL, width, height, is_intermediate).
 *
 * @param int|\WP_Post $post Post object or ID.
 * @param string       $size Image size (default: 'large').
 * @return array|false
 */
function plura_wp_thumbnail( int|\WP_Post $post, string $size = 'large' ): array|false { return false; }

/**
 * Render a datetime element.
 *
 * @param \DateTime|string|null $date     The date (DateTime, date string, or null).
 * @param string|array|null     $class    CSS class(es) for the wrapper element.
 * @param string                $format   Date format string.
 * @param int|null              $id       Post ID (overrides $date if provided).
 * @param string                $source   Source date format for parsing string dates.
 * @param string                $tag      HTML tag to wrap the date (default: 'time').
 * @param bool                  $relative Show relative time instead of formatted date.
 * @return string|null
 */
function plura_wp_datetime(
	\DateTime|string|null $date = null,
	string|array|null $class = null,
	string $format = 'l, F jS, Y g:i A',
	?int $id = null,
	string $source = 'Y-m-d H:i:s',
	string $tag = 'time',
	bool $relative = false,
): ?string { return ''; }

/**
 * Render the title of a post or term.
 *
 * @param \WP_Term|\WP_Post|int $object   Post/term object or post ID.
 * @param string|false          $tag      HTML tag (default 'h3'). Pass false for plain text.
 * @param bool                  $link     Whether to wrap the title in a link.
 * @param array|string|null     $class    Additional CSS classes.
 * @param string|null           $context  Context for filters.
 * @return string|null
 */
function plura_wp_title(
	\WP_Term|\WP_Post|int $object,
	string|false $tag = 'h3',
	bool $link = false,
	array|string|null $class = null,
	?string $context = null,
): ?string { return ''; }

/**
 * Wrap HTML in an anchor tag with external link detection.
 *
 * @param string                          $html   Inner HTML (required).
 * @param \WP_Post|\WP_Term|string|null   $target Post, term, URL string, or null.
 * @param array                           $atts   Attributes for the <a> tag.
 * @param bool                            $rel    Add rel="noopener noreferrer" when target="_blank".
 * @param string|null                     $title  Title attribute override.
 * @return string
 */
function plura_wp_link( string $html, \WP_Post|\WP_Term|string|null $target = null, array $atts = [], bool $rel = false, ?string $title = null ): string { return ''; }

// ─── Navigation ──────────────────────────────────────────────────────────────

/**
 * Generate breadcrumb navigation HTML.
 *
 * @param \WP_Post|\WP_Term|int|null $object  Post/term object or ID (null = current queried object).
 * @param bool                       $self    Include the object itself as final breadcrumb.
 * @param string|null                $class   Additional class for the container.
 * @param bool                       $html    Return rendered HTML (true) or raw array (false).
 * @param string|null                $context Context for filters.
 * @return string|array
 */
function plura_wp_breadcrumbs(
	\WP_Post|\WP_Term|int|null $object = null,
	bool $self = false,
	?string $class = null,
	bool $html = true,
	?string $context = null,
): string|array { return ''; }

// ─── WPML ────────────────────────────────────────────────────────────────────

/**
 * Check if WPML is active.
 *
 * @return bool
 */
function plura_wpml(): bool { return false; }

/**
 * Translate a post or term ID to its equivalent in the target language.
 *
 * @param int|int[]   $id      Post/term ID or array of IDs.
 * @param bool|string $default true = default lang, false = current lang, string = language code.
 * @param string      $type    'post' or 'term'.
 * @return int|int[]
 */
function plura_wpml_id( int|array $id = 0, bool|string $default = true, string $type = 'post' ): int|array { return $id; }

/**
 * Return the current WPML language code, or false if WPML is not active.
 *
 * @return string|false e.g. 'pt', 'en', or false.
 */
function plura_wpml_lang(): string|false { return false; }

/**
 * Get the featured image for a post with ACF fallback, WPML-aware.
 * Returns wp_get_attachment_image_src() array or false.
 *
 * @param int|\WP_Post $postID      Post ID or object.
 * @param bool         $acf_field   ACF field key to use as fallback (or false to skip).
 * @param string       $size        Image size (default: 'large').
 * @return array|false
 */
function plura_wpml_featured_image( int|\WP_Post $postID, bool|string $acf_field = false, string $size = 'large' ): array|false { return false; }

// ─── Utils ───────────────────────────────────────────────────────────────────

/**
 * Convert an array to an HTML attribute string.
 *
 * @param array $atts    Key/value pairs. Boolean true outputs the key only. 'class' can be an array.
 * @param bool  $prefix  Prefix all attributes with 'data-'.
 * @return string
 */
function plura_attributes( array $atts, bool $prefix = false ): string { return ''; }

/**
 * Evaluate the boolean state of a value.
 *
 * @param mixed      $value The value to test.
 * @param bool|null  $bool  null = check if truthy or falsy, true = check if truthy only, false = check if falsy only.
 * @return bool
 */
function plura_bool( mixed $value, ?bool $bool = null ): bool { return false; }

/**
 * Explode a string with auto-trimming of resulting values.
 *
 * @param string $separator
 * @param string $string
 * @param int    $limit
 * @return array
 */
function plura_explode( string $separator, string $string, int $limit = PHP_INT_MAX ): array { return []; }
