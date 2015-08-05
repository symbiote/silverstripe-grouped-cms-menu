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
	 * Require in CSS which we need for the menu
	 */
	public function init() {
		Requirements::css('grouped-cms-menu/css/GroupedCmsMenu.css');
	}

	/**
	 * @return ArrayList
	 */
	public function GroupedMainMenu() {
		$items  = $this->owner->MainMenu();
		$result = ArrayList::create();
		$groupSettings = Config::inst()->get('LeftAndMain', 'menu_groups');
		$groups = array();

		$position = 0;
		foreach ($groupSettings as $key => $menuItems) {
			$groups[$key] = array(
				'Code' => $key,
				'Position' => $position
			);
			$position++;
			if (count($menuItems)) {
				foreach ($menuItems as $menuItem ) {
					$groups[$menuItem] = array(
						'Code' => $key,
						'Position' => $position
					);
					$position++;
				}
			}
		}

		foreach ($items as $item) {
			$code = $item->Code->XML();
			if (array_key_exists($code, $groups)) {
				$item->Group = $groups[$code]['Code'];
				$item->Position = $groups[$code]['Position'];
			} else {
				$item->Group = $code;
				$item->Position = 9999;
			}
		}

		foreach (GroupedList::create($items->sort('Position'))->groupBy('Group') as $group => $children) {
			if (count($children) > 1) {
				$active = false;

				foreach ($children as $child) {
					if ($child->LinkingMode == 'current') $active = true;
				}

				$result->push(ArrayData::create(array(
					'Title' => $group,
					'Code' => DBField::create_field('Text', str_replace(' ', '_', $group)),
					'Link' => $children->First()->Link,
					'LinkingMode' => $active ? 'current' : '',
					'Position' => $position,
					'Children' => $children->sort('Position')
				)));
			} else {
				$result->push($children->First());
			}
		}

		return $result;
	}

}
