# 📦 Forge Framework – My Own Module Stash

Hey 👋 Welcome to the place where I store all the extra bits and pieces I’ve built for [Forge](https://github.com/forge-engine/framework) — my personal PHP framework.

This repo isn’t some polished package bazaar — it’s more like my personal toolbox. But if you find something useful here, feel free to use it, tweak it, or fork it to fit your thing. That’s the spirit Forge is built on.

### ⛏️ About This Registry

When you run a command like:

```bash
php forge.php package:install forge-auth@0.1.0
```

The package manager knows to come here and look in the `modules/` folder. All official `forge-*` modules live here. If you’re building your own registry (which is 100% encouraged), you can point the package manager to your own repo and structure it the same way.

This repo just happens to be the **default** registry.

---

### ✅ Currently Available Modules

Here’s what’s in the stash right now:

- `forge-auth` – Basic auth scaffolding (login, registration, etc.)
- `forge-error-handler` – Global error catcher, for CLI and web
- `forge-hub` – An administration panel on which you run cli commands, see framework metrics and stats, etc (WIP)
- `forge-logger` – Simple and effective logging
- `forge-nexus` – A CMS I'm building (WIP, but cool stuff happening)
- `forge-notifications` – Email/SMS/Push support (WIP)
- `forge-package-manager` – Lets you install/manage modules easily
- `forge-storage` – File storage similar to an s3, it have temporary urls, an administration panel (works with local, S3, etc.)
- `forge-ui` – UI components, slowly building what I need
- `forge-welcome` – The welcome screen you see in `forge-starter`

These are built based on what I need for my projects. Some are stable, some are in progress. That’s just the nature of a living, personal framework.

---

### 🛠️ Want to Make Your Own?

Forge doesn’t lock you into anything. You can totally set up your own registry and use it with the same package manager.

To do that:

1. Create a public GitHub repo
2. Copy the folder structure from this one (`modules/module-name/version/`)
3. Update your own `PackageManagerService` to point to your custom registry

If you forked Forge, you’ll probably want to:

- Change the registry URL
- Change the default module prefix (if you don’t want to use `forge-*`)

You’re in control.

If you’re making your own modules or registry, feel free to use this repo as a reference — or even a starting point:

```bash
git clone https://github.com/forge-engine/modules my-forked-registry
```

Then go build your own thing. That’s the Forge way.

