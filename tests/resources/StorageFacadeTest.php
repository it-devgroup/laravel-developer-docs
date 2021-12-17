<?php

namespace ItDevgroup\LaravelDeveloperDocs\Test\Resource;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class StorageFacadeTest
 * @package ItDevgroup\LaravelDeveloperDocs\Test\Resource
 */
class StorageFacadeTest implements Filesystem
{
    /**
     * @param string $path
     * @return string|null
     */
    public function get($path)
    {
        return file_get_contents(
            sprintf(
                '%s/docs/%s',
                __DIR__,
                $path
            )
        );
    }

    /**
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists(
            sprintf(
                '%s/docs/%s',
                __DIR__,
                $path
            )
        );
    }

    public function readStream($path)
    {
        // TODO: Implement readStream() method.
    }

    public function put($path, $contents, $options = [])
    {
        // TODO: Implement put() method.
    }

    public function writeStream($path, $resource, array $options = [])
    {
        // TODO: Implement writeStream() method.
    }

    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }

    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }

    public function prepend($path, $data)
    {
        // TODO: Implement prepend() method.
    }

    public function append($path, $data)
    {
        // TODO: Implement append() method.
    }

    public function delete($paths)
    {
        // TODO: Implement delete() method.
    }

    public function copy($from, $to)
    {
        // TODO: Implement copy() method.
    }

    public function move($from, $to)
    {
        // TODO: Implement move() method.
    }

    /**
     * @param string $path
     * @return false|int
     */
    public function size($path)
    {
        return filesize(
            sprintf(
                '%s/docs/%s',
                __DIR__,
                $path
            )
        );
    }

    public function lastModified($path)
    {
        // TODO: Implement lastModified() method.
    }

    public function files($directory = null, $recursive = false)
    {
        $originDirectory = $directory;
        $directory = rtrim(
            sprintf(
                '%s/docs/%s',
                __DIR__,
                $directory
            ),
            '/'
        );

        $data = [];

        foreach (array_diff(scandir($directory), ['.', '..']) as $file) {
            $path = sprintf(
                '%s/%s',
                $directory,
                $file
            );

            if (is_dir($path)) {
                continue;
            }

            if ($originDirectory) {
                $data[] = sprintf(
                    '%s/%s',
                    $originDirectory,
                    $file
                );
            } else {
                $data[] = $file;
            }
        }

        return $data;
    }

    public function allFiles($directory = null)
    {
        // TODO: Implement allFiles() method.
    }

    /**
     * @param string|null $directory
     * @param bool $recursive
     * @return array
     */
    public function directories($directory = null, $recursive = false)
    {
        $originDirectory = $directory;
        $directory = rtrim(
            sprintf(
                '%s/docs/%s',
                __DIR__,
                $directory
            ),
            '/'
        );

        $data = [];

        foreach (array_diff(scandir($directory), ['.', '..']) as $dir) {
            $path = sprintf(
                '%s/%s',
                $directory,
                $dir
            );

            if (!is_dir($path)) {
                continue;
            }

            if ($originDirectory) {
                $data[] = sprintf(
                    '%s/%s',
                    $originDirectory,
                    $dir
                );
            } else {
                $data[] = $dir;
            }
        }

        return $data;
    }

    public function allDirectories($directory = null)
    {
        // TODO: Implement allDirectories() method.
    }

    public function makeDirectory($path)
    {
        // TODO: Implement makeDirectory() method.
    }

    public function deleteDirectory($directory)
    {
        // TODO: Implement deleteDirectory() method.
    }
}
