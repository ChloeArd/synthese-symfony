<?php
namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;

class FilenameGenerator implements UniqIdentifierGeneratorInterface {

    /**
     * Return a fake uniq filename
     * @return string
     */
    public function generate(): string {
        return uniqid() . ".png";
    }
}