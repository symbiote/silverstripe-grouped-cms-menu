<?php
/**
 * Decorates left and main to provide a grouped/nested CMS menu.
 *
 * @package silverstripe-groupedcmsmenu
 */
class GroupedCmsMenu extends Extension {

	private static $menu_groups = array();

	public function init() {
		//Requirements::javascript('groupedcmsmenu/javascript/GroupedCmsMenu.js');
		Requirements::css('groupedcmsmenu/css/GroupedCmsMenu.css');
	}

	/**
	 * @return DataObjectSet
	 */
	public function GroupedMainMenu() {
		$items  = $this->owner->MainMenu();
		$result = ArrayList::create();
		$groups = Config::inst()->get('LeftAndMain', 'menu_groups');

		foreach ($items as $item) {
			$code = $item->Code->XML();
			if (array_key_exists($code, $groups)) {
				$item->Group = $groups[$code];
			} else {
				$item->Group = $code;
			}
		}

		foreach (GroupedList::create($items)->groupBy('Group') as $group => $children) {

			if (count($children) > 1) {
				$active = false;

				foreach ($children as $child) {
					if ($child->LinkingMode == 'current') $active = true;
				}

				$result->push(ArrayData::create(array(
					'Title'       	=> $group,
					'Code'    		=> DBField::create_field('Text', str_replace(' ', '_', $group)),
					'Link'        	=> $children->First()->Link,
					'LinkingMode' 	=> $active ? 'current' : '',
					'Children'    	=> $children
				)));
			} else {
				$result->push($children->First());
			}
		}
		
		return $result;
	}

}