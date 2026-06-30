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

| Module | Description | Version | License | Author |
|--------|-------------|---------|---------|--------|
| AppAuth | Application auth | 0.1.1 | MIT | Your Name |
| ForgeAdminConsole | Protected admin console with dashboard, account, profile, and user management | 0.1.2 | MIT | Forge Team |
| ForgeAppAuth | Distributable authentication implementation with login, register, forgot-password, and reset-password | 0.1.4 | MIT | Forge Team |
| ForgeAuth | An Auth module by forge. | 2.0.7 | MIT | Forge Team |
| ForgeBilling | Billing portal with plans, invoices, and payment provider support | 0.2.5 | MIT | Forge Team |
| ForgeComponents | Primitive reusable UI components with vanilla CSS design system | 0.3.7 | MIT | Forge Team |
| ForgeDatabaseSQL | SQL database support (SQLite, MySQL, PostgreSQL) | 0.9.13 | MIT | Forge Team |
| ForgeDebugBar | A debug bar by Forge | 1.3.7 | MIT | Forge Team |
| ForgeDeployment | Deploy applications to cloud providers with automated provisioning | 2.5.5 | MIT | Forge Team |
| ForgeErrorHandler | An error handler by Forge | 1.2.3 | MIT | Forge Team |
| ForgeEvents | An Event Queue system by forge | 1.4.8 | MIT | Forge Team |
| ForgeHub | Administration Hub for Forge Framework | 2.5.9 | MIT | Forge Team |
| ForgeLanding | Public-facing landing page with navigation to auth flows | 0.1.2 | MIT | Forge Team |
| ForgeLanguage | Multi language support to extend Forge Kernel | 0.2.3 | MIT | Your Name |
| ForgeLogger | A logger by Forge. | 0.5.4 | MIT | Forge Team |
| ForgeMarkDown | A markdown processor | 0.1.3 | MIT | Forge Team |
| ForgeMarkDown | A markdown processor | 0.1.3 | MIT | Forge Team |
| ForgeMultiTenant | A Multi Tenant Module by Forge | 0.3.5 | MIT | Forge Team |
| ForgeNotification | Multi-channel notification system with provider support, fluent API, and async queue integration | 0.3.3 | MIT | Forge Team |
| ForgePackageManager | A Package Manager By Forge | 3.3.21 | MIT | Forge Team |
| ForgeRouter | Forge Router and Http | 1.0.12 | MIT | Forge Team |
| ForgeSaas | SaaS plans, subscriptions, and feature gating for Forge Kernel | 0.1.4 | MIT | Forge Team |
| ForgeSqlOrm | SQL ORM Support (SQLite, MySQL, PostgreSQL) | 0.6.7 | MIT | Forge Team |
| ForgeStaticGen | A Basic Static Site Generator by Forge | 0.2.2 | MIT | Forge Team |
| ForgeStaticHtml | Static HTML generator with link crawling, depth control, and asset management | 0.1.0 | MIT | Forge Team |
| ForgeStorage | Simple file upload storage module with multiple provider support | 1.3.3 | MIT | Forge Team |
| ForgeTailwind | A tailwind module by forge | 0.2.3 | MIT | Forge Team |
| ForgeTesting | A Test Suite Module By Forge | 0.4.3 | MIT | Forge Team |
| ForgeUi | A UI component module by forge. | 1.1.3 | MIT | Forge Team |
| ForgeView | A View engine provided by forge | 0.1.2 | MIT | Forge Team |
| ForgeWelcome | A playground by forge | 1.2.5 | MIT | Forge Team |
| ForgeWire | A reactive controller rendering protocol for PHP | 2.7.6 | MIT | Forge Team |

*Module information is automatically generated from module source code.*

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
