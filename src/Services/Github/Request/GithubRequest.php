<?php

namespace App\Services\Github\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class GithubRequest extends BaseRequest
{

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    protected $username;

}