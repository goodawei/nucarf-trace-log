<?php

namespace Nucarf\TraceLogTests;

use Nucarf\TraceLog\TraceLog;
use Nucarf\TraceLog\TraceLogException;
use PHPUnit\Framework\TestCase;

class ParseCallableNameTest extends TestCase
{
    public function testParse()
    {
        self::assertSame(
            'strval',
            ParseCallableNameTestCass::parseCallableName('strval')
        );

        self::assertSame(
            'Nucarf\TraceLogTests\ParseCallableNameTestCass::parseCallableName',
            ParseCallableNameTestCass::parseCallableName(
                'Nucarf\TraceLogTests\ParseCallableNameTestCass::parseCallableName'
            )
        );

        self::assertSame(
            'Nucarf\TraceLogTests\ParseCallableNameTestCass::parseCallableName',
            ParseCallableNameTestCass::parseCallableName(
                ['Nucarf\TraceLogTests\ParseCallableNameTestCass', 'parseCallableName']
            )
        );

        self::assertSame(
            'Nucarf\TraceLogTests\ParseCallableNameTestCass::parseCallableName',
            ParseCallableNameTestCass::parseCallableName(
                [new ParseCallableNameTestCass, 'parseCallableName']
            )
        );

        try {
            ParseCallableNameTestCass::parseCallableName(function () {
            });
            self::assertTrue(false, 'this line should not run.');
        } catch (\Exception $e) {
            self::assertTrue($e instanceof TraceLogException);
        }
    }
}

class ParseCallableNameTestCass extends TraceLog
{
    public static function parseCallableName(callable $callable)
    {
        return parent::parseCallableName($callable);
    }
}
