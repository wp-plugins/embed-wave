=== Embed Wave ===
Contributors: signpostmarv
Tags: google, wave, embed
Requires at least: 2.8
Tested up to: 2.8.6
Stable tag: 1.1

Embed Waves in your posts & pages!

== Description ==

“Embed Wave” is a plugin for embedding waves on a WordPress blog. It allows multiple waves to be displayed on the same page- useful if you’re going to have multiple waves in a single post, or individual waves across multiple posts being displayed on the front page of your blog.

Waves are embedded by using shortcodes (e.g. [wave id="wave-id"]). Google is the default provider, to change this you’ll simply need to change the type attribute of the shortcode.

Additionally, “Embed Wave” is extensible, in that new providers can be added by other plugins via the WordPress filter system. In fact, the plugin does this itself in order to add Google Wave.

== Installation ==

1. Upload the `embed-wave` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Creating a page with multiple waves using shortcodes.
2. The resulting page with 2 waves embedded.

== ChangeLog ==

1.1 (2009-11-17)
------------------
1. Added support for specifying the width of the wave panel.
2. Added support for alternative content (not using <noscript />, since that would prevent alt content from being displayed if javascript was enabled but the wave embed api failed to load).
3. Added support for the google wave sandbox.

1.0 (2009-11-11)
------------------
1. Added support for Google Wave
2. Made Plugin extensible via add_filter('Marvulous_Embed_Wave::provider')
