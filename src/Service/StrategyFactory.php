<?php
namespace Smarty\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Smarty\View\Strategy;

class StrategyFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
		$renderer = $container->get('Smarty\View\Renderer');
		$strategy = new Strategy($renderer);
		return $strategy;
    }
}