#!/usr/bin/env php
<?php

declare(strict_types=1);

use GitWrapper\GitWrapper;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__.'/../vendor/autoload.php';

$filesystem = new Filesystem();
$git = new GitWrapper();
$git->streamOutput();

$repoUri = 'https://github.com/Schematron/schematron';
$repoDir = __DIR__.'/../tmp/schematron';
$patchesDir = __DIR__.'/../tmp/patches';
$targetDir = __DIR__.'/../lib/schematron';
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
    $filesystem->mkdir($repoDir);
    $repo = $git->cloneRepository($repoUri, $repoDir, ['depth' => 1]);
} else {
    $repo = $git->workingCopy($repoDir);
    $repo->fetch('origin', 'master');
    $repo->reset(['hard' => true], 'origin/master');
}

foreach ($patches as $patch) {
    $patchTarget = "${patchesDir}/".md5($patch);

    echo "Downloading {$patch} to {$patchTarget}\n";
    $filesystem->copy($patch, $patchTarget);
    $repo->apply($patchTarget, ['verbose' => true]);
}

$filesystem->remove(new FilesystemIterator($targetDir));

foreach ($files as $file) {
    $origin = "${repoDir}/${file}";
    $target = "${targetDir}/".basename($file);

    echo "Copying {$origin} to {$target}\n";
    $filesystem->copy($origin, $target);
}
