# zf3-smarty-module
Based on https://github.com/randlem/zf2-smarty-module and modified for use with ZF3

# Installation
Although you could just clone this repository, it is highly recommended to install the module via composer.
- run `php composer.phar require skillfish/zf3-smarty-module`
- add Smarty to your ZF3 modules Configuration e.g /config/modules.config.php
- change the View Manager Strategy to Smarty in your module or apllication config like:
```php
<?php
return [
  'view_manager' => [
    'strategies' => [
      'Smarty\View\Strategy'
    ],
  ],
];
```
- you might also want to change your View Manager's Template Map to actually use .tpl files instead of the default .phtml
```php
<?php
return [
  'view_manager' => [
    'template_map' => [
      'layout/layout' => '/module/Application/view/layout/layout.tpl',
      'application/index/index' => '/module/Application/view/application/index/index.tpl',
      'error/404' => '/module/Application/view/error/404.tpl',
      'error/index' => '/module/Applicationview/error/index.tpl',
    ],
  ],
];
```
# Requirements
The composer module currently requires:
- "smarty/smarty": "^3.1.29"
- "zendframework/zendframework": "^3.0.0"
