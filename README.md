# Forge Framework Official Modules Registry 📦

This repository serves as the official module registry for the Forge Engine. It provides a curated collection of core modules developed and maintained by the Forge Engine team.

## Official Modules ✅

Here's a list of the official modules currently available in this registry:

- forge-database
- forge-api
- forge-debug-bar
- forge-error-handler
- forge-forge-logger
- forge-mark-down
- forge-orm
- forge-static-gen
- forge-storage
- forge-test
- forge-view-engine
*   **forge-router**:
    *   Description:  Provides robust routing capabilities for your Forge Engine applications, handling URL mapping and request dispatch. 🚀
*   **forge-logger**:
    *   Description:  A flexible logging module to manage application logs, supporting various levels and output destinations for debugging and monitoring. 📝
*   **forge-package-manager**:
    *   Description:  The Forge Engine's own package manager module, enabling you to install and manage modules within your Forge projects. ⚙️

You can explore the source code for each module within the `modules/` directory of this repository. Each module version is located in a subdirectory named after the module and version (e.g., `modules/forge-router/1.0.0/`).

## Creating Your Own Modules Registry 🛠️

Forge Engine is designed to be flexible. While this repository provides official modules, you are highly encouraged to create your own module registries tailored to your specific needs or for private module collections.

**Important: Public Repository Requirement (Current Version)** 🔑
In this version of the Forge Engine Package Manager, your custom module registry repository needs to be **publicly accessible** on platforms like GitHub. This is because the package manager currently fetches module information and downloads modules using raw GitHub URLs, which require public access. Future versions might introduce support for private registries and authentication.

Here's how to set up your own module registry:

1.  **Repository Structure**:  A module registry is essentially a Git repository that follows a specific structure. You can start by cloning this official modules repository as a template:

    ```bash
    git clone [https://github.com/forge-engine/modules-v2](https://github.com/forge-engine/modules-v2) my-forge-registry
    cd my-forge-registry
    # Remove the existing official modules if you want to start completely fresh (optional)
    rm -rf modules/*
    ```

2.  **`modules.json`**:  The heart of a module registry is the `modules.json` file located at the root of your repository. This file lists all available modules and their versions in the following JSON format:

    ```json
    {
        "module-name-1": {
            "latest": "1.0.0",
            "versions": {
                "1.0.0": {
                    "description": "Description of module-name-1 version 1.0.0",
                    "url": "module-name-1/1.0.0"
                },
                "1.0.1": {
                    "description": "Description of module-name-1 version 1.0.1",
                    "url": "module-name-1/1.0.1"
                }
            }
        },
        "module-name-2": {
            "latest": "2.0.0",
            "versions": {
                "2.0.0": {
                    "description": "Description of module-name-2 version 2.0.0",
                    "url": "module-name-2/2.0.0"
                }
            }
        }
    }
    ```

    *   **`module-name`**:  The unique identifier for your module (e.g., `my-custom-module`).
    *   **`latest`**:  The latest stable version of the module.
    *   **`versions`**:  An object containing version-specific information.
        *   **`version-number`**:  The semantic version of the module (e.g., `1.0.0`).
            *   **`description`**:  A brief description of this module version.
            *   **`url`**:  The path within the repository to the module's version directory (relative to the repository root).  This path is used to construct the download URL for the module's ZIP file.

3.  **Module Version Directories**: Within the `modules/` directory, create subdirectories for each module and then for each version.  For example, for `module-name-1` version `1.0.0`, you would have: `modules/module-name-1/1.0.0/`.

4.  **Module ZIP Files**:  Inside each version directory, place a ZIP file named after the version (e.g., `1.0.0.zip`). This ZIP file should contain the module's code and assets for that specific version.  **Important:** The ZIP file should contain the module files directly at the root, not within another subdirectory inside the ZIP.

5.  **Publishing Modules**: To publish a new version of your module to your registry, you can use the provided `forge.php` CLI script (or adapt it for your needs).

6.  **Registering Your Registry in Forge Engine**:  To make your Forge Engine application aware of your custom module registry, you need to add its details to the `package_manager.php` configuration file in your application.

    *   Navigate to your Forge Engine application's `config/` directory.
    *   Open or create the `package_manager.php` file.
    *   Add your registry details to the `registry` array. Here's an example `package_manager.php` configuration:

        ```php
        <?php
        return [
            "registry" => [
                [
                    "name" => "acidlake-modules", // A descriptive name for your registry
                    "url" => "[https://github.com/acidlake/acidlake-modules](https://github.com/acidlake/acidlake-modules)", // URL of your registry repository (e.g., GitHub)
                    "branch" => "main", // Branch to use for fetching module information (usually 'main' or 'master')
                ],
                // You can add more registries here, including the official one if you want to explicitly include it
            ],
            "cache_ttl" => 3600 // Cache time-to-live for module lists (in seconds)
        ];
        ```

    *   **`name`**:  A descriptive name for your registry (e.g., "my-company-modules", "community-modules"). This name is used for identification in logs and messages.
    *   **`url`**:  The base URL of your module registry repository (e.g., your GitHub repository URL).
    *   **`branch`**:  The branch of your repository where the `modules.json` and module versions are located (usually `main` or `master`).
    *   **`cache_ttl`**: (Optional) Sets the cache time-to-live in seconds for module lists fetched from this registry.

3.  **Module Version Directories**: Within the `modules/` directory, create subdirectories for each module and then for each version.  For example, for `module-name-1` version `1.0.0`, you would have: `modules/module-name-1/1.0.0/`.

4.  **Module ZIP Files**:  Inside each version directory, place a ZIP file named after the version (e.g., `1.0.0.zip`). This ZIP file should contain the module's code and assets for that specific version.  **Important:** The ZIP file should contain the module files directly at the root, not within another subdirectory inside the ZIP.

5.  **Publishing Modules**: To publish a new version of your module to your registry, you can use the provided `forge.php` CLI script (or adapt it for your needs).

## Publishing Modules to a Registry with `forge.php` CLI

This repository includes a basic CLI script `forge.php` to assist in publishing modules.  Here's how to use it:

**Prerequisites:**

*   PHP CLI must be installed and accessible in your system's PATH.
*   The `forge.php` script should be placed at the root of your module registry repository.
*   Your module code should be organized in the `modules/` directory, with versioned subdirectories (e.g., `modules/my-module/1.0.0/`).
*   Your registry repository should be initialized as a Git repository.

**Usage:**

```bash
php forge.php publish <module-name>@<version>