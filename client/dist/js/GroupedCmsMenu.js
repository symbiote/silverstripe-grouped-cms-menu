/**
 * Grouped CMS menu: accordion behaviour (click instead of hover).
 *
 * - A click on a group entry opens its sub items and closes all other groups.
 *   Clicking an opened group closes it again.
 * - The group that contains the active menu item is rendered with the class
 *   "opened" by the template (LeftAndMain_MenuList.ss), so it is open on load.
 *   The CMS menu stays in the DOM during Ajax navigation, so the state set here
 *   persists (no extra storage needed).
 * - When the menu is collapsed (#cms-menu.collapsed, icons only) the admin CSS
 *   hides sub items. A click on a group then shows its sub items as a flyout
 *   next to the icon (class "flyout"). It closes on a click outside, on Escape,
 *   on a click on a sub item, on scroll or resize and when the menu is expanded.
 *
 * No build step: this file is edited directly in client/dist.
 */
(function ($) {
    'use strict';

    var GROUP_SELECTOR = '.cms-menu__list li.children';

    function closeFlyouts() {
        var menu = document.getElementById('cms-menu');

        // Runs on every scroll, focus and mousedown of the page, so it returns early when nothing is open
        if (!menu || !menu.classList.contains('has-flyout')) {
            return;
        }

        menu.classList.remove('has-flyout');
        $(GROUP_SELECTOR + '.flyout').removeClass('flyout').each(function () {
            // Back to the accordion state, which shows again once the menu is expanded
            $(this).children('a').attr('aria-expanded', $(this).hasClass('opened') ? 'true' : 'false');
        });
    }

    function toggleFlyout($group) {
        var open = !$group.hasClass('flyout');

        closeFlyouts();

        if (open) {
            // The collapsed list scrolls (overflow), so the flyout is fixed to the viewport
            // and moved up where it would leave the bottom of the window
            var rect = $group.children('a')[0].getBoundingClientRect();
            var $flyout = $group.children('ul.group');

            // Class first: the flyout has a height only once it is displayed
            $group.addClass('flyout');
            $flyout.css({
                top: Math.max(0, Math.min(rect.top, window.innerHeight - $flyout.outerHeight())),
                left: rect.right
            });
            $group.children('a').attr('aria-expanded', 'true');
            // Lifts the menu above the content area, see CSS
            $('#cms-menu').addClass('has-flyout');
        }
    }

    function closeOutside(e) {
        if (!$(e.target).closest(GROUP_SELECTOR + '.flyout').length) {
            closeFlyouts();
        }
    }

    $(document)
        .on('mousedown', closeOutside)
        // Tabbing out of the flyout closes it like a click outside
        .on('focusin', closeOutside)
        .on('keydown', function (e) {
            var $open = $(GROUP_SELECTOR + '.flyout');

            if (e.key === 'Escape' && $open.length) {
                // Keyboard users continue on the group they opened
                $open.children('a').trigger('focus');
                closeFlyouts();
            }
        })
        // The core handler still navigates, the flyout only has to go away
        .on('click', GROUP_SELECTOR + '.flyout > ul.group a', closeFlyouts)
        .on('expand', '#cms-menu', closeFlyouts);

    // A fixed flyout would stay behind while the page or the menu scrolls; scrolling inside it is fine
    document.addEventListener('scroll', closeOutside, true);

    // Only a change of the width moves the menu (mobile browsers also resize when the address bar slides)
    var lastWidth = window.innerWidth;
    $(window).on('resize', function () {
        if (window.innerWidth !== lastWidth) {
            lastWidth = window.innerWidth;
            closeFlyouts();
        }
    });

    function setGroupOpen($group, open) {
        $group.toggleClass('opened', open);
        $group.children('a').attr('aria-expanded', open ? 'true' : 'false');
    }

    function hasModifierKey(e) {
        return e.ctrlKey || e.metaKey || e.shiftKey || e.altKey;
    }

    // Flyout in the collapsed menu, accordion in the expanded one
    function toggleGroup($group) {
        if ($('#cms-menu').hasClass('collapsed')) {
            toggleFlyout($group);
            return;
        }

        var open = !$group.hasClass('opened');

        if (open) {
            $group.closest('.cms-menu__list').find('li.children.opened').not($group).each(function () {
                setGroupOpen($(this), false);
            });
        }

        setGroupOpen($group, open);
    }

    $.entwine('ss', function ($) {
        // Reflect the server-rendered state (current group is "opened") for assistive tech.
        $(GROUP_SELECTOR).entwine({
            onmatch: function () {
                this.children('a').attr('aria-expanded', this.hasClass('opened') ? 'true' : 'false');
                this._super();
            }
        });

        // Higher specificity than the core rule ".cms-menu__list li a", so this runs first;
        // this._super(e) hands over to the core handler where the old behaviour is wanted.
        $('.cms-menu__list li.children > a').entwine({
            onclick: function (e) {
                if (e.button !== 0 || hasModifierKey(e)) {
                    return this._super(e);
                }

                e.preventDefault();
                toggleGroup(this.closest('li.children'));

                return false;
            },
            // A link only reacts to Enter by itself; a group also opens with the space bar.
            // Called directly: a triggered click has no mouse button and would count as modified above.
            // Shortcuts with the space bar stay untouched; a held key toggles only once.
            onkeydown: function (e) {
                if (e.key === ' ' && !hasModifierKey(e)) {
                    e.preventDefault();

                    if (!e.originalEvent || !e.originalEvent.repeat) {
                        toggleGroup(this.closest('li.children'));
                    }

                    return false;
                }
                return this._super(e);
            }
        });
    });
}(jQuery));
