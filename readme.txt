=== Motion Button for Elementor ===
Contributors: devtanvirul
Tags: elementor, animated button, hover, directional, widget
Requires at least: 6.2
Requires PHP: 7.4
Stable tag: 1.0.7
License: GPLv2 or later

Premium-style directional reveal buttons with complete Elementor Free styling controls, icon rotation, and independent mobile tap/press animations.

== Installation ==
Upload the ZIP in Plugins > Add New Plugin > Upload Plugin. Activate Elementor Free, then activate Motion Button for Elementor. In the Elementor editor, search for "Motion Button" and drag it into your page.

== Features ==
9 background hover effects; adjustable directional animation; 5 icon animations with 90-degree default hover rotation; icon size, shape, normal/hover palette; global Accent fallback #E5212B; responsive layout, typography and spacing; click ripple and optional magnetic cursor; keyboard focus and reduced-motion support.

== Mobile animation controls ==
Open Elementor > Motion Button for Elementor > Content > Mobile Motion (Touch & Tap). Set Tap / Press / Off; customize the phone breakpoint, reveal effect, durations, icon motion/rotation, GSAP text effect/duration, and optional ripple. Desktop and mobile animation values remain independent. Tap effects do not delay link navigation.

== Notes ==
This plugin adds a new Elementor widget; it does not alter the built-in Elementor button. The default link is a placeholder (#), which should be replaced before publishing. No Pro features are required.

Developer: devtanvirul
Portfolio: https://devtanvirul.com

== Changelog ==
= 1.0.7 =
* New default for phones: "Colour + icon only". A tap changes the background and text colour and animates the icon, with no background sweep, no text motion and no lift, scale or ripple. Mobile Animation still offers "Same as desktop" and "Custom mobile animation".
* Performance: GSAP is no longer a hard dependency. It is requested only when a text effect is actually in play, so a button with Text Animation set to None loads 28 KB (gzipped) less. Every text effect already has a native Web Animations fallback.
* Performance: the per-button lookup tables are built once for the whole page instead of per button, the mobile/desktop pairing is derived rather than duplicated, the coarse-pointer media query is cached, redundant dataset writes are skipped, and the remaining pointer listeners are passive.
* Desktop behaviour, colours, CSS and all existing settings are unchanged.

= 1.0.6 =
* Mobile now animates exactly like desktop by default. New "Mobile Animation" control at the top of Mobile Motion: "Same as desktop" (new default) or "Custom mobile animation".
* Fixed: phones always overrode 12 desktop animation values, including the background effect itself (Expand from Center instead of the desktop effect), so mobile never matched desktop.
* Fixed: the odd mid-transition text colour on phones, caused by the centre-origin mobile reveal covering centred label text before the colour caught up, plus a shorter mobile colour speed.
* Fixed: mobile click ripple could never fire when Mobile Click Ripple was left on "Use existing ripple settings".
* Fixed: desktop Button Hover Scale was flattened to 100% on phones.
* Buttons returning via the phone's Back button no longer stay stuck in their hover colours.
* Custom mobile values are preserved; switch Mobile Animation to "Custom" to get the previous behaviour back.

= 1.0.5 =
* Renamed the plugin and Elementor widget label to "Motion Button for Elementor"; author now shown as devtanvirul.
* Plugin folder, widget ID, text domain and CSS/JS prefixes are unchanged, so buttons already placed on pages keep working and this installs over 1.0.4.
* No functional, styling or control changes.

= 1.0.2 =
* Removed hidden CSS fallback spacing. Button padding, minimum height, gap, and icon dimensions can be set to zero.
* Added optional exact height, content padding, icon padding, content justification controls.
* Allows zero reveal duration and preserves existing widget IDs / settings.

= 1.0.3 =
* Added optional GSAP-powered text hover animations: subtle lift, fade/rise, scale, letter wave and soft blur, each with editable timing and motion.
* Kept full zero-spacing controls introduced in v1.0.2.
* Web Animations fallback for blocked GSAP CDN; respects reduced-motion settings.

= 1.0.4 =
* Added independent Mobile Motion panel with phone breakpoint and all-touch-screen scope.
* Added Tap / Press / Off behavior, tap auto-reset, background reveal/easing/durations.
* Added separate mobile icon effect/90-degree rotation, text GSAP effects/timing, scale/lift and ripple settings.
* Kept first-tap navigation, desktop settings and existing widget ID intact.
* Handles responsive switching and keyboard/reduced-motion behavior.
