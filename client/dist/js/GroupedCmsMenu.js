/**
 * Grouped CMS menu: accordion behaviour (click instead of hover).
 *
 * - A click on a group entry opens its sub items and closes all other groups.
 *   Clicking an opened group closes it again.
 * - The group that contains the active menu item is rendered with the class
 *   "opened" by the template (LeftAndMain_MenuList.ss), so it is open on load.
 *   The CMS menu stays in the DOM during Ajax navigation, so the state set here
 *   persists (no extra storage needed).
 * - When the menu is collapsed (#cms-menu.collapsed, icons only) sub items are
 *   hidden by the admin CSS. A click then behaves as before: it falls through
 *   to the core handler and navigates to the first item of the group.
 *
 * No build step: this file is edited directly in client/dist.
 */
(function ($) {
    'use strict';

    var GROUP_SELECTOR = '.cms-menu__list li.children';

    function setGroupOpen($group, open) {
        $group.toggleClass('opened', open);
        $group.children('a').attr('aria-expanded', open ? 'true' : 'false');
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
                var modified = e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey;

                if (modified || $('#cms-menu').hasClass('collapsed')) {
                    return this._super(e);
                }

                e.preventDefault();

                var $group = this.closest('li.children');
                var open = !$group.hasClass('opened');

                if (open) {
                    $group.closest('.cms-menu__list').find('li.children.opened').not($group).each(function () {
                        setGroupOpen($(this), false);
                    });
                }

                setGroupOpen($group, open);

                return false;
            },
            // A link only reacts to Enter by itself; a group also opens with the space bar
            onkeydown: function (e) {
                if (e.which === 32) {
                    e.preventDefault();
                    this.trigger('click');
                    return false;
                }
                return this._super(e);
            }
        });
    });
}(jQuery));
