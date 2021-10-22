# Changelog
All notable changes to `excel-reader` will be documented in this file.

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
