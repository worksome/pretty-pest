<?php

namespace Worksome\PrettyPest\Support;

class WhitespaceDetail
{
    public function __construct(
        private int $startPtr,
        private int $endPtr,
    )
    {
    }

    public function getStartPtr(): int
    {
        return $this->startPtr;
    }

    public function getEndPtr(): int
    {
        return $this->endPtr;
    }

    public function isExactlyOneLine(): bool
    {
        return $this->getEndPtr() - $this->getStartPtr() === 1;
    }
}