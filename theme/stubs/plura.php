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
 * @param string $path Absolute file path.
 * @return string
 */
function plura_wp_file_url( string $path ): string { return ''; }

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
 * @param bool   $wrap      Wrap inline SVG in a span.
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
 * @param string      $timeline_start_key  Meta key for timeline start.
 * @param string      $timeline_end_key    Meta key for timeline end.
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
	string $timeline_start_key = '',
	string $timeline_end_key = '',
	array $params = [],
	string $context = '',
): \WP_Query { return new \WP_Query(); }

/**
 * Render a list of posts, each via plura_wp_post().
 *
 * @param int|null    $active              Active meta filter.
 * @param string      $active_key          Meta key for active filter.
 * @param string      $class               CSS class for the wrapper.
 * @param array       $data                Data attributes for the wrapper.
 * @param string      $datetime_format     Date format string.
 * @param array|int   $exclude             Post IDs to exclude.
 * @param array|int   $ids                 Specific post IDs.
 * @param int         $limit               Posts per page.
 * @param int         $link                0 = no link, 1 = title link, -1 = full card link.
 * @param string      $order               'ASC' or 'DESC'.
 * @param string      $orderby             WP_Query orderby value.
 * @param bool        $rand                Randomise.
 * @param bool|string $read_more           Show read more. Pass string for custom label.
 * @param string|null $label               Read more label override.
 * @param string|array $type               Post type(s).
 * @param array|int   $terms               Term IDs.
 * @param string      $taxonomy            Taxonomy name.
 * @param int|null    $timeline            Timeline filter.
 * @param string      $timeline_start_key  Meta key for timeline start.
 * @param string      $timeline_end_key    Meta key for timeline end.
 * @param bool        $wrap                Wrap output in a container div.
 * @param array|null  $posts               Pre-fetched posts array (skips query).
 * @param string      $output              'html' or 'array'.
 * @param array       $params              Extra WP_Query args.
 * @param string      $context             Context string passed to plura_wp_post filters.
 * @return string|array
 */
function plura_wp_posts(
	?int $active = null,
	string $active_key = '',
	string $class = '',
	array $data = [],
	string $datetime_format = '',
	array|int $exclude = [],
	array|int $ids = [],
	int $limit = -1,
	int $link = 1,
	string $order = 'DESC',
	string $orderby = 'date',
	bool $rand = false,
	bool|string $read_more = false,
	?string $label = null,
	string|array $type = 'post',
	array|int $terms = [],
	string $taxonomy = '',
	?int $timeline = null,
	string $timeline_start_key = '',
	string $timeline_end_key = '',
	bool $wrap = true,
	?array $posts = null,
	string $output = 'html',
	array $params = [],
	string $context = '',
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
 * @param string       $size    Image size.
 * @param array        $atts    img tag attributes.
 * @param string|null  $context Context for filters.
 * @return string
 */
function plura_wp_post_featured_image( \WP_Post|int $post, string $size = 'large', array $atts = [], ?string $context = null ): string { return ''; }

/**
 * Render a post title, optionally linked.
 *
 * @param \WP_Post|int $post    Post object or ID.
 * @param int          $link    Link mode.
 * @param string       $tag     HTML tag (default 'h2').
 * @param string       $class   CSS class.
 * @param string|null  $context Context for filters.
 * @return string
 */
function plura_wp_post_title( \WP_Post|int $post, int $link = 1, string $tag = 'h2', string $class = '', ?string $context = null ): string { return ''; }

// ─── Images ──────────────────────────────────────────────────────────────────

/**
 * Generate a responsive <img> tag with srcset/sizes.
 *
 * @param int|null    $attachment Attachment ID.
 * @param string      $size       Image size.
 * @param array       $atts       img tag attributes.
 * @param string|null $context    Context for filters.
 * @return string
 */
function plura_wp_image( ?int $attachment = null, string $size = 'full', array $atts = [], ?string $context = null ): string { return ''; }

/**
 * Wrap HTML in an anchor tag with external link detection.
 *
 * @param string             $html    Inner HTML.
 * @param string|array|\WP_Post|int $target URL string, link array, or post object/ID.
 * @param string             $class   CSS class on the anchor.
 * @param string|null        $context Context for filters.
 * @return string
 */
function plura_wp_link( string $html = '', string|array|\WP_Post|int $target = '', string $class = '', ?string $context = null ): string { return ''; }

// ─── Navigation ──────────────────────────────────────────────────────────────

/**
 * Generate breadcrumb navigation HTML.
 *
 * @param string|null $context Context for filters.
 * @return string
 */
function plura_wp_breadcrumbs( ?string $context = null ): string { return ''; }

// ─── WPML ────────────────────────────────────────────────────────────────────

/**
 * Check if WPML is active.
 *
 * @return bool
 */
function plura_wpml(): bool { return false; }

/**
 * Translate a post or term ID to the current WPML language equivalent.
 *
 * @param int    $id        Original post/term ID.
 * @param string $type      'post' or 'term'.
 * @param string $lang      Target language code (defaults to current).
 * @return int
 */
function plura_wpml_id( int $id, string $type = 'post', string $lang = '' ): int { return $id; }

/**
 * Return the current WPML language code.
 *
 * @return string e.g. 'pt', 'en'
 */
function plura_wpml_lang(): string { return ''; }

/**
 * Get the featured image for a post with ACF fallback, WPML-aware.
 *
 * @param \WP_Post|int $post Post object or ID.
 * @param string       $size Image size.
 * @param array        $atts img attributes.
 * @return string
 */
function plura_wpml_featured_image( \WP_Post|int $post, string $size = 'full', array $atts = [] ): string { return ''; }

// ─── Utils ───────────────────────────────────────────────────────────────────

/**
 * Convert an array to an HTML attribute string.
 *
 * @param array $atts Key/value pairs. Boolean true outputs the key only.
 * @return string
 */
function plura_attributes( array $atts ): string { return ''; }

/**
 * Evaluate the boolean state of a value.
 *
 * @param mixed $value
 * @return bool
 */
function plura_bool( mixed $value ): bool { return false; }

/**
 * Explode a string with auto-trimming of resulting values.
 *
 * @param string $separator
 * @param string $string
 * @return array
 */
function plura_explode( string $separator, string $string ): array { return []; }
