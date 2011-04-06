<?php
/**
 * Decorates left and main to provide a grouped/nested CMS menu.
 *
 * @package silverstripe-groupedcmsmenu
 */
class GroupedCmsMenu extends LeftAndMainDecorator {

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
		Requirements::javascript('groupedcmsmenu/javascript/GroupedCmsMenu.js');
		Requirements::css('groupedcmsmenu/css/GroupedCmsMenu.css');
	}

	/**
	 * @return DataObjectSet
	 */
	public function GroupedMainMenu() {
		$items  = $this->owner->MainMenu();
		$result = new DataObjectSet();

		foreach ($items as $item) {
			if (array_key_exists($item->Code, self::$groups)) {
				$item->Group = self::$groups[$item->Code];
			} else {
				$item->Group = $item->Code;
			}
		}

		foreach ($items->groupBy('Group') as $group => $children) {
			if (count($children) > 1) {
				$active = false;

				foreach ($children as $child) {
					if ($child->LinkingMode == 'current') $active = true;
				}

				$result->push(new ArrayData(array(
					'Group'       => $group,
					'Link'        => $children->First()->Link,
					'LinkingMode' => $active ? 'current' : '',
					'Children'    => $children
				)));
			} else {
				$result->push($children->First());
			}
		}

		return $result;
	}

}