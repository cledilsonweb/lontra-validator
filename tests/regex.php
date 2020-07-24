<?php

use PHPUnit\Framework\TestCase;

/**
 * Class regex to test regex before use
 */
class regex extends TestCase
{

    /**
     * @return void
     */
    public function testWordCountRegex()
    {
        $value = 'lorem ipis teste@teste.com ôxe oxênte casa@teste.com you\'re';

        $this->assertEquals(6, preg_match_all('/([a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+)|(\b\w*[-\']\w*\b)|(\w+)/u', $value));
    }
}
