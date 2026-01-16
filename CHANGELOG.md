# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-01-16

### Added

- Initial release
- Operation log middleware for automatic request logging
- Log management interface with filtering and search
- Multi-language support (English, Simplified Chinese, Traditional Chinese)
- Configurable excluded routes
- Configurable HTTP methods filter
- Sensitive fields masking (password, etc.)
- Database migration for `admin_operation_log` table

### Changed

- Updated package namespace to `dcat-x`
- Minimum PHP version requirement: 8.2
- Minimum dcat-x/laravel-admin version: 1.0

### Developer Experience

- Added Laravel Pint for code formatting
- Added Pest for testing
- Added GitHub Actions CI workflow
