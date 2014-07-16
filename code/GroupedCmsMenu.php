<?php

/**
 * Decorates left and main to provide a grouped/nested CMS menu.
 *
 * @package silverstripe-groupedcmsmenu
 */
class GroupedCmsMenu extends DataExtension {

	protected static $groups = array();

	/**
	 * Group multiple CMS menu items together under one title.
	 *
	 * @param  string $title The group title to display in the main menu
	 * @param  array $classes The set of menu codes/classes to group.
	 */
	public static function group($title, array $codes) {
		foreach ($codes as $code) self::$groups[$code] = $title;
	}

	public function init() {
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::css('silverstripe-groupedcmsmenu/css/GroupedCmsMenu.css');
	}

	/**
	 * @return ArrayList
	 */
	public function GroupedMainMenu() {
		$items = new GroupedList($this->owner->MainMenu());
		$result = new ArrayList();

		if (!empty(self::$groups)) {
			foreach ($items as $item) {
				if (array_key_exists($item->Code->RAW(), self::$groups)) {
					$item->Group = self::$groups[$item->Code->RAW()];
				} else {
					$item->Group = $item->Code->RAW();
				}
			}
		}
		foreach ($items->groupBy('Group') as $group => $children) {
			if (count($children) > 1) {
				$active = false;

				foreach ($children as $child) {
					if ($child->LinkingMode == 'current') $active = true;
				}

				$result->push(new ArrayData(array(
					'Group' => $group,
					'Link' => $children->First()->Link,
					'LinkingMode' => $active ? 'current' : '',
					'Children' => $children
				)));
			} else {
				$result->push($children->First());
			}
		}

		return $result;
	}

}