<?php

namespace JRK\PaymentSipsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jrk_payment_sips');

		$rootNode
		->children()
		    ->arrayNode('files')
                ->children()
                    ->scalarNode('sips_pathfile')->end()
		            ->scalarNode('sips_request')->end()
		            ->scalarNode('sips_response')->end()
		            ->scalarNode('sips_logs')->end()
                ->end()->end()
            ->arrayNode('params')
                ->children()
                    ->scalarNode('sips_merchant_id')->end()
                    ->scalarNode('sips_currency_code')->end()
                    ->scalarNode('sips_language')->end()
                    ->scalarNode('sips_payment_means')->end()
                    ->scalarNode('sips_header_flag')->end()
                    ->scalarNode('sips_merchant_country')->end()
                ->end()->end()
            ->arrayNode('links')
                ->children()
                    ->scalarNode('sips_route_response')->end()
                    ->scalarNode('sips_cancel_return_url')->end()
                    ->scalarNode('sips_route_auto_response')->end()
        ->end();
        return $treeBuilder;
    }

}

