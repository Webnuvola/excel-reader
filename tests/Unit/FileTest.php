<?php

use Webnuvola\ExcelReader\File\FileFactory;
use Webnuvola\ExcelReader\File\FileInterface;

it('can create file interface instance', function () {
    expect(FileFactory::createFromPath(__DIR__.'/../resources/financial.xlsx'))
        ->toBeInstanceOf(FileInterface::class)
        ->and(FileFactory::createFromString(financialFile(), 'xlsx'))
        ->toBeInstanceOf(FileInterface::class);
});

it('can get file info', function () {
    $file = FileFactory::createFromPath(__DIR__.'/../resources/financial.xlsx');

    expect($file->getPath())
        ->toBeReadableFile()
        ->and($file->getPath())->toBe(realpath(__DIR__.'/../resources/financial.xlsx'))
        ->and($file->getExtension())->toBe('xlsx');
});

test('temporary file is deleted', function () {
    $file = FileFactory::createFromString(financialFile(), 'xlsx');

    $path = $file->getPath();

    expect($path)->toBeReadableFile();

    unset($file);

    expect($path)->not->toBeReadableFile();
});
