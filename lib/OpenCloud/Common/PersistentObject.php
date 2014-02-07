<?php
/**
 * PHP OpenCloud library
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common;

use OpenCloud\Common\Resource\PersistentResource;

/**
 * This class is deprecated; its functionality has been split out into the following classes:
 *
 * * {@see \OpenCloud\Common\Resource\BaseResource}
 * * {@see \OpenCloud\Common\Resource\NovaResource}
 * * {@see \OpenCloud\Common\Resource\PersistentResource}
 *
 * @deprecated
 * @package OpenCloud\Common
 */
abstract class PersistentObject extends PersistentResource
{
}