Getting Started With MultiTenantBundle
==================================

This bundle aims to priovide multi tenant solution for web applications build with Symfony 2.

Prerequisites
-------------

This version of the bundle requires Symfony 2.4+.

Installation
------------

1. Download MultiTenantBundle using composer
2. Enable the Bundle
3. Create your Tenant class
4. Setup your User class
5. Configure your application's security.yml
6. Configure the MultiTenantBundle
7. Update your database schema

Step 1: Download MultiTenantBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require cvele/multitenant-bundle "dev-master"

Composer will install the bundle to your project's ``vendor/cvele/multitenant-bundle`` directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel::

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Cvele\MultiTenantBundle\MultiTenantBundle(),
            // ...
        );
    }

Step 3: Create your Tenant class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1. Extend the base ``Tenant`` class from the Model directory
2. Map the ``id`` field. It must be protected as it is inherited from the parent class.

.. caution::

    When you extend from the mapped superclass provided by the bundle, don't
    redefine the mapping for the other fields as it is provided by the bundle.

MultiTenantBundle currently supports only Doctrine ORM.

.. note::

    The doc uses a bundle named ``AppBundle`` according to the Symfony best
    practices. However, you can of course place your user class in the bundle
    you want.

a) Doctrine ORM Tenant class
..........................


.. configuration-block::

    .. code-block:: php-annotations

        <?php
        // src/AppBundle/Entity/Tenant.php

        namespace AppBundle\Entity;

        use Cvele\MultiTenantBundle\Model\Tenant as BaseTenant;
        use Doctrine\ORM\Mapping as ORM;

        /**
         * @ORM\Entity
         * @ORM\Table(name="tenants")
         */
        class Tenant extends BaseTenant
        {
            /**
             * @ORM\Id
             * @ORM\Column(type="integer")
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            public function __construct()
            {
                parent::__construct();
                // your own logic
            }
        }

Step 4: Setup your User class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For the sake of this example we will use User class as it would look like for FOSUserBundle.

.. configuration-block::

    .. code-block:: php-annotations

        <?php
        // src/AppBundle/Entity/User.php

        namespace AppBundle\Entity;

        use FOS\UserBundle\Model\User as BaseUser;
        use Cvele\MultiTenantBundle\Model\Traits\TenantAwareUserTrait;
        use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;
        use Doctrine\ORM\Mapping as ORM;

        /**
         * @ORM\Entity
         * @ORM\Table(name="users")
         */
        class User extends BaseUser implements TenantAwareUserInterface
        {
            use TenantAwareUserTrait;

            /**
             * @ORM\Id
             * @ORM\Column(type="integer")
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            public function __construct()
            {
                parent::__construct();
                // your own logic
            }
        }

Step 5: Configure your application's security.yml
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Users belong to more then one tenant, when this is the case we need to provide a way for user
to pick which tenant he wishes to login to. We will do this by presenting user with a list of all tenants he belongs to.
This is accomplished with custom login success handler.

To use default success handler edit your security.yml:

.. configuration-block::

    .. code-block:: yaml

        # app/config/security.yml
        firewalls:
            main:
                form_login:
                    success_handler: multi_tenant.handler.user_login_redirect_handler


Step 6: Configure the MultiTenantBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that you have properly configured your application's ``security.yml`` to work
with the MultiTenantBundle, the next step is to configure the bundle to work with
the specific needs of your application.

Add the following configuration to your ``config.yml`` file according to which type
of datastore you are using.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        multi_tenant:
            user_entity_class: AppBundle\Entity\User
            tenant_entity_class: AppBundle\Entity\Tenant
            logout_route: fos_user_security_logout
            redirect_after_login_route: dashboard
            pick_tenant_route: pick_tenant

Step 7: Update your database schema
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a new entity, the ``User`` class which you
created in Step 4.

For ORM run the following command.

.. code-block:: bash

    $ php bin/console doctrine:schema:update --force