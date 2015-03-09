Security Group Rules
====================

Create a security group rule
----------------------------

This operation takes one parameter, an associative array, with the
following keys:

+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| Name                  | Description                                                                                                                                                                                                                                                       | Data type                             | Required?   | Default value   | Example value                              |
+=======================+===================================================================================================================================================================================================================================================================+=======================================+=============+=================+============================================+
| ``securityGroupId``   | The security group ID to associate with this security group rule.                                                                                                                                                                                                 | String                                | Yes         | -               | ``2076db17-a522-4506-91de-c6dd8e837028``   |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``direction``         | The direction in which the security group rule is applied. For a compute instance, an ingress security group rule is applied to incoming (ingress) traffic for that instance. An egress rule is applied to traffic leaving the instance.                          | String (``ingress`` or ``egress``)    | Yes         | -               | ``ingress``                                |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``ethertype``         | Must be IPv4 or IPv6, and addresses represented in CIDR must match the ingress or egress rules.                                                                                                                                                                   | String (``IPv4`` or ``IPv6``)         | No          | ``IPv4``        | ``IPv6``                                   |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``portRangeMin``      | The minimum port number in the range that is matched by the security group rule. If the protocol is TCP or UDP, this value must be less than or equal to the value of the ``portRangeMax`` attribute. If the protocol is ICMP, this value must be an ICMP type.   | Integer                               | No          | ``null``        | ``80``                                     |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``portRangeMax``      | The maximum port number in the range that is matched by the security group rule. The port\_range\_min attribute constrains the attribute. If the protocol is ICMP, this value must be an ICMP type.                                                               | Integer                               | No          | ``null``        | ``80``                                     |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``protocol``          | The protocol that is matched by the security group rule.                                                                                                                                                                                                          | String (``tcp``, ``udp``, ``icmp``)   | No          | ``null``        | ``tcp``                                    |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``remoteGroupId``     | The remote group ID to be associated with this security group rule. You can specify either ``remoteGroupId`` or ``remoteGroupPrefix``.                                                                                                                            | String                                | Optional    | ``null``        | ``85cc3048-abc3-43cc-89b3-377341426ac5``   |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+
| ``remoteIpPrefix``    | The remote IP prefix to be associated with this security group rule. You can specify either ``remoteGroupId`` or ``remoteGroupPrefix``.                                                                                                                           | String                                | Optional    | ``null``        | ``192.168.5.0``                            |
+-----------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------+--------------------------------------------+

You can create a security group rule as shown in the following example:

.. code:: php

  /** @var $securityGroupRule OpenCloud\Networking\Resource\SecurityGroupRule **/
  $securityGroupRule = $networkingService->createSecurityGroupRule(array(
      'securityGroupId' => '2076db17-a522-4506-91de-c6dd8e837028',
      'direction'       => 'egress',
      'ethertype'       => 'IPv4',
      'portRangeMin'    => 80,
      'portRangeMax'    => 80,
      'protocol'        => 'tcp',
      'remoteGroupId'   => '85cc3048-abc3-43cc-89b3-377341426ac5'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/create-security-group-rule.php>`_


List security group rules
-------------------------

You can list all the security group rules to which you have access as
shown in the following example:

.. code:: php

  $securityGroupRules = $networkingService->listSecurityGroupRules();
  foreach ($securityGroupRules as $securityGroupRule) {
      /** @var $securityGroupRule OpenCloud\Networking\Resource\SecurityGroupRule **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/list-security-group-rules.php>`_
