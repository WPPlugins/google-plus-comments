<?php

function gplusJS() {
$options = get_option('gpcomments');
if (!isset($options['JS'])) {$options['JS'] = "";}
if ($options['JS'] == 'on') {
?>
<!-- Google+ Comments for WordPress: http://3doordigital.com/wordpress/plugins/google-plus-comments/ -->
<?php 
  wp_register_script('gplus_comments',('https://apis.google.com/js/plusone.js'),false);
  wp_enqueue_script('gplus_comments');
}}
add_action('wp_head', 'gplusJS', 100);

function gplusstyle() {
$options = get_option('gpcomments');
if (!isset($options['responsive'])) {$options['responsive'] = "";}
if ($options['responsive'] == 'on') {
?>
  <style type="text/css">
  div[id^='___comments_'], div[id^='___comments_'] iframe {width: 100% !important;}
  </style>
<?php }}
add_action('wp_head', 'gplusstyle', 110);


//COMMENT BOX
function gpcommentbox($content) {
	$options = get_option('gpcomments');
	if (!isset($options['linklove'])) {$options['linklove'] = "off";}
	if (!isset($options['posts'])) {$options['posts'] = "off";}
	if (!isset($options['pages'])) {$options['pages'] = "off";}
	if (!isset($options['homepage'])) {$options['homepage'] = "off";}
	if (
	   (is_single() && $options['posts'] == 'on') ||
       (is_page() && $options['pages'] == 'on') ||
       ((is_home() || is_front_page()) && $options['homepage'] == 'on')) {
		if ($options['title'] != '') {
			if ($options['titleclass'] == '') {
				$commenttitle = "<h3>";
			} else {
				$commenttitle = "<h3 class=\"".$options['titleclass']."\">";
			}
			$commenttitle .= $options['title']."</h3>";
		}
		$content .= "<!-- Google+ Comments for WordPress: http://3doordigital.com/wordpress/plugins/google-plus-comments/ -->".$commenttitle."<g:comments href=\"".get_permalink()."\" width=\"".$options['width']."\" first_party_property=\"BLOGGER\" view_type=\"FILTERED_POSTMOD\"></g:comments>";
     }

    if ($options['linklove'] != 'no') {
        if ($options['linklove'] != 'off') {
            if (empty($gpcomments[linklove])) {
      $content .= '<p>Powered by <a href="http://3doordigital.com/wordpress/plugins/google-plus-comments/">Google+ Comments</a></p>';
    }}
  }
return $content;
}
add_filter ('the_content', 'gpcommentbox', 100);


function gpcommentshortcode($fbatts) {
    extract(shortcode_atts(array(
		"gpcomments" => get_option('gpcomments'),
		"url" => get_permalink(),
    ), $fbatts));
    if (!empty($fbatts)) {
        foreach ($fbatts as $key => $option)
            $gpcomments[$key] = $option;
	}
		if ($gpcomments[title] != '') {
			if ($gpcomments[titleclass] == '') {
				$commenttitle = "<h3>";
			} else {
				$commenttitle = "<h3 class=\"".$gpcomments[titleclass]."\">";
			}
			$commenttitle .= $gpcomments[title]."</h3>";
		}
		$gpcommentbox = "<!-- Google+ Comments for WordPress: http://3doordigital.com/wordpress/plugins/google-plus-comments/ -->".$commenttitle.$commentcount;

      	if ($gpcomments[html5] == 'on') {
			$gpcommentbox .=	"<div class=\"fb-comments\" data-href=\"".$url."\" data-num-posts=\"".$gpcomments[num]."\" data-width=\"".$gpcomments[width]."\" data-colorscheme=\"".$gpcomments[scheme]."\"></div>";

    } else {
    $gpcommentbox .= "<g:comments href=\"".$url."\" width=\"".$gpcomments[width]."\" first_party_property=\"BLOGGER\" view_type=\"FILTERED_POSTMOD\"></g:comments>";
     }
    if ($options['linklove'] != 'no') {
        if ($options['linklove'] != 'off') {
            if (empty($gpcomments[linklove])) {
      $gpcommentbox .= '<p>Powered by <a href="http://3doordigital.com/wordpress/plugins/google-plus-comments/">Google+ Comments</a></p>';
    }}}
  return $gpcommentbox;
}
add_filter('widget_text', 'do_shortcode');
add_shortcode('gp-comments', 'gpcommentshortcode');


?>