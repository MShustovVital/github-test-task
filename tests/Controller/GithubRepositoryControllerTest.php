<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GithubRepositoryControllerTest extends WebTestCase
{
	/** @test */
	public function it_can_list_github_repositories()
	{
		$client = static::createClient();
		$this->assertTrue(true);
		$client->request('GET', '/repository', ['username' => 'test']);
		$this->assertResponseIsSuccessful();
	}

	/** @test */
	public function it_can_not_list_github_repositories()
	{
		$client = static::createClient();
		$this->assertTrue(true);
		$client->request('GET', '/repository');
		$this->assertResponseStatusCodeSame(422);
	}
}
