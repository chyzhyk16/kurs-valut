<?php

namespace App\Storage;

interface StorageInterface
{
    public function read(string $key): string;

    public function write(string $data, ?string $key = null): void;
}