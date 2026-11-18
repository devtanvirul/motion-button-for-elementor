# Changelog

All notable changes to **Motion Button for Elementor**. The current release is
1.0.7; each entry below records what changed, why, and how it was checked.

Releases before 1.0.2 predate this documented history.

## v1.0.7 — Lighter Mobile Motion and a Leaner Runtime

**Content → Mobile Motion (Touch & Tap) → Mobile Animation** now has three modes.

| Option | On a phone, a tap… |
| --- | --- |
| **Colour + icon only** (new default) | changes the background colour and the text colour, and animates the icon. No background sweep, no text motion, no lift, no scale, no ripple. |
| **Same as desktop** | reuses every desktop animation value (the 1.0.6 default). |
| **Custom mobile animation** | uses the separate mobile values below (the pre-1.0.6 behaviour). |

Colour + icon only pins just the settings that would otherwise add motion —
reveal effect `none`, text effect `none`, lift `0`, scale `100%`, ripple off.
Easing, colour speed and the icon settings are left to inherit from desktop, so
the icon animates exactly as designed on desktop and the colour change runs at
the desktop colour speed. It needs no JavaScript animation at all: the colour
change is the existing CSS `background-color`/`color` transition and the icon
rotation is an existing CSS transform.

### Performance

- **GSAP is no longer a hard dependency.** It was loaded on every page carrying
  the widget. Each text effect already has a native Web Animations fallback, and
  `none` does no work at all, so the library is now requested from `render()`
  only when a text effect is actually in play on desktop or mobile. A button
  with **Text Animation → None** no longer loads it: **−71 KB raw / −28 KB
  gzipped**, and one fewer third-party CDN connection. The runtime reads
  `window.gsap` at interaction time, so load order does not matter.
- **Lookup tables are built once per page** instead of once per button, and the
  hot path iterates them without allocating a key array on every
  mobile/desktop switch.
- **The mobile pairing is derived, not duplicated.** Each desktop key maps to its
  mobile counterpart by name (`textDuration` → `mobileTextDuration`), so the
  second table is gone and cannot drift out of sync.
- **The coarse-pointer media query is cached** rather than re-evaluated on every
  viewport check.
- **Redundant dataset writes are skipped** — each write touches the DOM.
- **All pointer listeners are passive** (`pointerdown`, `pointerup` and
  `pointermove` already were; `pointerenter`, `pointerleave` and `pointercancel`
  now are too).

`button.js` grew by 291 bytes raw / 250 gzipped to carry the new mode, which the
conditional GSAP load repays many times over.

### Verified

Chromium with iPhone 13 emulation, real touch events, against markup from the
widget's own `render()`:

- On a page with **GSAP deliberately absent**, a tap moves the background
  `rgb(229,33,43) → rgb(255,255,255)` and the label `rgb(255,255,255) →
  rgb(229,33,43)`, and rotates the icon 45° → 135°. The reveal layer stays
  `display: none`, no ripple node is created, the label keeps no inline styles,
  and the colours return after the tap auto-reset.
- Desktop on the same page is untouched: still the directional sweep, still the
  `lift` text effect — and the label text still animates with GSAP absent,
  confirming the native fallback works.
- All six conditional-GSAP cases resolve correctly, including custom mobile
  `wave` with desktop text effect `None`.
- "Same as desktop" and "Custom" both still behave as in 1.0.6.

Not verified here: a physical handset. CSS is byte-identical to 1.0.5.

### Possible next step

GSAP still comes from `cdn.jsdelivr.net`. Vendoring it into the plugin would
remove the third-party connection and serve it same-origin, at the cost of
~73 KB in the plugin and a manual update when GSAP changes. Not done here,
since it is a packaging decision rather than a bug.

## v1.0.6 — Mobile Matches Desktop

**Open Content → Mobile Motion (Touch & Tap) → Mobile Animation.**

| Option | Behaviour |
| --- | --- |
| **Same as desktop** (new default) | Phones reuse every desktop animation value — background effect, enter/exit duration, easing, icon effect and rotation, text effect and timing, colour speed, lift and scale. |
| **Custom mobile animation** | The separate mobile values apply, exactly as in v1.0.4–1.0.5. All previously saved mobile values are kept and reappear here. |

`Touch Animation Behavior` (Tap / Press / Off), `Apply Mobile Settings To`,
`Phone Max Width` and `Tap Auto-Reset Delay` are not animation values, so they
stay available in both modes. Touch motion still runs on phones in
"Same as desktop" mode — it simply runs with the desktop values.

### What was wrong

- **Mobile never matched desktop.** The mobile controls each carried a concrete
  default rather than "Inherit Desktop", and the runtime treated any non-inherit
  value as an override. On a phone, 12 values were replaced — including the
  background effect, which became *Expand from Center* regardless of the desktop
  effect, at 420/360 ms instead of 700/620 ms.
- **The odd mid-transition text colour on phones** followed from that. *Expand
  from Center* grows the reveal from the middle of the button, which is where the
  label sits, so the white reveal reached the text while the label colour was
  still part-way through its own cross-fade — measured passing through
  `rgb(246,178,182)` and `rgb(234,77,85)`. Mobile also used a shorter colour
  speed (300 ms vs 350 ms), so the two were never in step. Desktop's cursor-origin
  circle starts as a point and grows, so the same cross-fade reads correctly.
  With mobile inheriting desktop, the phone now grows the circle from the finger
  and the colour tracks it.
- **Mobile click ripple could never fire.** The "Use existing ripple settings"
  branch also required `data-touch`, which is emitted from a `mobile_animation`
  setting that is no longer a registered control, so it was always `no`.
- **Desktop Button Hover Scale was flattened on phones.** The runtime always
  wrote `--rmb-scale` while mobile was active, defaulting to 100%, overriding a
  desktop hover scale. It is now only written when a mobile scale is actually set.
- **Back-button return left buttons stuck in hover colours.** A phone restoring
  the page from the back/forward cache kept the touch state with no pointer event
  to clear it. Buttons now reset on restore.

### Verified

Checked in Chromium with iPhone 13 emulation, driving real touch events against
markup produced by the widget's own `render()`:

- In "Same as desktop", all 13 animation data attributes and all 8 CSS custom
  properties resolve **identically** on phone and desktop, while
  `data-rmb-mobile-active` stays `yes`.
- The touch ripple fires with Click Ripple on and Mobile Ripple on "inherit".
- In "Custom", the phone still overrides 12 values, so existing custom
  configurations are unchanged.

Not verified here: a physical handset, and the back/forward-cache reset, which
needs real history navigation. CSS is byte-identical to 1.0.5.

## v1.0.5 — Plugin Name and Author Branding

- Plugin name is now **Motion Button for Elementor**, shown in **Plugins → Installed Plugins** as *Motion Button for Elementor — By devtanvirul*.
- The Elementor widget label in the editor panel is now **Motion Button for Elementor**. Searching "Motion Button" still finds it.
- Author is shown as **devtanvirul** (portfolio link unchanged).
- **Nothing internal changed.** Plugin folder (`raddito-motion-button`), widget ID (`raddito-motion-button`), text domain, PHP class, `RMB_*` constants and `rmb-` CSS/JS prefixes are all identical to 1.0.4.
- Because the folder and widget ID are unchanged, this ZIP installs **over** 1.0.4 as a normal update, and every Motion Button already placed on a page keeps its settings and keeps rendering.
- No functional, animation, control or styling changes.

## v1.0.4 — Independent Mobile Animation Controls

Open **Content → Mobile Motion (Touch & Tap)**. These settings control touch/mobile animation independently of the desktop settings; they do not replace the separate responsive layout/padding controls in **Style → Layout**.

| Control group | Options |
| --- | --- |
| Touch Behavior | Tap (animate then reset), Press (animate while held), Off |
| Device scope | Phone width only (default <= 767px) or phone + all touch screens; custom phone breakpoint 320–1200px |
| Background | Inherit Desktop, Directional from tap position, Left/Right/Top/Bottom, Center, Diagonal, Fade, Instant; enter/exit duration and easing |
| Icon | Inherit, Rotate, Slide, Scale, Pop, None; custom rotation **-360° to +360°** (default **+90°**); independent speed and slide distance |
| Text (GSAP) | Inherit, None, Lift, Fade & Rise, Scale, Letter Wave, Blur; separate enter/exit timing, distance, scale, stagger, opacity and easing |
| Extra | Mobile color transition speed, whole-button lift/scale, configurable tap auto-reset delay and click ripple |

### Recommended mobile defaults

- Touch behavior: **Tap: animate, then auto-reset**.
- Reveal: **Center Expand** (420ms in / 360ms out).
- Icon: **Rotate +90°** (400ms).
- Text: **Subtle Lift, 2px** (320ms in / 260ms out).
- Tap auto-reset: **500ms**. This timer controls visual reset only — it never postpones a link.

For *real mobile tap testing*, use a phone or browser touch emulation. Elementor's responsive preview changes viewport size, but a desktop mouse continues to simulate a hover rather than an actual finger press. The mobile animation styling is still selected at the configured phone breakpoint.

Touch behavior Off disables touch motion; keyboard focus still supports the desktop/widget animation as an accessible interaction when appropriate. Reduced-motion preferences disable motion. Upgrading preserves the internal widget ID and previous desktop settings; mobile settings are new and editable per button instance.

## v1.0.3 — GSAP Text Hover Motion

- Added **Content → Text Hover Motion (GSAP)** with six modes (including Off).
- Default is a subtle 3px upward hover lift, animated with GSAP.
- Custom control over entry speed, exit speed, travel distance, scale, stagger, reveal start opacity, and easing. Each can be set independently; movement 0 removes positional offset.
- Letter Wave keeps spaces and word wrapping; characters are hidden from assistive technology and the link gets an accessible name.
- Keyboard focus animates like hover; reduced-motion preferences disable motion.
- Loads GSAP 3.15.0 from `cdn.jsdelivr.net` as an Elementor widget dependency. This requests a third-party script when the widget is used; no tracking is performed by this plugin. If the CDN is blocked, text effects degrade to lightweight native Web Animations API where supported.
- Retains v1.0.2 **zero-padding / sizing** fixes and the original widget ID and plugin directory, so replacement updates existing instances.

### Update from earlier versions

Upload `motion-button.zip` and choose **Replace current with uploaded**. If your button still looks padded after setting Button Padding = 0, remember to set Layout → **Minimum Height**, **Inner Content Padding**, **Text/Icon Gap**, plus Icon Style → **Icon Width/Height/Padding** to 0 independently. Elementor templates/containers can add external spacing.

## v1.0.2 — Zero Padding / Full Spacing Control Fix

The original version included CSS fallback values for padding (8/10/8/32px), minimum height (64px), icon container dimensions (42px), and text/icon gap (14px). These have **all been removed from the CSS defaults**, so clearing a control will not resurrect hidden fallback spacing. New widgets retain the familiar design through Elementor's *visible, editable control defaults*. Existing widgets keep their settings.

**To make a compact or zero-spacing button:**

1. Style → Layout → **Button Padding**: set top/right/bottom/left to `0`.
2. Style → Layout → **Minimum Height (0 = None)**: set to `0`. Leave **Exact Button Height** empty for auto sizing.
3. Style → Layout → **Text / Icon Gap**: set to `0`; also use **Inner Content Padding** of `0`.
4. Style → Icon Style → **Icon Circle Width**, **Icon Circle Height** and **Icon Circle Padding** are independent of button padding. Lower these sizes as desired. **Icon Glyph Size** is separately controlled.
5. Style → Typography → **Line Height** affects the natural text height. Parent Elementor Containers may also have their own padding or gap.

All listed dimensions support explicit zero values; padding settings are responsive for desktop/tablet/mobile. Duration values for reveal animations now support 0ms as well.

**Updating:** upload the latest ZIP and replace the current plugin (the plugin folder and Elementor widget internal ID are unchanged). Then clear page/CDN caches and regenerate Elementor CSS if a cached visual persists.
