<?php

namespace MatthiasMullie\PathConverter\Tests;

use MatthiasMullie\PathConverter\NoConverter;
use PHPUnit\Framework\TestCase;

/**
 * Converter test case.
 */
class NoConverterTest extends TestCase
{
    /**
     * Test Converter, provided by dataProvider.
     *
     * @test
     * @dataProvider dataProvider
     */
    public function convert($relative, $expected)
    {
        $converter = new NoConverter();
        $result = $converter->convert($relative);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array [relative, from, to, expected result]
     */
    public function dataProvider()
    {
        $tests = [];

        $tests[] = [
            '../images/img.jpg',
            '../images/img.jpg',
        ];

        $tests[] = [
            '../../images/icon.gif',
            '../../images/icon.gif',
        ];

        // absolute path - doesn't make sense :)
        $tests[] = [
            '/home/username/file.txt',
            '/home/username/file.txt',
        ];

        $tests[] = [
            'image.jpg',
            'image.jpg',
        ];

        $tests[] = [
            '../images/img.jpg',
            '../images/img.jpg',
        ];

        // https://github.com/forkcms/forkcms/issues/1186
        $tests[] = [
            '../images/img.jpg',
            '../images/img.jpg',
        ];

        // https://github.com/matthiasmullie/path-converter/issues/1
        $tests[] = [
            'image.jpg',
            'image.jpg',
        ];

        return $tests;
    }
}
