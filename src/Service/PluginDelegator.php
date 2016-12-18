<?php
namespace Smarty\Service;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Zend\ServiceManager\PluginManagerInterface;
use Interop\Container\ContainerInterface;
use Smarty;
use Smarty\Service\PluginManager;
use Smarty\Exception\InvalidServiceException;
use Smarty\Plugin\FunctionPluginInterface;
use Smarty\Plugin\ModifierPluginInterface;
use Smarty\Plugin\BlockPluginInterface;
use Smarty\Plugin\IfBlockPluginInterface;
use Smarty\Plugin\CycleBlockInterface;

/**
 * This delegator used for registration Object-plugins in Smarty.
 */
class PluginDelegator implements DelegatorFactoryInterface
{
    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * @var PluginManagerInterface
     */
    private $pluginManager;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $config = $container->get('Configuration');
        $pluginsConfig = $config['smarty']['plugins'];

        $renderer = call_user_func($callback);
        $this->smarty = $renderer->getEngine();
        $this->pluginManager = $container->get(PluginManager::class);

        $this->registerFunctionPlugins($pluginsConfig['functions']);
        $this->registerModifierPlugins($pluginsConfig['modifiers']);
        $this->registerBlockPlugins($pluginsConfig['blocks']);
        $this->registerIfBlockPlugins($pluginsConfig['if_blocks']);
        $this->registerIfBlockPlugins($pluginsConfig['cycle_blocks']);

        return $renderer;
    }

    /**
     * @param array $pluginsConfig
     * @param string $expectedInstance
     * 
     * @throws InvalidServiceException
     *
     * @return PluginInterface[]
     */
    protected function getPlugins(array $pluginsConfig, $expectedInstance)
    {
        $plugins = [];
        foreach ($pluginsConfig as $name => $pluginClass) {
            $plugin = $this->pluginManager->build($pluginClass, ['name' => $name]);
            if (!$plugin instanceOf $expectedInstance) {
                throw new InvalidServiceException(sprintf(
                    'Plugin delegator "%s" expected an instance of type "%s", but "%s" was received',
                    __CLASS__,
                    $expectedInstance,
                    is_object($plugin) ? get_class($plugin) : gettype($plugin)
                ));
            }
            $plugins[] = $plugin;
        }
        return $plugins;
    }

    public function registerFunctionPlugins(array $plugins)
    {
        foreach ($this->getPlugins($plugins, FunctionPluginInterface::class) as $plugin) {
            $this->smarty->registerPlugin('function', $name, [$plugin, 'run']);
        }
    }

    public function registerModifierPlugins(array $plugins)
    {
        foreach ($this->getPlugins($plugins, FunctionPluginInterface::class) as $plugin) {
            $this->smarty->registerPlugin('modifier', $name, [$plugin, 'modify']);
        }
    }

    public function registerBlockPlugins(array $plugins)
    {
        foreach ($this->getPlugins($plugins, FunctionPluginInterface::class) as $plugin) {
            $this->smarty->registerPlugin('block', $name, [$plugin, 'prepare']);
        }
    }

    public function registerIfBlockPlugins(array $plugins)
    {
        foreach ($this->getPlugins($plugins, FunctionPluginInterface::class) as $plugin) {
            $this->smarty->registerPlugin('block', $name, function(array $params, $content, $smarty, &$repeat) use($name, $plugin)
            {
                $trueFalseBlockContents = explode(sprintf('{%s_else}', $name), $content);
                if (!isset($trueFalseBlockContents[1])) {
                    $trueFalseBlockContents[1] = '';
                }

                if (!$pugin->checkCondition($params, $smarty)) {
                    return $plugin->prepareFalse($trueFalseBlockContents[1]);
                }

                return $plugin->prepareTrue($trueFalseBlockContents[0]);
            });
            $this->smarty->registerPlugin('function', $name . '_else', function(array $params, $smarty) use($name)
            {
                return $smarty->left_delimiter . $name . '_else' . $smarty->right_delimiter;
            });
        }
    }

    public function registerCycleBlockPlugins(array $plugins)
    {
        foreach ($this->getPlugins($plugins, CycleBlockInterface::class) as $plugin) {
            $this->smarty->registerPlugin('block', $name, function(array $params, $content, $smarty, &$repeat) use($plugin)
            {
                if (!$plugin->isValid($params, $smarty)) {
                    $repeat = false;
                    return '';
                }
                $repeat = true;
                return $plugin->prepare($params, $content, $smarty);
            });
        }
    }
}
