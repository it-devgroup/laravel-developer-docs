<?php

namespace ItDevgroup\LaravelDeveloperDocs;

/**
 * Interface DeveloperDocsServiceInterface
 * @package ItDevgroup\LaravelDeveloperDocs
 */
interface DeveloperDocsServiceInterface
{
    /**
     * @type string
     */
    public const DEFAULT_FILESYSTEM = 'developerDocs';
    /**
     * @type string
     */
    public const INDEX_HTML_FILENAME = 'index.html';

    /**
     * @param string|null $file
     * @return string|null
     */
    public function getPathFromFile(?string $file = null): ?string;

    /**
     * @param string|null $file
     * @return array|null
     */
    public function generateBackPathFromFile(?string $file = null): ?array;

    /**
     * @param string|null $folder
     * @return array
     */
    public function generateFolderMenu(?string $folder = null): array;

    /**
     * @param string|null $folder
     * @return array
     */
    public function generateFileMenu(?string $folder = null): array;

    /**
     * @param string $file
     * @return string|null
     */
    public function getFileTitle(string $file): ?string;

    /**
     * @param string $file
     * @return string
     */
    public function getFileContent(string $file): string;

    /**
     * @param string|null $file
     * @return array|null
     */
    public function generateBreadcrumbs(?string $file = null): ?array;

    /**
     * @return string[]|null
     */
    public function generateIndexPageData(): ?array;

    /**
     * @param string|null $imagePath
     * @param bool $output
     * @return array
     */
    public function getImageData(?string $imagePath = null, bool $output = false): array;
}
