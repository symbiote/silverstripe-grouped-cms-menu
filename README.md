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

## Sort order

The items in each grouped menu will follow the order you set in your YML. The groups 
themselves will be inserted in the menu with a priority of 0, with other menu items 
appearing above or below depending on their existing priority.
You can change the priority of a menu group like this:

```yml
LeftAndMain:
  menu_groups:
    Other:
      priority: -500
      - ReportAdmin
      - SecurityAdmin
```

Or you can "group" items by themselves to make any menu item follow the order you set in your configuration:

```yml
LeftAndMain:
  menu_groups:
    CMSPagesController:
      - CMSPagesController
    Other:
      - ReportAdmin
      - AssetAdmin
```

When you have larger menus, and/or multiple modules combining to the same menu, this may require something more consistent. In which case, you may sort your grouped menus alphabetically.

```yml
LeftAndMain:
  menu_groups:
    CMSPagesController:
      - CMSPagesController
    Other:
      - ReportAdmin
      - AssetAdmin
  menu_groups_alphabetical_sorting: true
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

* SilverStripe 3.2+ (See other branches for compatibility with older versions)

## Project Links

* [GitHub Project Page](https://github.com/ajshort/silverstripe-grouped-cms-menu)
* [Issue Tracker](https://github.com/ajshort/silverstripe-grouped-cms-menu/issues)
