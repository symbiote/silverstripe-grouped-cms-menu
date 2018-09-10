<?php
/**
 * Decorates left and main to provide a grouped/nested CMS menu.
 *
 * @package grouped-cms-menu
 */

namespace Symbiote\GroupedCMSMenu\Extensions;

use SilverStripe\Admin\CMSMenu;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\ORM\GroupedList;
use SilverStripe\View\ArrayData;

class GroupedCmsMenu extends Extension {

	/**
	 * @var array
	 */
	private static $menu_groups = array();

	/**
	 *	When you have larger menus, and/or multiple modules combining to the same menu, this may require something more consistent.
	 */

	private static $menu_groups_alphabetical_sorting = false;

	/**
	 * Require in CSS which we need for the menu
	 */
	public function init() {
		// Requirements::css('grouped-cms-menu/css/GroupedCmsMenu.css');
	}

	/**
	 * @return ArrayList
	 */
	public function GroupedMainMenu() {
		$items  = $this->owner->MainMenu();
		$result = ArrayList::create();
		$config = Config::inst();
		$groupSettings = $config->get(LeftAndMain::class, 'menu_groups');
		$itemsToGroup = array();
		$groupSort = 0;
		$itemSort = 0;


		foreach ($groupSettings as $groupName => $menuItems) {
			if (count($menuItems['items'])) {
				foreach ($menuItems['items'] as $key => $menuItem ) {
					$itemsToGroup[CMSMenu::get_menu_code($menuItem)] = array(
						'Group' => $groupName,
						'Priority' => (array_key_exists('priority', $groupSettings[$groupName])) ? $groupSettings[$groupName]['priority'] : $groupSort,
						'SortOrder' => $itemSort
					);
					$itemSort++;
				}
				$groupSort--;
			}
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

		foreach (GroupedList::create($items->sort(array('Priority'=>'DESC')))->groupBy('Group') as $group => $children) {
			if (count($children) > 1) {
				$active = false;
				foreach ($children as $child) {
					if ($child->LinkingMode == 'current') $active = true;
				}
				$icon = array_key_exists('icon', $groupSettings[$group]) ? $groupSettings[$group]['icon'] : false;
                $iconClass = array_key_exists('icon_class', $groupSettings[$group]) ? $groupSettings[$group]['icon_class'] : false;
				$code = str_replace(' ', '_', $group);
				$result->push(ArrayData::create(array(
					'Title' => _t('GroupedCmsMenuLabel.'.$code, $group),
					'Code' => DBText::create_field('Text', $code),
					'Link' => $children->First()->Link,
					'Icon' => $icon,
					'IconClass' => $iconClass,
					'LinkingMode' => $active ? 'current' : '',
					'Children' => $config->get('LeftAndMain', 'menu_groups_alphabetical_sorting') ? $children->sort('Title') : $children->sort('SortOrder')
				)));
			} else {
				$result->push($children->First());
			}
		}

		return $result;
	}

}
