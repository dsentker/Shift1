<?php
namespace Shift1\Core\Config\Builder\CollisionHandler;

use Shift1\Core\Config\Builder\Item\ConfigItem;

interface NodeCollisionHandlerInterface {

    function getPreferredNode(ConfigItem $node1, ConfigItem $node2);

}
