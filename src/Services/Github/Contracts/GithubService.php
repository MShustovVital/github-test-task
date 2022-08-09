<?php

namespace App\Services\Github\Contracts;

interface GithubService
{
    public function createRepository(string $name):mixed;

    public function removeRepository(string $name):mixed;

    public function listOfRepositories(string $username):mixed;
}