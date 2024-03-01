# Changelog
All notable changes to `excel-reader` will be documented in this file.

## v3.1.0 - 2024-01-01
- Remove PHP 7.4 and 8.0 support
- Add PHP 8.3 support
- Upgrade Composer dependencies
- Migrate tests to Pest

## v3.0.0 - 2022-12-13
- Composer: require `openspout/openspout:^4.9`
- PHP supported versions are `8.0`, `8.1`, `8.2`
- Remove deprecated method `ExcelReader::createFromFile`

## v2.0.0 - 2022-10-11
- Composer: remove `box/spout`, require `openspout/openspout`, update php version
- Add OpenSpout3 library
- Run test workflow: do not exclude `prefer-lowest` on `php 8.0`, add `php 8.1`
- Add upgrade guide to `README.md`

## v1.3.0 - 2021-10-22
- Add `skip` method to define how many rows should be skipped
- Add `preserveEmptyRows` method to define if empty rows should be preserved or not (compatible only with box/spout:^3.0)
- Add `version` static method to retrieve box/spout library version

## v1.2.0 - 2021-09-08
- Add `slugify` method to define settings for generating slugs for column headers

## v1.1.1 - 2021-02-23
- Fix for malformed tables

## v1.1.0 - 2021-02-23
- `ExcelReader` now requires `FileInterface` instead of `string` in `__construct`
- Deprecated `ExcelReader::createFromFile` in favor of `ExcelReader::createFromPath`
- Add `ExcelReader::createFromString` static method
- Add `FileInterface` to handle normal and temporary files

## v1.0.0 - 2021-02-19
- Initial release
