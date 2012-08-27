<?php
namespace Shift1\Core\Config\Builder\CollisionHandler;

use Shift1\Core\Config\Builder\Item\ConfigItem;
use Shift1\Core\Config\Builder\CollisionHandler\Exception\NodeCollisionException;


class ExceptionNodeCollisionHandler {

    public function getPreferredNode(ConfigItem $node1, ConfigItem $node2) {
        throw new NodeCollisionException(\sprintf("Config key collision detected between %s and %s!", $node1->getKey()))
    }

}
