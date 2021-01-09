<?php

namespace GX2CMSJoomla\Utils;

class GX2CMSDate
{
    private $timestamp = 0;

    public function __construct(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp(): int {return $this->timestamp;}

    public function getDateYMD(): string {return date('Y-m-d', $this->timestamp);}

    public function getDateYMDIHS(): string {return date('Y-m-d i:h:s', $this->timestamp);}

    public function __toString() {return (string)$this->timestamp;}
}