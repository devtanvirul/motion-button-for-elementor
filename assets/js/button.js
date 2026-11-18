/** Motion Button v1.0.6 — optional GSAP-powered hover text animation; WAAPI fallback. */
(function () {
    'use strict';

    const SELECTOR = '.rmb-button';
    const EASINGS = {
        premium: 'cubic-bezier(0.22, 1, 0.36, 1)',
        standard: 'cubic-bezier(0.42, 0, 0.58, 1)',
        gentle: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
        snappy: 'cubic-bezier(0.2, 0.8, 0.2, 1)',
        linear: 'linear'
    };
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const activeButtons = new Set();
    let resizeFrame = 0;


    function number(value, fallback, min, max) {
        const parsed = Number(value);
        return Number.isFinite(parsed) ? Math.min(max, Math.max(min, parsed)) : fallback;
    }

    function position(button, event) {
        const rect = button.getBoundingClientRect();
        if (!event || !Number.isFinite(event.clientX) || !Number.isFinite(event.clientY)) {
            return { x: rect.width / 2, y: rect.height / 2, width: rect.width, height: rect.height };
        }
        return {
            x: Math.max(0, Math.min(rect.width, event.clientX - rect.left)),
            y: Math.max(0, Math.min(rect.height, event.clientY - rect.top)),
            width: rect.width,
            height: rect.height
        };
    }

    /** Split text only for the optional letter-wave effect. Do not create extra spaces. */
    function prepareLetterWave(button, textNode) {
        if (!textNode || textNode.dataset.rmbSplit === '1') return [];
        textNode.dataset.rmbSplit = '1';
        const plainText = textNode.textContent || '';
        const frag = document.createDocumentFragment();
        const tokens = plainText.split(/(\s+)/u);
        const split = window.Intl && typeof Intl.Segmenter === 'function'
            ? function (word) { return Array.from(new Intl.Segmenter(undefined, { granularity: 'grapheme' }).segment(word), (part) => part.segment); }
            : function (word) { return Array.from(word); };
        tokens.forEach(function (token) {
            if (!token) return;
            if (/^\s+$/u.test(token)) {
                frag.appendChild(document.createTextNode(token));
                return;
            }
            const word = document.createElement('span');
            word.className = 'rmb-button__word';
            word.setAttribute('aria-hidden', 'true');
            split(token).forEach(function (letter) {
                const char = document.createElement('span');
                char.className = 'rmb-button__char';
                char.textContent = letter;
                word.appendChild(char);
            });
            frag.appendChild(word);
        });
        textNode.replaceChildren(frag);
        return Array.from(textNode.querySelectorAll('.rmb-button__char'));
    }

    function animateText(button, textNode, characters, entering) {
        if (!textNode || reducedMotion.matches) return;
        const effect = button.dataset.textEffect || 'lift';
        if (effect === 'none') return;

        const dur = number(entering ? button.dataset.textDuration : button.dataset.textExit, entering ? 420 : 340, 0, 1600) / 1000;
        const distance = number(button.dataset.textDistance, 3, 0, 32);
        const stagger = number(button.dataset.textStagger, 25, 0, 150) / 1000;
        const scale = number(button.dataset.textScale, 103, 100, 120) / 100;
        const opacity = number(button.dataset.textOpacity, 75, 0, 100) / 100;
        const ease = {
            soft: 'power2.out', smooth: 'power3.out', sine: 'sine.out',
            back: 'back.out(1.25)', linear: 'none'
        }[button.dataset.textEase] || 'power2.out';
        const gsap = window.gsap;
        const nodes = effect === 'wave' ? characters : [textNode];
        if (!nodes || nodes.length === 0) return;

        if (gsap && typeof gsap.to === 'function') {
            if (textNode._rmbTextTimeline) {
                textNode._rmbTextTimeline.kill();
                textNode._rmbTextTimeline = null;
            }
            gsap.killTweensOf(nodes);
            const options = { duration: dur, ease: ease, overwrite: 'auto' };
            if (effect === 'lift') {
                gsap.to(textNode, Object.assign({}, options, { y: entering ? -distance : 0 }));
            } else if (effect === 'scale') {
                gsap.to(textNode, Object.assign({}, options, { scale: entering ? scale : 1, transformOrigin: '50% 50%' }));
            } else if (effect === 'rise' || effect === 'blur') {
                if (entering) {
                    const from = { y: distance, opacity: opacity };
                    if (effect === 'blur') from.filter = 'blur(2px)';
                    const to = Object.assign({}, options, { y: 0, opacity: 1 });
                    if (effect === 'blur') to.filter = 'blur(0px)';
                    gsap.fromTo(textNode, from, to);
                } else {
                    const to = Object.assign({}, options, { y: 0, opacity: 1 });
                    if (effect === 'blur') to.filter = 'blur(0px)';
                    gsap.to(textNode, to);
                }
            } else if (effect === 'wave') {
                if (entering) {
                    const tl = gsap.timeline();
                    textNode._rmbTextTimeline = tl;
                    tl.to(characters, { y: -distance, duration: dur * 0.45, ease: ease, stagger: stagger, overwrite: 'auto' });
                    tl.to(characters, { y: 0, duration: dur * 0.55, ease: ease, stagger: stagger }, '<0.10');
                } else {
                    gsap.to(characters, Object.assign({}, options, { y: 0, opacity: 1, stagger: stagger / 2 }));
                }
            }
            return;
        }

        // Offline / blocked-CDN fallback: the button remains functional without GSAP.
        if (typeof textNode.animate !== 'function') return;
        const fallbackEase = 'cubic-bezier(0.22, 1, 0.36, 1)';
        if (effect === 'lift' || effect === 'scale') {
            const to = effect === 'lift' ? `translateY(${entering ? -distance : 0}px)` : `scale(${entering ? scale : 1})`;
            const current = window.getComputedStyle(textNode).transform;
            if (textNode._rmbFallback) textNode._rmbFallback.cancel();
            textNode._rmbFallback = textNode.animate([
                { transform: current === 'none' ? 'none' : current },
                { transform: to }
            ], { duration: dur * 1000, easing: fallbackEase, fill: 'forwards' });
        } else if (effect === 'wave') {
            characters.forEach(function (character, index) {
                if (character._rmbFallback) character._rmbFallback.cancel();
                character._rmbFallback = character.animate([
                    { transform: `translateY(${entering ? distance : 0}px)` },
                    { transform: 'translateY(0px)' }
                ], { duration: dur * 1000, easing: fallbackEase, delay: entering ? index * stagger * 1000 : 0 });
            });
        } else if (entering) {
            const from = { transform: `translateY(${distance}px)`, opacity: opacity };
            const to = { transform: 'translateY(0px)', opacity: 1 };
            if (effect === 'blur') { from.filter = 'blur(2px)'; to.filter = 'blur(0px)'; }
            textNode.animate([from, to], { duration: dur * 1000, easing: fallbackEase });
        }
    }

    function bindButton(button) {
        if (button.dataset.rmbReady === '1') return;
        button.dataset.rmbReady = '1';

        const layer = button.querySelector('.rmb-button__reveal');
        const content = button.querySelector('.rmb-button__content');
        const textNode = button.querySelector('.rmb-button__text');
        if (!layer) return;

        // Store desktop values, so switching back from a phone viewport does
        // not destroy the user's original desktop animation settings.
        const desktop = {};
        [
            'effect', 'iconEffect', 'textEffect', 'textEase', 'textDuration',
            'textExit', 'textDistance', 'textScale', 'textStagger',
            'textOpacity', 'ease', 'enter', 'leave'
        ].forEach(function (key) { desktop[key] = button.dataset[key]; });

        const overrideMap = {
            effect: 'mobileEffect',
            iconEffect: 'mobileIconEffect',
            textEffect: 'mobileTextEffect',
            textEase: 'mobileTextEase',
            textDuration: 'mobileTextDuration',
            textExit: 'mobileTextExit',
            textDistance: 'mobileTextDistance',
            textScale: 'mobileTextScale',
            textStagger: 'mobileTextStagger',
            textOpacity: 'mobileTextOpacity',
            ease: 'mobileEase',
            enter: 'mobileEnter',
            leave: 'mobileLeave'
        };

        const customVars = {
            '--rmb-enter-ms': ['mobileEnter', 'ms'],
            '--rmb-leave-ms': ['mobileLeave', 'ms'],
            '--rmb-icon-ms': ['mobileIconDuration', 'ms'],
            '--rmb-color-ms': ['mobileColorDuration', 'ms'],
            '--rmb-icon-hover-angle': ['mobileIconRotation', 'deg'],
            '--rmb-icon-slide': ['mobileIconSlide', 'px'],
            '--rmb-lift': ['mobileLift', 'px']
        };

        // Letter wave needs prebuilt spans, including when enabled only on mobile.
        const hasWave = desktop.textEffect === 'wave' || button.dataset.mobileTextEffect === 'wave';
        const characters = hasWave ? prepareLetterWave(button, textNode) : [];

        let animation = null;
        let raf = 0;
        let pointerInside = false;
        let lastPointerType = 'mouse';
        let mobileApplied = null;
        let touchActive = false;
        let touchTimer = 0;

        function mobileViewport(forceTouch) {
            const breakpoint = number(button.dataset.mobileBreakpoint, 767, 320, 1200);
            const phoneSize = window.innerWidth <= breakpoint;
            const allTouch = button.dataset.mobileScope === 'touch' && (
                forceTouch || window.matchMedia('(any-pointer: coarse)').matches
            );
            return phoneSize || allTouch;
        }

        function applyResponsive(forceTouch) {
            const enabled = mobileViewport(!!forceTouch);
            if (mobileApplied === enabled) return enabled;
            mobileApplied = enabled;
            const previousEffect = button.dataset.effect;

            Object.keys(overrideMap).forEach(function (key) {
                const override = button.dataset[overrideMap[key]];
                button.dataset[key] = enabled && override !== undefined &&
                    override !== '' && override !== 'inherit'
                    ? override : desktop[key];
            });
            if (previousEffect !== button.dataset.effect) {
                // A directional reveal uses an inline clip-path for WAAPI.
                // Remove it when switching to wipes/fades, or it would
                // incorrectly mask the new animation on viewport resize.
                if (animation) {
                    animation.cancel();
                    animation = null;
                }
                layer.style.removeProperty('clip-path');
            }
            Object.keys(customVars).forEach(function (cssName) {
                const setting = customVars[cssName];
                const value = button.dataset[setting[0]];
                if (enabled && value !== undefined && value !== '') {
                    button.style.setProperty(cssName, value + setting[1]);
                } else {
                    button.style.removeProperty(cssName);
                }
            });
            // Only override scale when a mobile value is actually set. Left empty
            // by "Same as desktop", the desktop --rmb-scale from the stylesheet
            // stays in force instead of being flattened to 1.
            const mobileScale = button.dataset.mobileScale;
            if (enabled && mobileScale !== undefined && mobileScale !== '') {
                button.style.setProperty('--rmb-scale',
                    String(number(mobileScale, 100, 90, 120) / 100));
            } else {
                button.style.removeProperty('--rmb-scale');
            }
            button.dataset.rmbMobileActive = enabled ? 'yes' : 'no';
            return enabled;
        }

        function animateDirectional(event, entering) {
            if (button.dataset.effect !== 'directional') return;
            const point = position(button, event);
            const farthestX = Math.max(point.x, point.width - point.x);
            const farthestY = Math.max(point.y, point.height - point.y);
            const radius = Math.ceil(Math.hypot(farthestX, farthestY)) + 3;
            const at = `at ${point.x}px ${point.y}px`;
            const target = entering ? `circle(${radius}px ${at})` : `circle(0px ${at})`;
            const current = window.getComputedStyle(layer).clipPath;
            if (animation) {
                layer.style.clipPath = current;
                animation.cancel();
                animation = null;
            }
            let from = current && current !== 'none' ? current : `circle(0px ${at})`;
            if (entering && /^circle\(\s*0(?:px)?\s+at\s/i.test(from)) {
                from = `circle(0px ${at})`;
            }
            if (reducedMotion.matches || typeof layer.animate !== 'function') {
                layer.style.clipPath = target;
                return;
            }
            const style = window.getComputedStyle(button);
            const varName = entering ? '--rmb-enter-ms' : '--rmb-leave-ms';
            const source = entering ? button.dataset.enter : button.dataset.leave;
            const duration = number(parseFloat(style.getPropertyValue(varName)),
                number(source, entering ? 700 : 620, 0, 2000), 0, 2000);
            const ease = EASINGS[button.dataset.ease] || EASINGS.premium;
            const running = layer.animate(
                [{ clipPath: from }, { clipPath: target }],
                { duration: duration, easing: ease, fill: 'forwards' }
            );
            animation = running;
            running.onfinish = function () {
                if (animation !== running) return;
                layer.style.clipPath = target;
                running.cancel();
                animation = null;
            };
        }

        function activate(event) {
            button.classList.add('is-hovered');
            animateDirectional(event, true);
            animateText(button, textNode, characters, true);
        }
        function deactivate(event) {
            button.classList.remove('is-hovered');
            animateDirectional(event, false);
            animateText(button, textNode, characters, false);
        }
        function resetMagnet() {
            if (raf) window.cancelAnimationFrame(raf);
            raf = 0;
            button.style.setProperty('--rmb-mag-x', '0px');
            button.style.setProperty('--rmb-mag-y', '0px');
        }
        function clearTouchTimer() {
            if (touchTimer) window.clearTimeout(touchTimer);
            touchTimer = 0;
        }
        function closeTouch(event) {
            if (!touchActive) return;
            touchActive = false;
            clearTouchTimer();
            if (!button.matches(':focus-visible')) deactivate(event);
        }

        // Returns the button to its resting state, colours included.
        button._rmbReset = function () {
            clearTouchTimer();
            touchActive = false;
            pointerInside = false;
            resetMagnet();
            deactivate(null);
        };

        button._rmbMobileSync = function () {
            if (!button.isConnected) {
                activeButtons.delete(button);
                return;
            }
            // Avoid changing the reveal effect mid-animation, especially
            // when rotating a device while a finger is holding the button.
            if (!touchActive) applyResponsive(false);
        };
        activeButtons.add(button);
        applyResponsive(false);

        button.addEventListener('pointerenter', function (event) {
            lastPointerType = event.pointerType;
            if (event.pointerType === 'touch') return;
            applyResponsive(false);
            pointerInside = true;
            activate(event);
        });
        button.addEventListener('pointermove', function (event) {
            if (!pointerInside || event.pointerType === 'touch' ||
                button.dataset.magnetic !== 'yes' || !content) return;
            if (raf) window.cancelAnimationFrame(raf);
            raf = window.requestAnimationFrame(function () {
                const p = position(button, event);
                const strength = number(button.dataset.magneticStrength, 5, 0, 20);
                const x = p.width ? (p.x / p.width - 0.5) * 2 * strength : 0;
                const y = p.height ? (p.y / p.height - 0.5) * 2 * strength : 0;
                button.style.setProperty('--rmb-mag-x', x.toFixed(2) + 'px');
                button.style.setProperty('--rmb-mag-y', y.toFixed(2) + 'px');
            });
        }, { passive: true });
        button.addEventListener('pointerleave', function (event) {
            if (event.pointerType === 'touch') return;
            pointerInside = false;
            resetMagnet();
            if (!button.matches(':focus-visible')) deactivate(event);
        });

        // Mobile tap/press animation: no preventDefault(), so a link still
        // navigates on the very first tap. It is intentionally not a two-tap UI.
        button.addEventListener('pointerdown', function (event) {
            if (event.pointerType !== 'touch') return;
            lastPointerType = 'touch';
            if (!applyResponsive(true) || button.dataset.mobileBehavior === 'off') return;
            clearTouchTimer();
            touchActive = true;
            activate(event);
        }, { passive: true });
        button.addEventListener('pointerup', function (event) {
            if (event.pointerType !== 'touch' || !touchActive) return;
            clearTouchTimer();
            if (button.dataset.mobileBehavior === 'press') {
                closeTouch(event);
                return;
            }
            // On mobile linked buttons the page may navigate before this timer
            // fires, as expected. We never add a navigation delay.
            const hold = number(button.dataset.mobileHold, 500, 0, 2500);
            touchTimer = window.setTimeout(function () { closeTouch(event); }, hold);
        }, { passive: true });
        button.addEventListener('pointercancel', function (event) {
            pointerInside = false;
            resetMagnet();
            closeTouch(event);
            if (event.pointerType !== 'touch' && !button.matches(':focus-visible')) {
                deactivate(event);
            }
        });

        button.addEventListener('focus', function () {
            applyResponsive(false);
            if (button.matches(':focus-visible')) activate(null);
        });
        button.addEventListener('blur', function () {
            if (!pointerInside && !touchActive) deactivate(null);
        });

        // Desktop and mobile ripple can be switched independently.
        button.addEventListener('click', function (event) {
            if (reducedMotion.matches) return;
            const touch = lastPointerType === 'touch';
            const onMobile = button.dataset.rmbMobileActive === 'yes';
            let rippleOn = button.dataset.ripple === 'yes';
            if (touch) {
                if (!onMobile || button.dataset.mobileBehavior === 'off') return;
                const mode = button.dataset.mobileRipple || 'inherit';
                if (mode === 'yes') rippleOn = true;
                else if (mode === 'no') rippleOn = false;
                // "Inherit" keeps the desktop ripple setting as it is. It also
                // required data-touch, which is emitted from a setting that is no
                // longer registered and so was always "no" — meaning an inherited
                // ripple could never fire on a touch device.
            }
            if (!rippleOn) return;
            const p = position(button, event);
            const node = document.createElement('span');
            node.className = 'rmb-button__ripple';
            node.setAttribute('aria-hidden', 'true');
            node.style.left = p.x + 'px';
            node.style.top = p.y + 'px';
            node.style.setProperty('--rmb-ripple-scale',
                Math.ceil(Math.hypot(p.width, p.height) / 8).toString());
            button.appendChild(node);
            node.addEventListener('animationend', function () { node.remove(); }, { once: true });
        });
    }

    // Returning to a page with Back on a phone can restore it from the back/forward
    // cache with the touch state still applied, and no pointer event follows to
    // clear it — leaving the label stuck in its hover colour. Reset on restore.
    window.addEventListener('pageshow', function (event) {
        if (!event.persisted) return;
        activeButtons.forEach(function (button) {
            if (typeof button._rmbReset === 'function') button._rmbReset();
        });
    });

    window.addEventListener('resize', function () {
        if (resizeFrame) window.cancelAnimationFrame(resizeFrame);
        resizeFrame = window.requestAnimationFrame(function () {
            resizeFrame = 0;
            activeButtons.forEach(function (button) {
                if (typeof button._rmbMobileSync === 'function') button._rmbMobileSync();
            });
        });
    }, { passive: true });

    function init(root) {
        const scope = root && root.nodeType === 1 ? root : document;
        if (scope.matches && scope.matches(SELECTOR)) bindButton(scope);
        scope.querySelectorAll(SELECTOR).forEach(bindButton);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { init(document); });
    } else {
        init(document);
    }

    // Elementor updates individual widget DOM trees during live editing.
    function registerEditorHook() {
        if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;
        window.elementorFrontend.hooks.addAction('frontend/element_ready/raddito-motion-button.default', function (scope) {
            init(scope && scope[0] ? scope[0] : scope);
        });
    }
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        registerEditorHook();
    } else if (window.jQuery) {
        window.jQuery(window).on('elementor/frontend/init', registerEditorHook);
    }
})();
