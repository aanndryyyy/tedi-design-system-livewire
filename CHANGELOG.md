# Changelog

All notable changes to this project are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed

- **npm:** `@tedi-design-system/core` 6.5.0 → 6.8.1, `sass` 1.103.1,
  `replace-in-file` 9.0.0
- **Composer (library):** `illuminate/support` and `illuminate/view` now allow
  Laravel 13 (`^11.0|^12.0|^13.0`); dev toolchain bumped to Livewire 4.4,
  Orchestra Testbench 11, PHPUnit 13
- **Composer (storybook):** Laravel 13.29.0, Livewire 4.4.2

### Fixed

- Migrated vendored SCSS token references for `@tedi-design-system/core` ≥6.5.1
  and ≥6.7.0, per the upstream [core release notes](https://github.com/TEDI-Design-System/core/releases):
  - `--tooltip-background` / `--tooltip-text` → `--tooltip-primary-background` /
    `--tooltip-primary-text` (`tooltip.component.scss`)
  - `--general-selected-border-width` → `--general-border-width-selected`
    (`filter.component.scss`, `filter-group.component.scss`)
  - `--table-of-contents-padding-level-1` →
    `--table-of-contents-padding-left-level-1`
    (`table-of-contents-item.component.scss`)
