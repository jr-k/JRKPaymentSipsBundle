<?php

/*
    Copyright 2014 Jessym Reziga https://github.com/jreziga/JRKPaymentSipsBundle

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
namespace JRK\PaymentSipsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    
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

