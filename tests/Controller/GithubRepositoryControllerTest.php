<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GithubRepositoryControllerTest extends WebTestCase
{
	/** @test */
	public function it_can_list_github_repositories()
	{
		$client = static::createClient();
		$client->request('GET', '/github/repository', ['username' => 'test']);
		$this->assertResponseIsSuccessful();
	}

	/** @test */
	public function it_validation_fails_when_username_more_than_eighty_symbols()
	{
		$client = static::createClient();
		$username = str_pad('', 90);
		$client->request('GET', '/github/repository', ['username' => $username]);
        $this->assertResponseStatusCodeSame(422);
	}

    /** @test */
    public function it_validation_fails_when_username_less_than_two_symbols()
    {
        $client = static::createClient();
        $username = str_pad('', 2);
        $client->request('GET', '/github/repository', ['username' => $username]);
        $this->assertResponseStatusCodeSame(422);
    }
}
