<?php
namespace MatthiasMullie\PathConverter;

/**
 * Convert paths relative from 1 file to another.
 *
 * E.g.
 *     ../../images/icon.jpg relative to /css/imports/icons.css
 * becomes
 *     ../images/icon.jpg relative to /css/minified.css
 *
 * Please report bugs on https://github.com/matthiasmullie/path-converter/issues
 *
 * @author Matthias Mullie <pathconverter@mullie.eu>
 *
 * @copyright Copyright (c) 2015, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
class Converter
{
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @param string $from The original base path.
     * @param string $to   The new base path.
     */
    public function __construct($from, $to)
    {
        // make sure we're dealing with directories
        $from = @is_file($from) ? dirname($from) : $from;
        $to = @is_file($to) ? dirname($to) : $to;

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Normalize path.
     *
     * @param  string $path
     * @return string
     */
    protected function normalize($path)
    {
        // deal with different operating systems' directory structure
        $path = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/');

        /*
         * Example:
         *     /home/forkcms/frontend/cache/compiled_templates/../../core/layout/css/../images/img.gif
         * to
         *     /home/forkcms/frontend/core/layout/images/img.gif
         */
        do {
            $path = preg_replace('/[^\/]+(?<!\.\.)\/\.\.\//', '', $path, -1, $count);
        } while ($count);

        return $path;
    }

    /**
     * Figure out the shared path of 2 locations.
     *
     * Example:
     *     /home/forkcms/frontend/core/layout/images/img.gif
     * and
     *     /home/forkcms/frontend/cache/minified_css
     * share
     *     /home/forkcms/frontend
     *
     * @param  string $path1
     * @param  string $path2
     * @return string
     */
    protected function shared($path1, $path2)
    {
        // $path could theoretically be empty (e.g. no path is given), in which
        // case it shouldn't expand to array(''), which would compare to one's
        // root /
        $path1 = $path1 ? explode('/', $path1) : array();
        $path2 = $path2 ? explode('/', $path2) : array();

        $shared = array();

        // compare paths & strip identical ancestors
        foreach ($path1 as $i => $chunk) {
            if (isset($path2[$i]) && $path1[$i] == $path2[$i]) {
                $shared[] = $chunk;
            } else {
                break;
            }
        }

        return implode('/', $shared).'/';
    }

    /**
     * Convert paths relative from 1 file to another.
     *
     * E.g.
     *     ../images/img.gif relative to /home/forkcms/frontend/core/layout/css
     * should become:
     *     ../../core/layout/images/img.gif relative to
     *     /home/forkcms/frontend/cache/minified_css
     *
     * @param  string $path The relative path that needs to be converted.
     * @return string The new relative path.
     */
    public function convert($path)
    {
        $path = $this->normalize($path);
        // if we're not dealing with a relative path, just return absolute
        if (strpos($path, '/') === 0) {
            return $path;
        }

        // normalize paths
        $path = $this->normalize($this->from.'/'.$path);
        $to = $this->normalize($this->to);

        // strip shared ancestor paths
        $shared = $this->shared($path, $to);
        $path = substr($path, mb_strlen($shared));
        $to = substr($to, mb_strlen($shared));

        // add .. for every directory that needs to be traversed to new path
        $to = str_repeat('../', substr_count($to, '/') + 1);

        return $to.$path;
    }
}
