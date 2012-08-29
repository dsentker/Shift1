<?php
namespace Bundles\Shift1\BlogDemoBundle;

use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Config\Builder\ConfigTreeBuilder;
use Shift1\Core\Config\Builder\AdjustmentRequest;
use Shift1\Core\Config\File\YamlFile;

class BlogDemoBundleManager extends BundleManager {

    public function loadApplicationConfiguration() {

        $builder = new ConfigTreeBuilder();

        $builder->node('posts')
                    ->addItem('postsPerPage', 10, AdjustmentRequest::create()->setPrompt('Please enter the posts per page, default')->setValidation('#^\d+$#', 'Please enter a numeric value!'))
            ;

        return $builder;
    }

    public function loadHttpRouteCollection() {
        $bundleRoutes = new YamlFile(__DIR__ . '/Routing/routes.yml');
        return \Shift1\Core\Routing\Route\RouteCollection::fromConfig($bundleRoutes);
    }

}
