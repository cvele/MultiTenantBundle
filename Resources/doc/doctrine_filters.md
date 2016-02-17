Enabling Doctrine Tenant Filter
===============================

MultiTenantBundle comes with prepared Tenant filter.
This means that every SQL query that is done via Doctrine on entities that are
tenant aware will be intercepted and where condition will be applied to it *ie* tenant_id = 123.

Enable TenantFilter in Doctrine
-------------

```yaml
#app/config/config.yml
doctrine:  
  orm:
    filters:
      user_filter:
        class:   Cvele\MultiTenantBundle\Filter\TenantFilter
        enabled: true
```

Thats it! Now every time you execute query on entity that implements TenantAwareEntityInterface
and uses TenantAwareEntityTrait tenant where condition will be applied.
