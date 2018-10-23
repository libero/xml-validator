#!/usr/bin/env php
<?php

declare(strict_types=1);

use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__.'/../vendor/autoload.php';

$filesystem = new Filesystem();

$target = __DIR__.'/../lib/schematron';

$base = 'https://github.com/Schematron/schematron/raw/master';
$files = [
    'LICENSE',
    'trunk/schematron/code/iso_schematron_skeleton_for_xslt1.xsl',
    'trunk/schematron/code/iso_svrl_for_xslt1.xsl',
    'trunk/converters/code/ToSchematron/ExtractSchFromRNG.xsl',
    'trunk/converters/code/ToSchematron/ExtractSchFromXSD.xsl',
];

$filesystem->remove(new FilesystemIterator($target));

foreach ($files as $file) {
    $filesystem->copy("{$base}/${file}", "{$target}/".basename($file));
}
