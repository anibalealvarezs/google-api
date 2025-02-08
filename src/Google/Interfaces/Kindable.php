<?php

namespace Anibalealvarezs\GoogleApi\Google\Interfaces;

interface Kindable
{
    /**
     * @param array $properties
     */
    public function keepOneOfKind(array $properties): void;
}
