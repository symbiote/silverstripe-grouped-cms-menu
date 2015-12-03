<?php
/**
 * Decorates left and main to provide a grouped/nested CMS menu.
 *
 * @package grouped-cms-menu
 */
class GroupedCmsMenu extends Extension {

	/**
	 * @var array
	 */
	private static $menu_groups = array();

	/**
	 * Require in CSS & JS which we need for the menu
	 */
	public function init() {
		Requirements::css('grouped-cms-menu/css/GroupedCmsMenu.css');
		Requirements::javascript('grouped-cms-menu/javascript/GroupedCmsMenu.js');
	}

	/**
	 * @return ArrayList
	 */
	public function GroupedMainMenu() {
		$items  = $this->owner->MainMenu();
		$result = ArrayList::create();
		$groupSettings = Config::inst()->get('LeftAndMain', 'menu_groups');
		$itemsToGroup = array();
		$groupSort = 0;
		$itemSort = 0;

		foreach ($groupSettings as $groupName => $menuItems) {
			if (count($menuItems)) {
				foreach ($menuItems as $key => $menuItem ) {
					if (is_numeric($key))
					$itemsToGroup[$menuItem] = array(
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
			$code = $item->Code->XML();
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
				$code = str_replace(' ', '_', $group);
				$result->push(ArrayData::create(array(
					'Title' => _t('GroupedCmsMenuLabel.'.$code, $group),
					'Code' => DBField::create_field('Text', $code),
					'Link' => $children->First()->Link,
					'Icon' => $icon,
					'LinkingMode' => $active ? 'current' : '',
					'Children' => $children->sort('SortOrder')
				)));
			} else {
				$result->push($children->First());
			}
		}

		return $result;
	}

}
