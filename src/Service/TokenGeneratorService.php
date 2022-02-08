<?php

namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;

class TokenGeneratorService implements UniqIdentifierGeneratorInterface {

    public function generate(): string {
        $token = md5(time() . mt_rand());
        return $token;
    }
}