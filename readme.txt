=== Motion Button ===
Tags: elementor, animated button, hover, directional, widget
Requires at least: 6.2
Requires PHP: 7.4
Stable tag: 1.0.4
License: GPLv2 or later

Premium-style directional reveal buttons with complete Elementor Free styling controls, icon rotation, and independent mobile tap/press animations.

== Installation ==
Upload the ZIP in Plugins > Add New Plugin > Upload Plugin. Activate Elementor Free, then activate Motion Button. In the Elementor editor, search for "Motion Button" and drag it into your page.

== Features ==
9 background hover effects; adjustable directional animation; 5 icon animations with 90-degree default hover rotation; icon size, shape, normal/hover palette; global Accent fallback #E5212B; responsive layout, typography and spacing; click ripple and optional magnetic cursor; keyboard focus and reduced-motion support.

== Mobile animation controls ==
Open Elementor > Motion Button > Content > Mobile Motion (Touch & Tap). Set Tap / Press / Off; customize the phone breakpoint, reveal effect, durations, icon motion/rotation, GSAP text effect/duration, and optional ripple. Desktop and mobile animation values remain independent. Tap effects do not delay link navigation.

== Notes ==
This plugin adds a new Elementor widget; it does not alter the built-in Elementor button. The default link is a placeholder (#), which should be replaced before publishing. No Pro features are required.

Developer: Tanvirul Islam
Portfolio: https://devtanvirul.com

== Changelog ==
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
