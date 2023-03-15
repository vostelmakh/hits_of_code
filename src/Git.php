<?php

declare(strict_types=1);

namespace Vostelmakh\HitsOfCode;

use DateTime;

class Git
{
    public function __construct(
        private $dir,
        private $exclude,
        private $author,
        private $since,
        private $before
    ) {
    }

    public function hits(): array
    {
        $version = explode(" ", shell_exec('git --version'))[2];
        if (version_compare($version, '2.0', '<')) {
            throw new \Exception("git version $version is too old, upgrade it to 2.0+");
        }

        $excludeArg = '';
        foreach ($this->exclude as $e) {
            $excludeArg .= "':(exclude,glob)$e' ";
        }

        $author = $this->author ? "--author={$this->author}" : '';

        $cmd = "cd {$this->dir} && git log --pretty=tformat: --numstat --ignore-space-change --ignore-all-space --ignore-submodules --no-color --find-copies-harder"
        . $author . " -M --diff-filter=ACDM --since={$this->since} --before={$this->before} -- . $excludeArg";

        $output = shell_exec($cmd);

        $hits = [];

        $lines = array_filter(explode("\n", $output), function($line) { return !empty(trim($line)); });
        foreach ($lines as $line) {
            $parts = explode("\t", $line);

            $additions = (int) $parts[0];
            $deletions = intval($parts[1]);

            $hits[] = new Hits(new DateTime(), $additions + $deletions);
        }

        return $hits;
    }
}
