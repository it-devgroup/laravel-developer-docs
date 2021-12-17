<?php

namespace ItDevgroup\LaravelDeveloperDocs\Console\Command;

use Illuminate\Console\Command;
use ItDevgroup\LaravelDeveloperDocs\Provider\DeveloperDocsServiceProvider;

/**
 * Class DeveloperDocsPublishCommand
 * @package ItDevgroup\LaravelDeveloperDocs\Console\Command
 */
class DeveloperDocsPublishCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'developer:docs:publish {--tag=* : Tag for published}';
    /**
     * @var string
     */
    protected $description = 'Publish files for developer docs package';
    /**
     * @var array
     */
    private array $files = [];
    /**
     * @var array
     */
    private array $fileTags = [
        'config',
    ];

    /**
     * @return void
     */
    public function handle()
    {
        $option = is_array($this->option('tag')) && !empty($this->option('tag')) ? $this->option('tag')[0] : '';

        $this->parsePublishedFiles();

        switch ($option) {
            case 'config':
                $this->copyConfig();
                break;
            default:
                $this->error('Not selected tag');
                break;
        }
    }

    /**
     * @return void
     */
    private function parsePublishedFiles(): void
    {
        $index = 0;
        foreach (DeveloperDocsServiceProvider::pathsToPublish(DeveloperDocsServiceProvider::class) as $k => $v) {
            $this->files[$this->fileTags[$index]] = [
                'from' => $k,
                'to' => $v,
            ];
            $index++;
        }
    }

    /**
     * @return void
     */
    private function copyConfig(): void
    {
        $this->copyFiles($this->files['config']['from'], $this->files['config']['to']);
    }

    /**
     * @param string $from
     * @param string $to
     */
    private function copyFiles(string $from, string $to): void
    {
        if (!file_exists($to)) {
            mkdir($to, 0755, true);
        }
        $from = rtrim($from, '/') . '/';
        $to = rtrim($to, '/') . '/';
        foreach (scandir($from) as $file) {
            if (!is_file($from . $file)) {
                continue;
            }

            $path = strtr(
                $to . $file,
                [
                    base_path() => ''
                ]
            );

            if (file_exists($to . $file)) {
                $this->info(
                    sprintf(
                        'File "%s" skipped',
                        $path
                    )
                );
                continue;
            }

            copy(
                $from . $file,
                $to . $file
            );

            $this->info(
                sprintf(
                    'File "%s" copied',
                    $path
                )
            );
        }
    }
}
