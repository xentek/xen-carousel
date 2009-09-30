=== XEN Carousel ===
Contributors: xenlab
Donate link: http://xentek.net/code/wordpress/plugins/xen-carousel/
Tags: jcarousel,jcarousellite,carousel,slideshow,images,ajax,javascript,posts,pages,gallery
Requires at least: 2.8
Tested up to: 2.9
Stable tag: trunk

== Description ==

**The balance of form and function.**

Call out sections of your site by easily creating a carousel of images, associated to posts or pages, for display on your home page or anywhere on your site. The carousel purposely does not come styled, but is instead semantically marked up with #IDs and .classes to make it easy for you to integrate it into your theme without much effort.

== Installation ==

1. Download the xen-carousel.zip file, unzip and upload the whole directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Upload Images to the Media Library.
1. Associate those images to posts and/or pages by searching for them in the XEN Carousel 'Meta Box' provided on the Add/Edit screens.
1. Add this template tag to your theme templates where ever you would like to show the carousel: `<?php xencarousel_output(); ?>`
1. Add styles to your CSS file to make it fit in with your site.

== Frequently Asked Questions ==

= Does this plugin have any options or settings =

Not yet, but in the future I plan to add an options screen that allows you to control various options of the carousel, including speed, animation effects, and more. The defaults should be pretty good to get you going.

= What kind of mark up does it ouput? What are the IDs and Classes can I use to style it? =

Here is an example:

	<div id="xencarouselcontainer">
		<div id="xencarousel1" class="xencarousel" rel="xencarousel1">
			<ul>
				<li>
					<a href="http://example.com/link-to-post-1" title="Post Title 1">
						<img src="http://example.com/wp-content/uploads/image1.jpg" alt="Post Title 1" width="300" height="300" />
					</a>
				</li>
				<li>
					<a href="http://example.com/link-to-post-2" title="Post Title 2">
						<img src="http://example.com/wp-content/uploads/image2.jpg" alt="Post Title 2" width="300" height="300" />
					</a>
				</li>
			</ul>
		</div>
		<span class="prev">Previous</span>
		<span class="next">Next</span>
		<div id="xencarouseloverlay"></div>
	</div>

All values are automatically determined from the Post/Page you associated with your slide with and the image size as reported by the Media Library.

= What size should I make my slides? =

They can be any size that you'd like, but for best results each slide should be the same size. The plugin does not do any image manipulation.

= I want to help with development of this Plugin! =

The project is now hosted on [github.com](http://github.com/xentek/xen-carousel). Just fork the project and send me a pull request.

[New to git?](http://delicious.com/ericmarden/git)

== Screenshots ==

1. Example of a fully styled XEN Carousel in action. *Courtesy of AgileDevelopmentWithRob.com*

== Changelog ==

= 0.10 =
* Fixed an issue with the use of the jQuery Easing plugin
* Minified all js scripts.
* Added a proper README.txt
* 

== License ==

The XEN Carousel plugin was developed by Eric Marden, and is provided with out warranty under the GPLv2 License. More info and other plugins at: http://xentek.net

Copyright 2008  Eric Marden  (email : wp@xentek.net)

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA