<?php

namespace Importer\Contracts;

use Importer\DTO\ParsedDataDTO;

interface ParserContract
{
    /**
     * Set Key->Index Mappings
     */
    public function setMapping(array $mapping): void;

    /**
     * Parse the given file by filepath and return parsed data
     */
    public function parse(string $filepath): ParsedDataDTO;

}