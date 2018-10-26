<?php

namespace MatthiasMullie\PathConverter\Tests;

use MatthiasMullie\PathConverter\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Converter test case.
 */
class ConverterTest extends TestCase
{
    /**
     * Test Converter, provided by dataProvider.
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
        $tests = [];

        $tests[] = [
            '../images/img.jpg',
            '/home/forkcms/frontend/core/layout/css',
            '/home/forkcms/frontend/cache/minified_css',
            '../../core/layout/images/img.jpg',
        ];

        $tests[] = [
            '../../images/icon.gif',
            '/css/imports/',
            '/css/',
            '../images/icon.gif',
        ];

        // absolute path - doesn't make sense :)
        $tests[] = [
            '/home/username/file.txt',
            '/css/imports',
            '/css',
            '/home/username/file.txt',
        ];

        $tests[] = [
            'image.jpg',
            'tests/css/sample/convert_relative_path/source',
            'tests/css/sample/convert_relative_path/source',
            'image.jpg',
        ];

        $tests[] = [
            '../images/img.jpg',
            'C:/My Documents/forkcms/frontend/core/layout/css',
            'C:/My Documents/forkcms/frontend/cache/minified_css',
            '../../core/layout/images/img.jpg',
        ];

        // https://github.com/forkcms/forkcms/issues/1186
        $tests[] = [
            '../images/img.jpg',
            '/Users/mathias/Documents/— Projecten/PROJECT_NAAM/Web/src/Backend/Core/Layout/Css/',
            '/Users/mathias/Documents/— Projecten/PROJECT_NAAM/Web/src/Backend/Cache/MinifiedCss/',
            '../../Core/Layout/images/img.jpg',
        ];

        // https://github.com/matthiasmullie/path-converter/issues/1
        $tests[] = [
            'image.jpg',
            '/var/www/mysite.com/assets/some_random_folder_name/',
            '/var/www/mysite.com/assets/some_other_random_folder_name/',
            '../some_random_folder_name/image.jpg',
        ];

        $tests[] = [
            'image.jpg',
            '/var/www/',
            '/',
            'var/www/image.jpg',
        ];

        $tests[] = [
            'image.jpg',
            '/',
            '/var/www/',
            '../../image.jpg',
        ];

        // https://github.com/matthiasmullie/path-converter/issues/4
        $tests[] = [
            'rotissanser-webfont.eot',
            'typo3temp/assets/compressed/../../../typo3conf/ext/user_merkl/Resources/Public/Fonts/webfontkit-rotissanser/stylesheet.css',
            'typo3temp/assets/compressed/merged-abce7d875ee92a78bd5e1871ef774fbe.css',
            '../../../typo3conf/ext/user_merkl/Resources/Public/Fonts/webfontkit-rotissanser/rotissanser-webfont.eot',
        ];

        return $tests;
    }
}
