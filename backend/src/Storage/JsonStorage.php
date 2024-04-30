<?php

declare(strict_types=1);

namespace App\Storage;

use Exception;

class JsonStorage implements StorageInterface
{
    private const FILENAME = 'var/data.json';

    /**
     * @throws Exception
     */
    public function read(?string $key = null): string
    {
        if (!file_exists(self::FILENAME)) {
            $this->write('');
        }

        $jsonData = file_get_contents(self::FILENAME);
        if ($jsonData === false) {
            throw new Exception("Error reading JSON file");
        }

        if ($key) {
            $data = json_decode($jsonData, true);
            return $data[$key] ?? '';
        }

        return $jsonData;
    }

    /**
     * @throws Exception
     */
    public function write(string $data, ?string $key = null): void
    {
        if ($key) {
            $currentData = json_decode($this->read(), true);
            $currentData[$key] = $data;
            $writeData = json_encode($currentData);
        } else {
            $writeData = $data;
        }

        if (file_put_contents(self::FILENAME, $writeData) === false) {
            throw new Exception("Error writing to JSON file");
        }
    }
}