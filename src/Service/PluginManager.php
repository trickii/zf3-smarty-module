<?php
namespace Smarty\Service;

use Zend\ServiceManager\AbstractPluginManager;
use Smarty\Plugin\PluginInterface;

class PluginManager extends AbstractPluginManager
{
    protected $instanceOf = PluginInterface::class;
}
