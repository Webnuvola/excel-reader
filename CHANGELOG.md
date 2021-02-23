# Changelog
All notable changes to `excel-reader` will be documented in this file.

## v1.1.1 - 2021-02-23
- Fix for malformed tables

## v1.1.0 - 2021-02-23
- `ExcelReader` now requires `FileInterface` instead of `string` in `__construct`
- Deprecated `ExcelReader::createFromFile` in favor of `ExcelReader::createFromPath`
- Add `ExcelReader::createFromString` static method
- Add `FileInterface` to handle normal and temporary files

## v1.0.0 - 2021-02-19
- Initial release
