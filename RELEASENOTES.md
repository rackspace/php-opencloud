RELEASE NOTES
-------------

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
