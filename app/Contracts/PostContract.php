<?php

namespace App\Contracts;

interface PostContract
{
    public function getAll(int $page, string $sort = 'desc');

    public function getById(int $id);

    public function flush(string $key = ''): void;

    public function regenerate(): void;
}
