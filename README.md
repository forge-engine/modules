# Forge Kernel Modules Registry

Capability modules for the Forge kernel. Module metadata is extracted from
each module's PHP source code using the `#[Module]` attribute.

These are personal packages that happen to be useful enough to publish.
Update them when new versions are released, take what you need, modify
what does not fit, delete what you do not want. You own your stack.

## Index

- [About This Registry](#about-this-registry)
- [Available Modules](#available-modules)
- [Adding This Registry](#adding-this-registry)
- [Module Discovery](#module-discovery)

## About This Registry

When you run a command like:

```bash
php forge.php package:install-module --module=forge-auth@0.1.0
```

The package manager looks in the `modules/` folder. All official `forge-*`
modules live here. If you are building your own registry, point the package
manager to your own repo with the same structure.

This is the default registry.

## Available Modules

| Module              | Description                                                                                      | Version |
| ------------------- | ------------------------------------------------------------------------------------------------ | ------- |
| ForgeAuth           | An Auth module by forge.                                                                         | 0.7.0   |
| ForgeComponents     | Component library module that composes ForgeUi primitives                                        | 0.2.0   |
| ForgeDatabaseSQL    | SQL database support (SQLite, MySQL, PostgreSQL)                                                 | 0.4.0   |
| ForgeDebugBar       | A debug bar by Forge                                                                             | 1.3.0   |
| ForgeDeployment     | Deploy applications to cloud providers with automated provisioning                               | 2.5.0   |
| ForgeErrorHandler   | An error handler by Forge                                                                        | 1.2.0   |
| ForgeEvents         | An Event Queue system by forge                                                                   | 1.4.0   |
| ForgeHub            | Administration Hub for Forge Framework                                                           | 2.5.0   |
| ForgeLogger         | A logger by Forge.                                                                               | 0.5.0   |
| ForgeMarkDown       | A markdown processor                                                                             | 0.1.0   |
| ForgeMultiTenant    | A Multi Tenant Module by Forge                                                                   | 0.3.0   |
| ForgeNexus          | CMS for Forge Framework                                                                          | 0.2.0   |
| ForgeNotification   | Multi-channel notification system with provider support, fluent API, and async queue integration | 0.3.0   |
| ForgePackageManager | A Package Manager By Forge                                                                       | 3.3.0   |
| ForgeSqlOrm         | SQL ORM Support (SQLite, MySQL, PostgreSQL)                                                      | 0.6.0   |
| ForgeStaticGen      | A Basic Static Site Generator by Forge                                                           | 0.2.0   |
| ForgeStorage        | Simple file upload storage module with multiple provider support                                 | 1.3.0   |
| ForgeTailwind       | A tailwind module by forge                                                                       | 0.2.0   |
| ForgeTesting        | A Test Suite Module By Forge                                                                     | 0.4.0   |
| ForgeUi             | A UI component module by forge.                                                                  | 1.1.0   |
| ForgeWelcome        | A playground by forge                                                                            | 1.2.0   |
| ForgeWire           | A reactive controller rendering protocol for PHP                                                 | 2.7.0   |

_Module information is automatically generated from module source code._

## Adding This Registry

To use this registry in your Forge project, add it to `config/source_list.php`:

```php
return [
    'registry' => [
        [
            'name' => 'kernel-module-registry',
            'type' => 'git',
            'url' => 'https://github.com/forge-kernel/kernel-module-registry',
            'branch' => 'main',
            'private' => false,
            'description' => 'Forge Kernel Official Modules'
        ]
    ],
    'cache_ttl' => 3600
];
```

## Module Discovery

To see available modules and their information, use the package manager:

```bash
php forge.php package:list-modules
```

Module metadata is read directly from the module source code.
