# Motion Button for Elementor

**Developer:** devtanvirul — https://devtanvirul.com  
**Version:** 1.0.7  
**Requirements:** WordPress 6.2+, PHP 7.4+, Elementor Free (or Pro).  
**Price/dependencies:** No Elementor Pro or paid addon required. GSAP is an external open-web CDN dependency only for text animation; a built-in Web Animations fallback keeps button interactions working if blocked.

## Install

1. In WordPress, go to **Plugins → Add New Plugin → Upload Plugin**.
2. Upload `motion-button.zip`, click **Install Now**, then **Activate**.
3. Make sure the free **Elementor** plugin is active.
4. Edit a page with Elementor. Search the widget list for **Motion Button**.
5. Drag the widget into your page, then customize it in the widget panel. This is a **new widget**, not a modification of the native Elementor Button. Replace an existing button with this widget when migrating.
6. Set the URL under **Content & Link → Link**. Default is `#` (a placeholder); replace it before publishing.
7. Clear any caching/minification plugin cache after activating or updating.

## Where to find controls

| Elementor widget section | Editable options |
| --- | --- |
| Content → Content & Link | Text, URL, new tab/nofollow, icon picker, icon position, aria-label |
| Content → Hover Animation | Nine reveal effects, entry/exit speed, easing, icon effect, icon rotation, slide distance, hover scale/lift, magnetic effect, click ripple, independent color and icon timing |
| Content → Text Hover Motion (GSAP) | Off, subtle lift, fade & rise, gentle scale, letter wave, blur reveal; enter/exit duration, distance, scale, stagger, opacity and easing |
| Content → Mobile Motion (Touch & Tap) | **Mobile Animation: Colour + icon only (default), Same as desktop, or Custom**; Tap / Press / Off; phone breakpoint and touch scope; tap auto-reset. In Custom: background reveal, entry/exit duration, easing, icon rotation and animation, GSAP text effects, scale/lift, ripple |
| Content → Accessibility & Behavior | Extra CSS class (older touch-ripple settings are preserved for migrated buttons) |
| Style → Layout | Responsive alignment, width, min height, padding, corner radius, label/icon gap |
| Style → Typography | Elementor typography group: family, size, weight, line height, letter spacing, etc. |
| Style → Button Colors & Border | Normal and hover backgrounds/text, border, border color, box shadow |
| Style → Advanced Backgrounds (Optional) | Gradient and image background options for normal and hover reveal |
| Style → Icon Style | Circle/rounded/square/none, responsive icon/icon-container size (0 allowed), icon circle padding, custom corner radius, initial rotation angle, normal/hover icon and circle colors |

### Built-in hover effects

- **Directional** — radial reveal from the mouse entry position, collapses toward the exit position; uses the Web Animations API for interruption-safe transitions.
- **Left, Right, Top, Bottom** — directional wipe.
- **Center** — horizontal center expansion.
- **Diagonal** — polygon diagonal-origin reveal.
- **Fade** — soft opacity transition.
- **None** — immediate background switch with independent text/icon transitions.

### Built-in icon effects

- Rotate (default **+90° on hover**, editable from -360° to +360°)
- Diagonal slide (distance adjustable)
- Scale
- Pop
- None

### Global Accent color

Normal button background, hover text, normal icon foreground, and hover icon background reference **Elementor's Global Accent color**, with `#E5212B` as a CSS fallback. Change that global color from **Elementor → Site Settings → Global Colors → Accent**. Individual widget color controls can override global styles.

### Default icon angle

The default icon is a vertical up arrow rotated **45° at rest**, producing a diagonal up-right arrow. Hover adds **90°**, so the final total rotation is 135°. If you select an icon already pointing diagonally, set *Icon Initial Angle* to 0°.

### Animations and accessibility

- Animation disables movement when a visitor requests **reduced motion**.
- Keyboard focus receives visible focus styling and hover state.
- The Icon glyph is decorative; the button text or accessible label provides the link's accessible name.
- Touch screens use a separate Tap/Press interaction with custom reveal, icon and text motion. Link navigation still occurs on the **first tap**, and may interrupt a long animation. No artificial delay or second-tap navigation is required.
- No tracking or global styling overrides. For GSAP text motion, WordPress loads the GSAP library from jsDelivr (the CDN sees the visitor’s IP as with any third-party resource); offline/blocked-CDN browsing uses a limited native Web Animations fallback.

## Technical notes

- Assets: `assets/css/button.css` and `assets/js/button.js`. They are registered with WordPress and included as Elementor widget dependencies.
- Widget: `includes/class-rmb-button-widget.php`.
- Plugin bootstrap: `raddito-motion-button.php`.
- The CSS and JavaScript are scoped under the widget's own `rmb-button` class.
- Controls are registered using Elementor's Widget API and generated per-widget stylesheet selectors.
- No direct database writes or admin settings changes are performed by this plugin.

## Known boundaries

- This is **not** an Elementor Pro feature and doesn't extend the native Button widget's panel. Use the new Motion Button widget.
- For a destination link, supply a real URL; placeholder `#` must be replaced manually.
- A WordPress/Elementor integration smoke test is recommended on a staging site before production, especially when using aggressive cache/JS optimization plugins.

**Developer:** devtanvirul.  
**Portfolio:** https://devtanvirul.com  
**License:** GPL-2.0-or-later.

**Compatibility note:** The internal widget ID remains `raddito-motion-button` and plugin directory remains `raddito-motion-button` so existing Elementor pages continue to recognize the widget and the plugin can replace the earlier release. The visible plugin and widget names are **Motion Button for Elementor**.

## Changelog

Version history, including what was fixed in each release and how it was
verified, is in [CHANGELOG.md](CHANGELOG.md).

## Building the installable ZIP

The repository root is the plugin root, so a plain archive of the checkout
would install into the wrong directory. Run `./build.sh` to produce
`dist/motion-button-for-elementor-v<version>.zip` with the
`raddito-motion-button/` folder WordPress expects.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
