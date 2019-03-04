#!/usr/bin/env php
<?php

declare(strict_types=1);

use Gitonomy\Git;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__.'/../vendor/autoload.php';

$filesystem = new Filesystem();

$repo = 'https://github.com/Schematron/schematron';
$repoDir = __DIR__.'/../tmp/schematron';
$patchesDir = __DIR__.'/../tmp/patches';
$target = __DIR__.'/../lib/schematron';
$patches = [
    'https://github.com/Schematron/schematron/pull/81.patch',
];
$files = [
    'LICENSE',
    'trunk/schematron/code/iso_schematron_skeleton_for_xslt1.xsl',
    'trunk/schematron/code/iso_svrl_for_xslt1.xsl',
    'trunk/converters/code/ToSchematron/ExtractSchFromRNG.xsl',
    'trunk/converters/code/ToSchematron/ExtractSchFromXSD.xsl',
];

if (!$filesystem->exists($repoDir)) {
    $filesystem->mkdir(dirname($repoDir));
    Git\Admin::cloneTo($repoDir, $repo, false);
}

$repository = new Git\Repository($repoDir);

$repository->run('fetch', ['origin', 'master']);
$repository->run('reset', ['--hard', 'origin/master']);

foreach ($patches as $patch) {
    $patchTarget = "${patchesDir}/".md5($patch);

    $filesystem->copy($patch, $patchTarget);
    $repository->run('am', [$patchTarget]);
}

$filesystem->remove(new FilesystemIterator($target));

foreach ($files as $file) {
    $filesystem->copy("${repoDir}/${file}", "${target}/".basename($file));
}
