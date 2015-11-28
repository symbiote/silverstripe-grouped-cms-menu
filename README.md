# SilverStripe Grouped CMS Menu

This module allows you to group CMS menu items into nested lists which expand when hovered over. This is useful when 
there are so many CMS menu items that screen space becomes an issue.

Previous versions are available through the appropriate branch.

## Basic Usage

In order to group CMS menu items together, define your menu groups in a `config.yml` file.

In the example below, CMSMain (Pages) and AssetAdmin (Files &amp; Images) are grouped
together under a "Content" heading.

```yml
LeftAndMain:
  menu_groups:
    Content:
      - CMSPagesController
      - AssetAdmin
```
Each grouped menu will be ordered by the way you configure your YML. If you do not add an item to a grouping, they will 
appear at the bottom of the menu. You may also choose to "group" items by themselves to make sure that they are ordered 
correctly in your menu.

```yml
LeftAndMain:
  menu_groups:
    CMSPagesController: []
    CMSSettingsController: []
    Other:
      - ReportAdmin
      - AssetAdmin
```

## Group icons

You can add a CSS class to groups for the purpose of adding an icon. The class name will be prefixed with 'icon-'.
In the example below the same icon used for the Pages menu item will be used for the Content group.

```yml
LeftAndMain:
  menu_groups:
    Content:
      icon: 'cmspagescontroller'
      - CMSPagesController
      - AssetAdmin
```

## Translating group labels

A group label may be translated by providing a translation key as below (using
the 'Other' group from above as an example)

```
langcode:
  GroupedCmsMenuLabel:
    Other: 'translated text'
```

If the group label has spaces, these will be converted to underscores for the 
key

```
langcode:
  GroupedCmsMenuLabel:
    Other_Label: 'translated text'
```

## Maintainer Contacts

* Marcus Nyeholt (<marcus@silverstripe.com.au>)

## Requirements

* SilverStripe 3.0+

## Project Links

* [GitHub Project Page](https://github.com/ajshort/silverstripe-grouped-cms-menu)
* [Issue Tracker](https://github.com/ajshort/silverstripe-grouped-cms-menu/issues)
