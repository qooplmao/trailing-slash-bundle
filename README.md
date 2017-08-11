# Mf1TrailingSlashBundle

This bundle enables you to get a big of consistency when it comes to trailing
slashes in your Symfony routes.

This idea originally came about when creating an API and having to deal with
Symfony routing requirements of having to have, at least, a `/` rather than
an empty path. Symfony, very helpfully, handles redirects between routes with
trailing slashes to those without (or the other way around.. I forget which).
As API routes are usually exposed to a user I reasoned that there should be both
uniformity (either all with or without trailing slashes) along with as few redirects
as possible.

For example

```yaml
# app/config/routing.yml
routes:
    prefix: /api/
    resource: @AppBundle/Resources/config/routing.yml

# src/AppBundle/Resources/config/routing/yml
routes_1:
    path: /
    ...

routes_2:
    path: /nested
    ...
```

Would become..

```bash
$ bin/console debug:router

 ---------- -------- -------- ------ -----------------------------------
  Name       Method   Scheme   Host   Path
 ---------- -------- -------- ------ -----------------------------------
  routes_1   ANY      ANY      ANY    /api/
  routes_2   ANY      ANY      ANY    /api/nested
```

In an ideal world they would be `/api` and `/api/nested` (or `/api/` and
`/api/nested/`... your choice).

So..

## Installation

1. Download Mf1TrailingSlashBundle using composer
2. Enable the bundle

### Step 1. Download Mf1TrailingSlashBundle using composer

Require the bundle using the command line

```cli
$ composer require mf1/trailing-slash-bundle
```

### Step 2. Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Mf1\TrailingSlashBundle\Mf1TrailingSlashBundle(),
        // ...
    );
}
```

## Configuration

The configuration is very basic. Essentially you just set a path and state what to do
with the trailing slash.

**NOTE** Nested routes will follow the same rule as their closest parent until they
reach a new matching rule.

```yaml
mf1_trailing_slash:
    rules:
        # /remove-slash/ will become /remove-slash
        # /remove-slash/a/ will become /remove-slash/a
        # /remove-slash/b will become /remove-slash/b
        - { path: /remove-slash/, slash: remove }
        # /add-slash will become /add-slash/
        # /add-slash/a will become /add-slash/a/
        # /add-slash/b/ will become /add-slash/b/
        - { path: /add-slash, slash: add }
        # /leave-as-default will stay as /leave-as-default
        - { path: /leave-as-default, slash: no_change }
        # /nested/ will stay as /nested/
        - { path: /nested/, slash: no_change }
        # /nested/add will become /nested/add/
        # even though /nested/ is set to not change
        - { path: /nested/add, slash: add }
        # /nested/remove/ will become /nested/remove
        # even though /nested/ is set to not change
        - { path: /nested/remove/, slash: remove }
```

## License

This bundle is under the MIT license. See the complete license in the bundle

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/qooplmao/trailing-slash-bundle/issues).
