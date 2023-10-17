<p align="center">
  <a href="https://github.com/itplusx/flexible_pages" rel="noopener noreferrer">
    <img src="https://raw.githubusercontent.com/itplusx/flexible_pages/master/Resources/Public/Icons/Logo.png" alt="TYPO3 EXT:flexible_pages">
  </a>
</p>


# TYPO3 Extension `flexible_pages`

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Setup custom page types in TYPO3 with ease.**

This extensions reduces the [registration of custom page types in TYPO3](https://docs.typo3.org/typo3cms/CoreApiReference/latest/ApiOverview/PageTypes/Index.html) to a simple setup using *YAML* files.

* Features
* Roadmap
* Installation
* Registering custom pages types
* Generic frontend list plugin (*tx_flexiblepages_pagelist*)


## Features

✓ Register custom pages types within seconds

✓ Use custom or already registered icons

✓ Plugin to render menus / lists of pages by page types


## Roadmap
- Create an generic icon with the staring letter of the label, if no icons have been registered
- Backend module to configure custom pages types. It's basically a configuration front end for the YAML file
- Enable setting custom position in pages select box
- Enable sorting by date
- Cli command to create page types
- BE Module
  - List
    - List pages with page type filter
    - Bulk edit
  - Configuration
    - Configure page type in BE
- Provide a nice way to extend custom page types with additional fields


## 1. Installation

### 1.1 Installation with [`composer`](https://getcomposer.org/)
`composer require itplusx/flexible-pages`


### 1.2 Installation with the [TYPO3 Extension Manager](https://docs.typo3.org/typo3cms/GettingStartedTutorial/ExtensionManager/Index.html#installing-a-new-extension)
Use the Extension Key `flexible_pages` in the [TYPO3 Extension Manager](https://docs.typo3.org/typo3cms/GettingStartedTutorial/ExtensionManager/Index.html#installing-a-new-extension).


## 2. Registering custom pages types
There are two ways to register custom pages types.
1. [Using *YAML* configuration files (recommended)](#21-using-yaml-configuration-files-recommended)
2. [Using ext_localconf.php](#22-using-ext_localconfphp)


### 2.1 Using *YAML* configuration files (recommended)
The easiest way to add new page types is via YAML. Three different possibilities exist to add new pageTypes with YAML files:
1. [Using the global config directory path (like with the site configuration of the TYPO3 core)](#211-using-the-global-config-directory)
2. [Using the extension Configuration directory path of your custom extension](#212-using-the-extension-configuration-directory)
3. [Adding a custom path](#213-adding-a-custom-path)


#### 2.1.1 Using the global config directory
As with the site configuration of the TYPO3 core it is also possible to add YAML files inside the global config directory.

Example:
```
config/
└── flexible_pages/
    └── myNewPageType.yaml
    └── myOtherPageType.yaml
    └── Articles
        └── blogArticle.yaml
        └── newsArticle.yaml
```


#### 2.1.2 Using the extension Configuration directory
It is also possible for every third-party extension to use `flexible_pages` as base for adding custom pageTypes. To enable this just add a YAML configuration file to your own extension inside `Configuration/Yaml/flexible_pages`.

Example:
```
your_extensionkey/
└── Configuration/
    └── Yaml
        └── flexible_pages/
            └── myNewPageType.yaml
            └── myOtherPageType.yaml
            └── Articles
                └── blogArticle.yaml
                └── newsArticle.yaml
```


#### 2.1.3 Adding a custom path
Beside the first two pre-defined directory paths it is also possible to define a third custom path where your custom YAML files are stored. For this you could simply add the custom path to the extension configuration in `Admin Tools -> Settings -> Extension Configuration -> flexible_pages` or set `$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['flexible_pages']['additionalYamlConfigPath']` in your `ext_localconf.php`.


#### 2.1.4 YAML File Example
```
dokType: 87
label: 'My Custom pageType'
iconSet:
  defaultIcon:
    source: 'EXT:your_ext/Resources/Public/Icons/apps-pagetree-mycustompagetype.svg'
  hideInMenuIcon:
    identifier: 'apps-pagetree-page-frontend-user-hideinmenu'
  rootPageIcon:
    source: 'EXT:your_ext/Resources/Public/Icons/apps-pagetree-mycustompagetype-root.svg'
isDraggableInNewPageDragArea: true

```


### 2.2 Using ext_localconf.php
```php
\ITplusX\FlexiblePages\Registry\PageTypesRegistration::registerPageType(
  87
  'My Custom pageType',
  [
    \ITplusX\FlexiblePages\Configuration\IconSetConfiguration::ICON_TYPE_DEFAULT => [
        'source' => 'EXT:your_ext/Resources/Public/Icons/apps-pagetree-mycustompagetype.svg',
    ],
    \ITplusX\FlexiblePages\Configuration\IconSetConfiguration::ICON_TYPE_HIDE_IN_MENU => [
        'identifier' => 'apps-pagetree-page-frontend-user-hideinmenu',
    ]
    \ITplusX\FlexiblePages\Configuration\IconSetConfiguration::ICON_TYPE_ROOT_PAGE => [
        'source' => 'EXT:your_ext/Resources/Public/Icons/apps-pagetree-mycustompagetype-root.svg',
    ]
  ],
  'isDraggableInNewPageDragArea' => true
);
```


### 2.3 Configuration

#### 2.3.1 Registration parameters
| Parameter                    | Type   | Mandatory | Description                                                                                            |
|------------------------------|--------|-----------|--------------------------------------------------------------------------------------------------------|
| dokType                      | int    | ✓         | The dokType to register the new pageType with                                                          |
| label                        | string | ✓         | The label of the new pageType                                                                          |
| iconSet                      | array  | ✓         | The iconSet array of the newPageType. (see: [iconSet configuration parameters](#232-icons-parameters)) |
| isDraggableInNewPageDragArea | bool   |           | Defines if the new pageType is draggable from above the page tree. (Default: false)                    |


#### 2.3.2 Icons parameters
| Parameter      | Type  | Mandatory | Description                                                  | Possible values                                                                                          |
|----------------|-------|-----------|--------------------------------------------------------------|----------------------------------------------------------------------------------------------------------|
| defaultIcon    | array | ✓         | The default icon of the page.                                | - `'source' => '/path/to/file.png'`(EXT: is allowed)<br> - `'identifier' => 'already-registered-identifier'` |
| hideInMenuIcon | array |           | The icon of the page when "hideInMenu" is checked.           | - `'source' => '/path/to/file.png'`(EXT: is allowed)<br> - `'identifier' => 'already-registered-identifier'` |
| rootPageIcon   | array |           | The icon of the page when the page is selected as root page. | - `'source' => '/path/to/file.png'`(EXT: is allowed)<br> - `'identifier' => 'already-registered-identifier'` |


## 3. Generic frontend list plugin (*tx_flexiblepages_pagelist*)
*flexible_pages* provides a generic list plugin to list pages by specific pageTypes. Some settings of this plugin can be configured by the user to make it as flexible as possible:


### 3.1 Extending templates
Extending the template select field makes it possible to add your own custom Templates. Custom templates can be added either by PageTS or the EXTCONF array.


#### 3.1.1 Extending via PageTS

##### as associative array:
```typo3_typoscript
tx_flexiblepages {
  tx_flexiblepages_pagelist {
    templates {
      myTemplate = My new template
      myTemplate2 = My new template 2
    }
  }
}
```


##### as numeric array:
```typo3_typoscript
tx_flexiblepages {
  tx_flexiblepages_pagelist {
    templates {
      0 = My new template
      1 = My new template 2
    }
  }
}
```


#### 3.1.2 Extending via EXTCONF

##### as associative array:
```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['flexible_pages']['tx_flexiblepages_pagelist']['templates'] = [
    'myTemplate' => 'My new template',
    'myTemplate2' => 'My new template 2',
];
```


##### as numeric array:
```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['flexible_pages']['tx_flexiblepages_pagelist']['templates'] = [
    'My new template', 'My new template 2'
];
```


#### 3.1.3 Adding custom template file
After extending the template configuration you want to add your custom template file. To do that you have to extend the fluid_styled_content rootPaths. You can either do this in your own extension by extending the `lib.contentElement` TypoScript (as described [HERE](https://docs.typo3.org/c/typo3/cms-fluid-styled-content/master/en-us/AddingYourOwnContentElements/Index.html#setup-txt)) or you could use the constants provided by flexible_pages:
- `plugin.tx_flexiblepages.templateRootPath`
- `plugin.tx_flexiblepages.partialRootPath`
- `plugin.tx_flexiblepages.layoutRootPath`

Finally you can add your custom template file in the previously specified `templateRootPath`.


##### File naming:
- When you chose to extend the templates with a associative array, your template file has to be named like the key (as upper camelcase; e.g `MyTemplate.html`).
- When you chose to extend the templates with a numeric array, your template file has to be named like the value (as upper camelcase; e.g `MyNewTemplate.html`).


### 3.2 Extending orderBy
The `orderBy` selection can be extended as well to add custom order fields. For example if you added a new field `type` to your custom pageType you could also order by this field if necessary.

For every added custom `orderBy` item an ascending and descending variant is created. Custom order fields can be added either by PageTS or the EXTCONF array.

**NOTE: The key of the `orderBy` item must be the name of the field in the database! Numeric arrays are not allowed here.**


#### 3.2.1 Extending via PageTS
```typo3_typoscript
tx_flexiblepages {
  tx_flexiblepages_pagelist {
    orderBy {
      type = Type
    }
  }
}
```


#### 3.2.2 Extending via EXTCONF
```php
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['flexible_pages']['tx_flexiblepages_pagelist']['orderBy'] = [
    'type' => 'Type'
];
```

## 4. Working Example
For a working example please have a look at [EXT:flexible_news](https://github.com/itplusx/flexible_news).
There you can see how simple it is to:

- Setup a new pageType with special icons and name
- Extend the template select of the *tx_flexiblepages_pagelist* CE

---

<p align="center">
  <a href="https://itplusx.de" target="_blank" rel="noopener noreferrer">
    <img width="350" src="https://itplusx.de/banners/created-by-X-with-passion.svg" alt="ITplusX - Internetagentur & Systemhaus">
  </a>
</p>

---
