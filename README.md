# Forge Kernel - Modules Registry

This registry contains the capability modules I've built for my own projects and decided to share. Module information (name, description, version, author, license) is extracted directly from each module's PHP source code using the `#[Module]` attribute.

These aren't products. They're my personal packages that happen to be useful enough to publish. I'll update them when I publish new versions, and you can take those updates if you want. But more importantly, they're here as a starting point — use what you need, modify what doesn't fit, delete what you don't want. If you find a bug, you can fix it in your project right away, report it and wait for an update, or create a PR. You own your stack. That's the point of Forge.

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

The package manager knows to come here and look in the `modules/` folder. All official `forge-*` modules live here. If you're building your own registry (which is encouraged), you can point the package manager to your own repo and structure it the same way.

This repo just happens to be the **default** registry.

## Available Modules

| Module | Description | Version | License | Author |
|--------|-------------|---------|---------|--------|
| ForgeAuth | An Auth module by forge. | 0.5.0 | MIT | Forge Team |
| ForgeComponents | Component library module that composes ForgeUi primitives | 0.2.0 | MIT | Forge Team |
| ForgeDatabaseSQL | SQL database support (SQLite, MySQL, PostgreSQL) | 0.4.0 | MIT | Forge Team |
| ForgeDebugBar | A debug bar by Forge | 1.1.0 | MIT | Forge Team |
| ForgeDeployment | Deploy applications to cloud providers with automated provisioning | 1.1.0 | MIT | Forge Team |
| ForgeErrorHandler | An error handler by Forge | 1.2.0 | MIT | Forge Team |
| ForgeEvents | An Event Queue system by forge | 0.3.0 | MIT | Forge Team |
| ForgeHub | Administration Hub for Forge Framework | 0.2.0 | MIT | Forge Team |
| ForgeLogger | A logger by Forge. | 0.2.0 | MIT | Forge Team |
| ForgeMarkDown | A markdown processor | 0.1.0 | MIT | Forge Team |
| ForgeMarkDown | Static site generator for Forge Framework | 0.1.0 | MIT | Forge Team |
| ForgeMultiTenant | A Multi Tenant Module by Forge | 0.3.0 | MIT | Forge Team |
| ForgeNexus | CMS for Forge Framework | 0.2.0 | MIT | Forge Team |
| ForgeNotification | Multi-channel notification system with provider support, fluent API, and async queue integration | 0.2.0 | MIT | Forge Team |
| ForgePackageManager | A Package Manager By Forge | 3.1.0 | MIT | Forge Team |
| ForgeSqlOrm | SQL ORM Support (SQLite, MySQL, PostgreSQL) | 0.4.0 | MIT | Forge Team |
| ForgeStaticGen | A Basic Static Site Generator by Forge | 0.2.0 | MIT | Forge Team |
| ForgeStorage | Simple file upload storage module with multiple provider support | 1.1.0 | MIT | Forge Team |
| ForgeTailwind | A tailwind module by forge | 0.2.0 | MIT | Forge Team |
| ForgeTesting | A Test Suite Module By Forge | 0.3.0 | MIT | Forge Team |
| ForgeUi | A UI component module by forge. | 1.1.0 | MIT | Forge Team |
| ForgeWelcome | A playground by forge | 1.2.0 | MIT | Forge Team |
| ForgeWire | A reactive controller rendering protocol for PHP | 2.3.0 | MIT | Forge Team |

*Module information is automatically generated from module source code.*

## Adding This Registry

To use this registry in your Forge project, add it to `config/source_list.php`:

```php
return [
    'registry' => [
        [
            'name' => 'forge-engine-modules',
            'type' => 'git',
            'url' => 'https://github.com/forge-engine/modules',
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

Module metadata is read directly from the module source code, ensuring accuracy and eliminating redundancy.
| ForgeMarkDown | Static site generator for Forge Framework | 0.2.0 | MIT | Forge Team |
| ForgeDatabaseSQL | SQL database support (SQLite, MySQL, PostgreSQL) | 0.5.0 | MIT | Forge Team |
| AppAuth | Application-level auth configuration | 1.1.0 | MIT | Forge Team |
