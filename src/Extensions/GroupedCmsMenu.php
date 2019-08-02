<?php

namespace Symbiote\GroupedCmsMenu\Admin;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\GroupedList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\ORM\SS_List;

/**
 * Decorates {@link LeftAndMain} to provide a grouped/nested CMS menu.
 *
 * @package grouped-cms-menu
 */
class GroupedCmsMenu extends LeftAndMainExtension
{
    /**
     * @var array
     */
    private static $menu_groups = [];
    /**
     * When you have larger menus, and/or multiple modules combining to the same menu,
     * this may require something more consistent.
     *
     * @var boolean
     */
    private static $menu_groups_alphabetical_sorting = false;

    /**
     * Require the CSS which we need for the menu.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        Requirements::css('symbiote/silverstripe-grouped-cms-menu:client/dist/css/GroupedCmsMenu.css');
    }

    /**
     * @return ArrayList
     */
    public function GroupedMainMenu()
    {
        $items = $this->getOwner()->MainMenu();
        $result = ArrayList::create();
        $config = $this->getOwner()->config();
        $groupSettings = $config->get('menu_groups');
        $itemsToGroup = [];
        $groupSort = 0;
        $itemSort = 0;
        foreach ($groupSettings as $groupName => $menuItems) {
            if (!count($menuItems['children'])) {
                continue;
            }
            foreach ($menuItems['children'] as $key => $menuItem) {
                $itemsToGroup[$menuItem] = [
                    'Group' => $groupName,
                    'Priority' => (array_key_exists('priority', $groupSettings[$groupName])) ? $groupSettings[$groupName]['priority'] : $groupSort,
                    'SortOrder' => $itemSort
                ];
                $itemSort++;
            }
            $groupSort--;
        }
        foreach ($items as $item) {
            $code = $item->Code;
            if (array_key_exists($code, $itemsToGroup)) {
                $item->Group = $itemsToGroup[$code]['Group'];
                $item->Priority = $itemsToGroup[$code]['Priority'];
                $item->SortOrder = $itemsToGroup[$code]['SortOrder'];
            } else {
                $item->Group = $code;
                $item->Priority = is_numeric($item->MenuItem->priority) ? $item->MenuItem->priority : -1;
                $item->SortOrder = 0;
            }
        }
        $groupedList = GroupedList::create($items->sort(['Priority' => 'DESC']))->groupBy('Group');
        foreach ($groupedList as $group => $children) {
            if (count($children)) {
                $active = false;
                foreach ($children as $child) {
                    if ($child->LinkingMode == 'current') {
                        $active = true;
                    }
                }
                $code = str_replace(' ', '_', $group);
                $result->push(ArrayData::create([
                    'Title' => $this->getTitle($group, $code),
                    'IconClass' => $this->getIcon($group, $code),
                    'Code' => DBField::create_field(DBText::class, $code),
                    'Link' => $children->first()->Link,
                    'LinkingMode' => $active ? 'current' : 'link',
                    'Children' => $this->FilterChildren($children),
                ]));
            } else {
                $result->push($children->first());
            }
        }
        return $result;
    }

    /**
     * Return the {@link LeftAndMain} subclass' `$menu_title` for each Level 1
     * menu item, or use the partial name of each child item from `$menu_groups`
     * config.
     *
     * @param  string $group
     * @param  string $code
     * @return string
     */
    public function getTitle($group, $code)
    {
        $class = str_replace('-', '\\', $code);
        if (class_exists($class)) {
            return $class::create()->config()->get('menu_title');
        }
        return _t('GroupedCmsMenuLabel.' . $code, $group);
    }

    /**
     * Return the {@link LeftAndMain} subclass' `$menu_icon_class` or use the
     * name of the heading as per the `$menu_groups` setting in config.
     *
     * @param  string $group
     * @param  string $code
     * @return string
     */
    public function getIcon($group, $code)
    {
        $class = str_replace('-', '\\', $code);
        $groupSettings = $this->getOwner()->config()->get('menu_groups');
        if (class_exists($class)) {
            return $class::create()->config()->get('menu_icon_class');
        }
        return 'font-icon-' . (!empty($groupSettings[$group]['icon']) ? $groupSettings[$group]['icon'] : '');
    }

    /**
     * Ensure what we pass to $Children in the include template is accurate. We only
     * want to indicate to it that children should be shown groups declared in config.
     *
     * @param  SS_List $children
     * @return ArrayList
     */
    public function filterChildren(SS_List $children)
    {
        // Only deal with children if we've explicitly instructed our classes as such
        $config = $this->getOwner()->config();
        $groupSettings = $config->get('menu_groups');
        $filtered = ArrayList::create();
        foreach ($children as $child) {
            foreach ($groupSettings as $group => $candidates) {
                $candidates = str_replace('-', '\\', $candidates['children']);
                $class = $child->MenuItem->controller;
                if (in_array($class, $candidates)) {
                    foreach ($candidates as $candidate) {
                        $menuItem = $candidate::create();
                        $menuItem->setField('ChildTitle', $this->getTitle($group, $candidate));
                        $menuItem->setField('Code', str_replace('\\', '-', $candidate));
                        $menuItem->setField('IconClass', $this->getIcon($group, $candidate));
                        $menuItem->setField('LinkingMode', $child->LinkingMode);
                        $filtered->push($menuItem);
                    }
                }
            }
        }
        return $config->get('menu_groups_alphabetical_sorting') ?
            $filtered->sort('ChildTitle') :
            $filtered->sort('SortOrder');
    }
}
