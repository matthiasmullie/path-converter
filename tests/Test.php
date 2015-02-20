<?php

use MatthiasMullie\PathConverter\Converter;

/**
 * Converter test case.
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Converter, provided by dataProvider
     *
     * @test
     * @dataProvider dataProvider
     */
    public function convert($relative, $from, $to, $expected)
    {
        $converter = new Converter($from, $to);
        $result = $converter->convert($relative);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array [relative, from, to, expected result]
     */
    public function dataProvider()
    {
        $tests = array();

        $tests[] = array(
            '../images/img.gif',
            '/home/forkcms/frontend/core/layout/css',
            '/home/forkcms/frontend/cache/minified_css',
            '../../core/layout/images/img.gif',
        );

        $tests[] = array(
            '../../images/icon.jpg',
            '/css/imports/icons.css',
            '/css/minified.css',
            '../images/icon.jpg',
        );

        // absolute path - doesn't make sense :)
        $tests[] = array(
            '/home/username/file.jpg',
            '/css/imports/icons.css',
            '/css/minified.css',
            '/home/username/file.jpg',
        );

        return $tests;
    }
}
