# SilverStripe Grouped CMS Menu Module

This module allows you to group CMS menu items into nested lists which expand
when hovered over. This is useful when there are so many CMS menu items that
screen space becomes an issue.

## Basic Usage
In order to group CMS menu items together, the `GroupedCmsMenu::group()` function
is used. The grouping code should normally go in `mysite/_config.php`. In the
example below, CMSMain (Pages) and AssetAdmin (Files &amp; Images) are grouped
together under a "Content" heading:

	GroupedCmsMenu::group('Content', array('CMSMain', 'AssetAdmin'));

## Maintainer Contacts
* Marcus Nyeholt (<marcus@silverstripe.com.au>)

## Requirements
* SilverStripe 3.1+

## Project Links
* [GitHub Project Page](https://github.com/ajshort/silverstripe-groupedcmsmenu)
* [Issue Tracker](https://github.com/ajshort/silverstripe-groupedcmsmenu/issues)
