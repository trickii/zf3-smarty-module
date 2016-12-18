<?php
namespace Smarty\Service\PluginProxy;

use Smarty\Plugin\IfBlockPluginInterface;

class IfBlockProxy
{
    /**
     * @var IfBlockPluginInterface
     */
    private $plugin;

    /**
     * @var string
     */
    private $name;

    /**
     * @param IfBlockPluginInterface $plugin
     * @param string $name
     */
    public function __construct(IfBlockPluginInterface $plugin, $name)
    {
        $this->plugin = $plugin;
        $this->name = $name;
    }

    public function __invoke(array $params, $content, $smarty, &$repeat)
    {
        $trueFalseBlockContents = explode(sprintf('{%s_else}', $this->name), $content);
        if (!isset($trueFalseBlockContents[1])) {
            $trueFalseBlockContents[1] = '';
        }

        if (!$pugin->checkCondition($params, $smarty)) {
            return $this->plugin->prepareFalse($trueFalseBlockContents[1]);
        }

        return $this->plugin->prepareTrue($trueFalseBlockContents[0]);
    }

    public function elseFunction(array $params, $smarty)
    {
        return $smarty->left_delimiter . $this->name . '_else' . $smarty->right_delimiter;
    }
}
