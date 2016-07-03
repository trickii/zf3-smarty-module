<?php
namespace Smarty;

return [
    'view_manager' => [
        'strategies' => [
            'Smarty\View\Strategy',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Smarty\View\Strategy' => 'Smarty\Service\StrategyFactory',
            'Smarty\View\Renderer' => 'Smarty\Service\RendererFactory',
        ],
    ],
    'smarty' => [
        'suffix' => 'tpl',
        'suffixLocked' => true,
        'compile_dir' => getcwd().'/data/smarty/templates_c',
        'config_file' => getcwd() . '/config/autoload/smarty.conf',
    ],
];