<?php

function showReadMore($text): string
{
    return \Illuminate\Support\Str::limit($text, 100, '...');
}

function getSortByRoute(): string
{
    $currentUrl = url()->current();
    $page = request()->query('page') ? request('page') : 1;

    return "$currentUrl?page=$page";
}
