<?php
/**
 * entry point for PHP-OpenCloud library
 *
 * @copyright 2013 Rackspace Hosting, Inc.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

if (!class_exists('ClassLoader')) {
  require_once(__DIR__ . '/Autoload.php');
}
require_once(__DIR__ . '/OpenCloud/Globals.php');

$classLoader = new ClassLoader;
$classLoader->registerNamespaces(array(
	'OpenCloud' => array(__DIR__, __DIR__ . '/../tests')
));
$classLoader->register();
