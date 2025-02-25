# Forge Framework - Official Modules

[![Forge Version](https://img.shields.io/badge/Forge-1.0.0-blue.svg)](https://github.com/yourorg/forge)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

> **The Modular PHP Framework with Zero Magic**

This repository contains the official modules for the Forge PHP Framework. Follows the core philosophy of:

- 🧩 **Explicit Control** - No hidden magic, you dictate the flow
- 📦 **Modular Architecture** - Use only what you need
- 🛠️ **Zero Dependencies** - Pure PHP components
- 🔒 **Security First** - Safe defaults and clear patterns

## Table of Contents

- [Core Modules](#core-modules)
- [Module Structure](#module-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [CLI Commands](#cli-commands)
- [Creating Modules](#creating-modules)
- [Contributing](#contributing)
- [License](#license)

## Core Modules

### 🗄️ Database Module

- Database abstraction layer
- Multiple driver support (MySQL, PostgreSQL, SQLite)
- Query builder with parameter binding

```php
$results = $db->query("SELECT * FROM users WHERE active = ?", [1]);
```

### 📜 ORM Module

- Active Record implementation
- Schema migrations system
- Model relationships (hasMany, belongsTo)

```php
class User extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email'];
}
```

### 📦 Package Manager

- Module discovery and installation
- Dependency resolution
- Registry management

```bash
php forge.php install:module forge-logger
```

### 📊 Logger Module

- Multiple log channels (file, syslog, stderr)
- PSR-3 compatible interface
- Rotating file handler

```php
$logger->info('User logged in', ['user_id' => 42]);
```

## Module Structure

A Forge module requires this basic structure:

```
module-name/
├── forge.json          # Module manifest
├── src/                # PHP classes
├── Database/           # Optional
│   ├── Migrations/     # Database migrations
│   └── Seeds/          # Database seeds
├── config/             # Configuration files
└── Cli/                # CLI commands
```

### forge.json Manifest

```json
{
    "$schema": "https://raw.githubusercontent.com/upperdo/forge-schemas/main/schemas/modules/schema.json",
    "name": "forge-logger",
    "description": "Official logging module",
    "version": "1.0.0",
    "provides": [
        "LoggerInterface@1.0"
    ],
    "requires": [
        "ErrorHandlerInterface@1.1"
    ],
    "class": "Forge\\Modules\\Logger\\LoggerModule"
}
```

## Installation

### Manual Installation

1. Clone module into `modules/` directory
2. Add to your application bootstrap:

```php
$forge->registerModule(\Forge\Modules\Logger\LoggerModule::class);
```

### Using Package Manager

```bash
php forge.php install:module forge-logger
```

## Configuration

Configure modules through `config/modules.php`:

```php
return [
    'logger' => [
        'default' => 'file',
        'channels' => [
            'file' => [
                'path' => storage_path('logs/app.log'),
                'level' => 'debug'
            ]
        ]
    ]
];
```

## CLI Commands

| Command                               | Description             |
|---------------------------------------|-------------------------|
| `php forge.php install:module <name>` | Install a module        |
| `php forge.php remove:module <name>`  | Remove a module         |
| `php forge.php list:modules`          | Show installed modules  |
| `php forge.php migrate`               | Run database migrations |
| `php forge.php seed`                  | Run database seeds      |

## Creating Modules

1. Create module structure:

```bash
mkdir -p modules/MyModule/src
```

2. Create `forge.json`:

```json
{
    "name": "my-module",
    "description": "My custom module",
    "version": "1.0.0",
    "class": "Forge\\Modules\\MyModule\\MyModule"
}
```

3. Create module class:

```php
namespace Forge\Modules\MyModule;

use Forge\Core\Contracts\Modules\ModulesInterface;

class MyModule implements ModulesInterface
{
    public function register(Container $container): void
    {
        // Register your services here
    }
}
```

4. Add to your application:

```php
$forge->registerModule(\Forge\Modules\MyModule\MyModule::class);
```

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Fork** the repository
2. Create a **feature branch**
3. Follow our coding standards:
    - PSR-12 coding style
    - Strict type declarations
    - Documented PHPDoc blocks
4. Submit a **Pull Request**

### Module Submission Process

1. Create a new directory under `modules/`
2. Include complete documentation
3. Add unit tests (PHPUnit)
4. Ensure `forge.json` is properly configured
5. Submit PR for review

## License

Forge Modules are open-source software licensed under the [MIT license](LICENSE).

---

📚 [Full Documentation](https://github.com/forge-engine/modules/docs) |
🐛 [Report Issues](https://github.com/forge-engine/modules/issues) |
💡 [Feature Requests](https://github.com/forge-engine/modules/discussions)

_Forge Framework - Build explicitly, scale infinitely_