<?php
namespace Smarty\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Smarty\View\Renderer;

class RendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
		$config = $container->get('Configuration');
		$config = $config['smarty'];

		/** @var $pathResolver \Zend\View\Resolver\TemplatePathStack */
		$pathResolver = clone $container->get('ViewTemplatePathStack');
		$pathResolver->setDefaultSuffix(($config['suffix']) ? $config['suffix'] : 'tpl');

		/** @var $resolver \Zend\View\Resolver\AggregateResolver */
        $resolver = $container->get('ViewResolver');
        $resolver->attach($pathResolver, 2);

		$engine = new \Smarty();
		$engine->setCompileDir($config['compile_dir']);

		if (file_exists($config['config_file'])) {
			$engine->configLoad($config['config_file']);
		}

		$renderer = new Renderer();
		$renderer->setEngine($engine);
		$renderer->setSuffix(($config['suffix']) ? $config['suffix'] : 'tpl');
		$renderer->setResolver($resolver);

		return $renderer;
    }
}