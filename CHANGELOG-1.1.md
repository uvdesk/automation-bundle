CHANGELOG for 1.1.x
===================

This changelog references any relevant changes introduced in 1.1 minor versions.

* 1.1.4 (2025-09-08)
    * Filter for prepared response added on ticket view.
    * Workflows related conditions updates.
    * Update dashboard and search bar svg icon of workflows
    * Code Refactoring
    * Improved codes and removed unused methods.
    * Readme.md file updates.

* 1.1.3 (2024-12-19)
   * License and support email address update.
   * Round robin ticket assignments update.
   * Code Refactoring.

* 1.1.2 (2023-06-12)
    * Update: Dropped dependency on uvdesk/composer-plugin in support of symfony/flex
    * Update: Change entity PreparedResponse::description param to type text from string
    * Bug #50: Error loading ticket status and priority options in workflow conditions (Komal-sharma-2712)

* 1.1.1 (2022-09-13)
    * Bug Fixes: Entity reference updates and other miscellaneous bug fixes

* 1.1.0 (2022-03-23)
    * Feature: Improved compatibility with PHP 8 and Symfony 5 components
    * PR #44: Use *Doctrine\ORM\EntityManagerInterface* to inject dependency to Doctrine's entity manager (WebmaticMerseburg)
