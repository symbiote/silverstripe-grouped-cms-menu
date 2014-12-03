# SilverStripe Grouped CMS Menu Module

This module allows you to group CMS menu items into nested lists which expand
when hovered over. This is useful when there are so many CMS menu items that
screen space becomes an issue.

Previous versions are available through the appropriate branch.

## Basic Usage
In order to group CMS menu items together, define your menu groups in your config.yml file. 
example below, CMSMain (Pages) and AssetAdmin (Files &amp; Images) are grouped
together under a "Content" heading:

```
LeftAndMain:
  menu_groups:
    CMSPagesController: Content
    AssetAdmin: Content
```

## Maintainer Contacts
* Marcus Nyeholt (<marcus@silverstripe.com.au>)

## Requirements
* SilverStripe 3.0+

## Project Links
* [GitHub Project Page](https://github.com/ajshort/silverstripe-groupedcmsmenu)
* [Issue Tracker](https://github.com/ajshort/silverstripe-groupedcmsmenu/issues)
