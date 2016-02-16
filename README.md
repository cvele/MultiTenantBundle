MultiTenantBundle
==================
When building SaaS applications more often then not you are going to need multi tenant solution for your users. This bundle aims to provide simple solution for multitenancy.

Bundle takes care only of multitenancy ignoring all other aspects of account system your app might need (for that take a look at FOSUserBundle or something similar).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2b2fa3d6-3fbf-42bc-bb74-d33c67519148/mini.png)](https://insight.sensiolabs.com/projects/2b2fa3d6-3fbf-42bc-bb74-d33c67519148)
[![Latest Stable Version](https://poser.pugx.org/cvele/multitenant-bundle/v/stable)](https://packagist.org/packages/cvele/multitenant-bundle)
[![Total Downloads](https://poser.pugx.org/cvele/multitenant-bundle/downloads)](https://packagist.org/packages/cvele/multitenant-bundle)
[![License](https://poser.pugx.org/cvele/multitenant-bundle/license)](https://packagist.org/packages/cvele/multitenant-bundle)

Prerequisites
-------------

This version of the bundle requires Symfony 2.4+ and PHP 5.4+.

Features
------------
- User can own and/or belong to multiple tenants
- Tenants can be switched on the fly, with URL parameter or via helper method
- Traits for enteties that need to belong to tenants

**Note** *Bundle currently supports only Doctrine ORM for storage.*

Documentation
--------------
The source of the documentation is stored in the Resources/doc/ folder in this bundle.

[Read Documentation for master](https://github.com/cvele/MultiTenantBundle/blob/master/Resources/doc/index.rst)

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/cvele/MultiTenantBundle/issues).

