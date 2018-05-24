<?php
namespace Helper;

class Base extends \Codeception\Module
{
    /**
     * Event hook before a test starts
     *
     * @param \Codeception\TestCase $test
     * @throws \Exception
     */
    public function _before(\Codeception\TestCase $test)
    {
        $this->test = $test;
    }
}