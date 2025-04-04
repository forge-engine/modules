# 📦 Forge Framework's Official Goodies Stash!

Hey again! 👋 So, this is the official spot where all the cool modules for the Forge Framework hang out. Think of it like our curated collection of building blocks.

You know when you run that command `php forge.php package:install awesome-module@some-version`? Well, this is where that command comes to find those modules! It's the official registry, but the awesome thing is, you can totally create your own registry too – more on that later!

Oh, and a little heads-up: any module name that starts with `forge-` is special. Our official package manager will always grab those from this very stash. But hey, if you decide to roll your own package manager or just want to do things differently, that's totally up to you!

## Official Modules You Can Grab ✅

Here are the official modules we've got for you right now:

- `forge-database` (For all your database needs)
- `forge-debug-bar` (Your handy debugging friend)
- `forge-error-handler` (Catches those oopsies gracefully)
- `forge-logger` (Keeps track of what's happening in your app)
- `forge-mark-down` (Makes working with Markdown a breeze)
- `forge-static-gen` (Perfect for building static sites)
- `forge-storage` (For handling file storage)
- `forge-testing` (Helps you write awesome tests)
- `forge-view-engine` (For rendering your web pages)

Just to give you a little more info on a couple:

* **`forge-logger`**: This guy helps you manage all your application logs. You can set different levels of logging and send them to various places – super useful for debugging and keeping an eye on things. 📝
* **`forge-package-manager`**: This is the tool that lets you easily install and manage other modules in your Forge projects. Pretty important, right? ⚙️

You can dive into the code for any of these modules – just head over to the `modules/` folder in this repository. Each module has its own folder, and then folders for each version (like `modules/forge-router/1.0.0/`).

## Want to Make Your Own Module Stash? 🛠️

Forge is all about being flexible, so while we have these official modules, we'd love for you to create your own module registries too! Maybe you have a set of modules that are specific to your projects, or you want to share your creations with the world.

**Important Note (For Now): Public Repos Only** 🔑
Just a heads-up for the current version of Forge's package manager: your custom module registry needs to be a **public repository** (like on GitHub). That's because the package manager currently fetches info and downloads modules using those public GitHub links. We might add support for private registries later on.

Here's the basic idea of how to set up your own module registry:

1. **Think of it Like This Repo**: A module registry is basically a Git repository set up in a specific way. You could even start by cloning this one as a template:

   ```bash
   git clone [https://github.com/forge-engine/modules-v2](https://github.com/forge-engine/modules-v2) my-forge-registry
   cd my-forge-registry
   # If you want a completely fresh start, you can remove our modules
   rm -rf modules/*