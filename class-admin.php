<?php
define("GP_PLUGIN_NAME","Google+ Comments");
define("GP_PLUGIN_TAGLINE","Adds Google+ Comments to your posts and pages!");
define("GP_PLUGIN_URL","http://3doordigital.com/wordpress/plugins/google-plus-comments/");
define("GP_EXTEND_URL","http://wordpress.org/extend/plugins/google-plus-comments-plugin/");
define("GP_AUTHOR_TWITTER","alexmoss");
define("GP_DONATE_LINK","https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WFVJMCGGZTDY4");

add_action('admin_init', 'gpcomments_init' );
function gpcomments_init(){
	register_setting( 'gpcomments_options', 'gpcomments' );
	$new_options = array(
		'posts' => 'on',
		'pages' => 'off',
		'homepage' => 'off',
		'title' => 'Google+ Comments',
		'titleclass' => '',
		'width' => '580',
		'responsive' => 'on',
		'JS' => 'on',
		'linklove' => 'off',
	);
	add_option( 'gpcomments', $new_options );
}


add_action('admin_menu', 'show_gpcomments_options');
function show_gpcomments_options() {
	add_options_page('Google+ Comments Options', 'Google+ Comments', 'manage_options', 'gpcomments', 'gpcomments_options');
}


function gpcomments_fetch_rss_feed() {
    include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed("http://3doordigital.com/feed");	
	if ( is_wp_error($rss) ) { return false; }	
	$rss_items = $rss->get_items(0, 3);
    return $rss_items;
}   

// ADMIN PAGE
function gpcomments_options() {
$domain = get_option('siteurl');
$domain = str_replace('http://', '', $domain);
$domain = str_replace('www.', '', $domain);
?>
    <link href="<?php echo plugins_url( 'admin.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">
    <div class="pea_admin_wrap">
        <div class="pea_admin_top">
            <h1><?php echo GP_PLUGIN_NAME?> <small> - <?php echo GP_PLUGIN_TAGLINE?></small></h1>
        </div>

        <div class="pea_admin_main_wrap">
            <div class="pea_admin_main_left">
                <div class="pea_admin_signup">
                    Want to know about updates to this plugin without having to log into your site every time? Want to know about other cool plugins we've made? Add your email and we'll add you to our very rare mail outs.

                    <!-- Begin MailChimp Signup Form -->
                    <div id="mc_embed_signup">
                    <form action="http://peadig.us5.list-manage2.com/subscribe/post?u=e16b7a214b2d8a69e134e5b70&amp;id=eb50326bdf" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div class="mc-field-group">
                        <label for="mce-EMAIL">Email Address
                    </label>
                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL"><button type="submit" name="subscribe" id="mc-embedded-subscribe" class="pea_admin_green">Sign Up!</button>
                    </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>	<div class="clear"></div>
                    </form>
                    </div>

                    <!--End mc_embed_signup-->
                </div>

		<form method="post" action="options.php" id="options">
			<?php settings_fields('gpcomments_options'); ?>
			<?php $options = get_option('gpcomments'); 
if (!isset($options['linklove'])) {$options['linklove'] = "";}
if (!isset($options['posts'])) {$options['posts'] = "";}
if (!isset($options['pages'])) {$options['pages'] = "";}
if (!isset($options['homepage'])) {$options['homepage'] = "";}
if (!isset($options['JS'])) {$options['JS'] = "";}
?>
			<h3 class="title">Main Settings</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="JS">Enable Google+ JS Call</label></th>
					<td><input id="JS" name="gpcomments[JS]" type="checkbox" value="on" <?php checked('on', $options['JS']); ?> /> <small>only disable this if you already have the Google+ JS call enabled elsewhere</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="linklove">Credit</label></th>
					<td><input id="credit" name="gpcomments[linklove]" type="checkbox" value="on" <?php checked('on', $options['linklove']); ?> /></td>
				</tr>
			</table>

			<h3 class="title">Display Settings</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="posts">Posts</label></th>
					<td><input id="posts" name="gpcomments[posts]" type="checkbox" value="on" <?php checked('on', $options['posts']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="pages">Pages</label></th>
					<td><input id="pages" name="gpcomments[pages]" type="checkbox" value="on" <?php checked('on', $options['pages']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="homepage">Homepage</label></th>
					<td><input id="homepage" name="gpcomments[homepage]" type="checkbox" value="on" <?php checked('on', $options['homepage']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="width">Width</label></th>
					<td><input id="width" type="text" name="gpcomments[width]" value="<?php echo $options['width']; ?>" /> <small>default is <strong>580</strong></small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="responsive">Responsive Comment Box</label></th>
					<td><input id="responsive" name="gpcomments[responsive]" type="checkbox" value="on" <?php checked('on', $options['responsive']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="title">Title</label></th>
					<td><input id="title" type="text" name="gpcomments[title]" value="<?php echo $options['title']; ?>" /> with a CSS class of <input type="text" name="gpcomments[titleclass]" value="<?php echo $options['titleclass']; ?>" /></td>
				</tr>
			</table>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

               <div class="pea_admin_box">
			<h3 class="title">Using the Shortcode</h3>
			<table class="form-table">
				<tr valign="top"><td>
<p>The settings above are for automatic insertion of the Google+ Comment box.</p>
<p>You can insert the comment box manually in any page or post or template by simply using the shortcode <strong>[gp-comments]</strong>. To enter the shortcode directly into templates using PHP, enter <strong>echo do_shortcode('[gp-comments]');</strong></p>
<p>You can also use the options below to override the the settings above.</p>
<ul>
<li><strong>url</strong> - leave blank for current URL</li>
<li><strong>width</strong> -  minimum must be <strong>350</strong></li>
<li><strong>title</strong> with a CSS class of <strong>titleclass</strong></li>
<li><strong>linklove</strong> - enter "1" to link to the plugin</li>
</ul>
<p>Here's an example of using the shortcode:<br><code>[gp-comments url="http://3doordigital.com/wordpress/plugins/google-plus-comments/" width="375"]</code></p>
<p>You can also insert the shortcode directly into your theme with PHP:<br><code>&lt;?php echo do_shortcode('[gpcomments url="http://3doordigital.com/wordpress/plugins/google-plus-comments/" width="375" count="off" num="3"]'); ?&gt;</code>
					</td>
				</tr>
			</table>
</div>
</div>
            <div class="pea_admin_main_right">
                <div class="pea_admin_logo">
            <a href="http://3doordigital.com/?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Google+%2BComments%2BAdmin" target="_blank"><img src="<?php echo plugins_url( '3dd-logo.png' , __FILE__ ); ?>" width="250" height="92" title="3 Door Digital"></a>
                </div>
                <div class="pea_admin_box">
                    <h2>Like this Plugin?</h2>
<a href="<?php echo GP_EXTEND_URL; ?>" target="_blank"><button type="submit" class="pea_admin_green">Rate this plugin	&#9733;	&#9733;	&#9733;	&#9733;	&#9733;</button></a><br><br>
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=181590835206577";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                    <div class="fb-like" data-href="<?php echo GP_PLUGIN_URL; ?>" data-send="true" data-layout="button_count" data-width="250" data-show-faces="true"></div>
                    <br>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo GP_PLUGIN_URL; ?>" data-text="Just been using <?php echo GP_PLUGIN_NAME; ?> #WordPress plugin" data-via="<?php echo AUTHOR_TWITTER; ?>" data-related="WPBrewers">Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <br>
<a href="http://bufferapp.com/add" class="buffer-add-button" data-text="Just been using <?php echo GP_PLUGIN_NAME; ?> #WordPress plugin" data-url="<?php echo GP_PLUGIN_URL; ?>" data-count="horizontal" data-via="<?php echo AUTHOR_TWITTER; ?>">Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>
<br>
                    <div class="g-plusone" data-size="medium" data-href="<?php echo GP_PLUGIN_URL; ?>"></div>
                    <script type="text/javascript">
                      window.___gcfg = {lang: 'en-GB'};

                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>
                    <br>
                    <su:badge layout="3" location="<?php echo GP_PLUGIN_URL?>"></su:badge>
                    <script type="text/javascript">
                      (function() {
                        var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
                        li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
                      })();
                    </script>
                </div>

<center><a href="<?php echo DONATE_LINK; ?>" target="_blank"><img class="paypal" src="<?php echo plugins_url( 'paypal.gif' , __FILE__ ); ?>" width="147" height="47" title="Please Donate - it helps support this plugin!"></a></center>

                <div class="pea_admin_box">
                    <h2>About the Author</h2>

                    <?php
                    $default = "http://reviews.evanscycles.com/static/0924-en_gb/noAvatar.gif";
                    $size = 70;
                    $alex_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( "alex@peadig.com" ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
                    ?>

                    <p class="pea_admin_clear"><img class="pea_admin_fl" src="<?php echo $alex_url; ?>" alt="Alex Moss" /> <h3>Alex Moss</h3><br><a href="https://twitter.com/alexmoss" class="twitter-follow-button" data-show-count="false">Follow @alexmoss</a>
<div class="fb-subscribe" data-href="https://www.facebook.com/alexmoss1" data-layout="button_count" data-show-faces="false" data-width="220"></div>
</p>
                    <p class="pea_admin_clear">Alex Moss is the Co-Founder and Technical Director of 3 Door Digital. With offices based in Manchester, UK and Tel Aviv, Israel he manages WordPress development as well as technical aspects of digital consultancy. He has developed several WordPress plugins (which you can <a href="http://3doordigital.com/wordpress/plugins/?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Google+%2BComments%2BAdmin" target="_blank">view here</a>) totalling over 350,000 downloads.</p>
</div>

                <div class="pea_admin_box">
                    <h2>More from 3 Door Digital</h2>
    <p class="pea_admin_clear">
                    <?php
$gpcommentsfeed = gpcomments_fetch_rss_feed();
                echo '<ul>';
                foreach ( $gpcommentsfeed as $item ) {
			    	$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), $protocolls=null, 'display' ) );
					echo '<li>';
					echo '<a href="'.$url.'?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Google+%2BComments%2BRSS">'. esc_html( $item->get_title() ) .'</a> ';
					echo '</li>';
			    }
                echo '</ul>'; 
                    ?></p>
                </div>


            </div>
        </div>
    </div>



<?php
}

?>