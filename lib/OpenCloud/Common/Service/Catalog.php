<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

/**
 * Description of Catalog
 * 
 * @link 
 */
class Catalog
{
    private $items = array();
    
    public static function factory($config)
    {
        if (is_array($config)) {
            $catalog = new self();
            foreach ($config as $item) {
                $catalog->items[] = CatalogItem::factory($item);
            }
        } elseif ($config instanceof Catalog) {
            $catalog = $config;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Argument for Catalog::factory must be either an array or an instance of %s',
                get_class($this)
            ));
        }
        
        return $catalog;
    }
    
    public function getItems()
    {
        return $this->items;
    }
}