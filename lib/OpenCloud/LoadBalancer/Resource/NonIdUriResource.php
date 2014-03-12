<?php

namespace OpenCloud\LoadBalancer\Resource;

/**
 * Represents a resource that cannot be queried based on its ID. Instead, it
 * uses its parent URL, plus a generic path name, to determine its state.
 *
 * @package OpenCloud\LoadBalancer\Resource
 */
abstract class NonIdUriResource extends AbstractResource
{
    public function refresh($id = null, $url = null)
    {
        return $this->refreshFromParent();
    }
}