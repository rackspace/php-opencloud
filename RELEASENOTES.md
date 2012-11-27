RELEASE NOTES
-------------

### next version
* `DataObject::Create()` now uses `content_type`; using the older
  `type` parameter is deprecated and will cause an UnknownParameterError
  exception.

### 11/06/2012 Version 1.2
* Adds full support for OpenStack Cinder/Rackspace Cloud Block Storage
* Bug fixes
* Refactoring to reduce code size

### 10/25/2012 Version 1.1
* Fix for security issue #6
* Includes Quick Reference material on Cinder/Cloud Block Storage, but
  no additional docs yet (they're in progress)

### 10/12/2012 Version 1.0 final
* More refactoring to reduce the amount of duplicated code
* Fix for issue #3
* Added support for Cloud Block Storage (Nova Volumes) and 
  Cloud Load Balancers, but this is only prelinary and has
  no docs yet.

### 10/05/2012 Version 1.0 RC3
* Some refactoring in classes that extend NovaInstance
* Fixed bug where servers without networks did not receive default networks
  https://github.com/rackspace/php-opencloud/issues/2

### 09/28/2012 Version 1.0 (Build 39)
Supported products:
* OpenStack Swift (Rackspace Cloud Files)
* OpenStack Nova (Rackspace Next Gen Cloud Servers)
* Rackspace Cloud Networks
* Rackspace Cloud Databases
