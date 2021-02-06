<?php


namespace src\Integration;

/**
 * Interface DataProvideable
 *
 * @package src\Integration
 */
interface DataProvideable
{
    public function get(array $request);
}
