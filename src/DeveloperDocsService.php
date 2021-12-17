<?php

namespace ItDevgroup\LaravelDeveloperDocs;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * Class DeveloperDocsService
 * @package ItDevgroup\LaravelDeveloperDocs
 */
class DeveloperDocsService implements DeveloperDocsServiceInterface
{
    /**
     * @var Filesystem
     */
    private Filesystem $storage;
    /**
     * @var string
     */
    private string $routePrefix;
    /**
     * @var string
     */
    private string $filesFolder;

    /**
     * DeveloperDocsService constructor.
     */
    public function __construct()
    {
        $this->storage = Storage::disk(self::DEFAULT_FILESYSTEM);
        $this->routePrefix = Config::get('developer_docs.route_prefix');
        $this->filesFolder = Config::get('developer_docs.files_folder');
    }

    /**
     * @param string|null $file
     * @return string|null
     */
    public function getPathFromFile(?string $file = null): ?string
    {
        if (!$file) {
            return null;
        }

        $partials = explode('/', $file);

        if (!count($partials)) {
            return null;
        }

        $partials = array_slice($partials, 0, -1);

        return implode('/', $partials);
    }

    /**
     * @param string|null $file
     * @return array|null
     */
    public function generateBackPathFromFile(?string $file = null): ?array
    {
        if (!$file) {
            return null;
        }

        $partials = explode('/', $file);

        if (count($partials) <= 1) {
            return null;
        }

        $partials = array_slice($partials, 0, -2);

        $path = implode('/', $partials);

        $file = $this->generateFilePath(
            self::INDEX_HTML_FILENAME,
            $path
        );

        if (!$title = $this->fileTitle($file)) {
            return null;
        }

        return $this->responseFileFormat($file, $title);
    }

    /**
     * @param string|null $folder
     * @return array
     */
    public function generateFolderMenu(?string $folder = null): array
    {
        $directories = $this->directories($folder);

        $data = [];

        foreach ($directories as $dir) {
            $file = $this->generateFilePath(
                self::INDEX_HTML_FILENAME,
                $dir
            );

            if (!$title = $this->fileTitle($file)) {
                continue;
            }

            $data[] = $this->responseFileFormat($file, $title);
        }

        return $data;
    }

    /**
     * @param string|null $folder
     * @return array
     */
    public function generateFileMenu(?string $folder = null): array
    {
        $files = $this->files($folder);

        $data = [];

        foreach ($files as $file) {
            $file = $this->generateFilePath($file);

            if (!$title = $this->fileTitle($file)) {
                continue;
            }

            $data[] = $this->responseFileFormat($file, $title);
        }

        return $data;
    }

    /**
     * @param string $file
     * @return string
     * @throws Exception
     */
    public function getFileContent(string $file): string
    {
        $content = $this->file($file);

        return $this->parseContent($content);
    }

    /**
     * @param string $file
     * @return string|null
     */
    public function getFileTitle(string $file): ?string
    {
        return $this->fileTitle($file);
    }

    /**
     * @param string|null $file
     * @return array
     */
    public function generateBreadcrumbs(?string $file = null): array
    {
        if (!$file || $file == self::INDEX_HTML_FILENAME) {
            return [];
        }

        $partials = explode('/', $file);

        $data = [];
        $prevFile = null;
        while (count($partials)) {
            $file = implode('/', $partials);
            $partials = array_slice($partials, 0, -1);

            if (substr($file, -5) != '.html') {
                $file = sprintf(
                    '%s/%s',
                    $file,
                    self::INDEX_HTML_FILENAME
                );
            }

            if ($prevFile && $prevFile == $file) {
                continue;
            }

            $prevFile = $file;

            if (!$title = $this->fileTitle($file)) {
                continue;
            }

            $data[] = $this->responseFileFormat($file, $title);
        }

        if ($indexFile = $this->generateIndexPageData()) {
            $data[] = $indexFile;
        }

        return array_reverse($data);
    }

    /**
     * @return string[]|null
     */
    public function generateIndexPageData(): ?array
    {
        $file = self::INDEX_HTML_FILENAME;

        if (!$title = $this->fileTitle($file)) {
            return null;
        }

        return $this->responseFileFormat($file, $title);
    }

    /**
     * @param string|null $imagePath
     * @return array
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getImageData(?string $imagePath = null, bool $output = false): array
    {
        $data = [
            'headers' => [],
            'file' => null
        ];

        $imagePath = ltrim($imagePath, '/');

        if (!$imagePath || !$this->storage->exists($imagePath)) {
            return $data;
        }

        $file = sprintf(
            '%s/%s',
            $this->filesFolder,
            $imagePath
        );

        $data['file'] = $file;
        $data['headers'] = [
            'Content-Type' => $this->mimeType($file),
            'Content-Length' => $this->storage->size($imagePath)
        ];

        if ($output) {
            $this->imageOutput($data);
        }

        return $data;
    }

    /**
     * @param string|null $folder
     * @return array
     */
    private function directories(?string $folder = null): array
    {
        return $this->storage->directories($folder);
    }

    /**
     * @param string|null $folder
     * @return array
     */
    private function files(?string $folder = null): array
    {
        return $this->storage->files($folder);
    }

    /**
     * @param string $file
     * @return string
     * @throws Exception
     */
    private function file(string $file): string
    {
        return trim($this->storage->get($file));
    }

    /**
     * @param string $file
     * @param string|null $dir
     * @return string
     */
    private function generateFilePath(string $file, ?string $dir = null): string
    {
        if ($dir) {
            return sprintf(
                '%s/%s',
                $dir,
                $file
            );
        }

        return $file;
    }

    /**
     * @param string $content
     * @return bool
     */
    private function checkTitle(string &$content): bool
    {
        return strpos($content, '<title>') === 0;
    }

    /**
     * @param string $content
     * @return string|null
     */
    private function titleFromContent(string &$content): ?string
    {
        preg_match('/<title>(.+)<\/title>/i', $content, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $file
     * @return string|null
     */
    private function fileTitle(string $file): ?string
    {
        try {
            $content = $this->file($file);
        } catch (Exception $e) {
            return null;
        }

        if (!$content || !$this->checkTitle($content)) {
            return null;
        }

        if (!$title = $this->titleFromContent($content)) {
            return null;
        }

        unset($content);

        return $title;
    }

    /**
     * @param string $content
     * @return string|null
     */
    private function parseContent(string &$content): ?string
    {
        $content = trim(
            preg_replace(
                '/<title>(.+)<\/title>/i',
                '',
                $content
            )
        );
        $route = sprintf(
            '/%s/image?path=',
            $this->routePrefix
        );
        $content = preg_replace(
            '/<img(.+)src=["\']((?!(http:|https:|\/\/)).+?)["\']/',
            sprintf('<img$1src="%s$2"', $route),
            $content
        );
        $content = preg_replace(
            '/<a(.+)href=["\']((?!(http:|https:|\/\/)).+?)["\']/',
            '<a$1href="#$2"',
            $content
        );

        return $content;
    }

    /**
     * @param string $file
     * @param string $title
     * @return string[]
     */
    private function responseFileFormat(string $file, string $title): array
    {
        return [
            'path' => $file,
            'title' => $title
        ];
    }

    /**
     * @param string $fileFullPath
     * @return string|null
     */
    private function mimeType(string $fileFullPath): ?string
    {
        return mime_content_type($fileFullPath) ?: null;
    }

    /**
     * @param array $data
     */
    private function imageOutput(array $data): void
    {
        foreach ($data['headers'] as $key => $value) {
            header(
                sprintf(
                    '%s: %s',
                    $key,
                    $value
                )
            );
        }

        readfile($data['file']);
    }
}
