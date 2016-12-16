<?php
namespace Smarty;

use Smarty\View\Strategy;
use Smarty\Service\StrategyFactory;
use Smarty\View\Renderer;
use Smarty\Service\RendererFactory;

return [
    'view_manager' => [
        'strategies' => [
            Strategy::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Strategy::class => StrategyFactory::class,
            Renderer::class => RendererFactory::class,
        ],
    ],
    'smarty' => [
        'suffix' => 'tpl',
        'compile_dir' => getcwd() . '/data/smarty/templates_c',
        'config_file' => getcwd() . '/config/autoload/smarty.conf',
        'escape_html' => true,
        'caching' => false,
        'cache_dir' => getcwd() . '/data/smarty/cache',
        'plugins_dir' => getcwd() . '/data/smarty/plugins',
    ],
];
