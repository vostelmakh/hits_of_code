<?php

declare(strict_types=1);

namespace Vostelmakh\HitsOfCode;

use \InvalidArgumentException;

class Base
{
    public function __construct(
        private string $dir,
        private array $exclude,
        private string $author,
        private string $format,
        private string $since,
        private string $before
    ) {
    }

    public function report(): string
    {
        if (file_exists($this->dir . '/.git')) {
            $repo = new Git($this->dir, $this->exclude, $this->author, $this->since, $this->before);
        } else {
            throw new \Exception("There is NO git repositories in {$this->dir}");
        }

        $count = array_reduce($repo->hits(), function($sum, $hit) { return $sum + $hit->getTotal(); }, 0);

        return match ($this->format) {
            'xml' => "<hoc><total>{$count}</total></hoc>",
            'json' => "{\"total\":{$count}}",
            'text' => "Total Hits-of-Code: {$count}",
            'int' => (string) $count,
            default => throw new InvalidArgumentException('Only "text|xml|json|int" formats are supported now'),
        };
    }
}
