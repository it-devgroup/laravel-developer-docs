<?php

namespace ItDevgroup\LaravelDeveloperDocs\Test;

/**
 * Class ServiceTest
 * @package ItDevgroup\LaravelDeveloperDocs\Test
 */
class ServiceTest extends TestCase
{
    /**
     * @test
     */
    public function serviceTestGetPathFromFileResult()
    {
        $res = $this->service->getPathFromFile('fail.html');
        $this->assertEquals(
            $res,
            ''
        );

        $res = $this->service->getPathFromFile('index.html');
        $this->assertEquals(
            $res,
            ''
        );

        $res = $this->service->getPathFromFile('part1/index.html');
        $this->assertEquals(
            $res,
            'part1'
        );

        $res = $this->service->getPathFromFile('part1/subpart1/page.html');
        $this->assertEquals(
            $res,
            'part1/subpart1'
        );
    }

    /**
     * @test
     */
    public function serviceTestGenerateBackPathFromFileResult()
    {
        $res = $this->service->generateBackPathFromFile('');
        $this->assertNull($res);

        $res = $this->service->generateBackPathFromFile('fail.html');
        $this->assertNull($res);

        $res = $this->service->generateBackPathFromFile('index.html');
        $this->assertNull($res);

        $res = $this->service->generateBackPathFromFile('part1/index.html');
        $this->assertEquals(
            $res,
            [
                'path' => 'index.html',
                'title' => 'Title index file',
            ]
        );

        $res = $this->service->generateBackPathFromFile('part1/part1-1/index.html');
        $this->assertEquals(
            $res,
            [
                'path' => 'part1/index.html',
                'title' => 'Part 1 title',
            ]
        );

        $res = $this->service->generateBackPathFromFile('part1/part1-1/part1-1-text.html');
        $this->assertEquals(
            $res,
            [
                'path' => 'part1/index.html',
                'title' => 'Part 1 title',
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestGenerateFolderMenuResult()
    {
        $res = $this->service->generateFolderMenu('');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'part1/index.html',
                    'title' => 'Part 1 title',
                ],
                [
                    'path' => 'part2/index.html',
                    'title' => 'Part 2 title',
                ]
            ]
        );

        $res = $this->service->generateFolderMenu('part1');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'part1/part1-1/index.html',
                    'title' => 'Part 1-1 title',
                ]
            ]
        );

        $res = $this->service->generateFolderMenu('part2');
        $this->assertEquals(
            $res,
            []
        );
    }

    /**
     * @test
     */
    public function serviceTestGenerateFileMenuResult()
    {
        $res = $this->service->generateFileMenu('');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'index.html',
                    'title' => 'Title index file',
                ],
                [
                    'path' => 'main.html',
                    'title' => 'Title main file',
                ]
            ]
        );

        $res = $this->service->generateFileMenu('part1');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'part1/index.html',
                    'title' => 'Part 1 title',
                ],
                [
                    'path' => 'part1/part1-text.html',
                    'title' => 'Part 1 text title',
                ]
            ]
        );

        $res = $this->service->generateFileMenu('part2');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'part2/index.html',
                    'title' => 'Part 2 title',
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestGetFileContentResult()
    {
        $res = $this->service->getFileContent('index.html');
        $this->assertEquals(
            $res,
            '<p>text</p>
<img src="/developer-docs/image?path=image/test.jpg">
<img src="/developer-docs/image?path=/image/test.jpg">
<img src="//localhost/image/test.jpg">
<img src="http://localhost/image/test.jpg">
<img src="https://localhost/image/test.jpg">
<a href="#page.index">Link</a>
<a href="#part1/index.html">Link</a>
<a href="#/part1/index.html">Link</a>
<a href="//localhost/page">Link</a>
<a href="http://localhost/page1">Link</a>
<a href="https://localhost/page2">Link</a>'
        );

        $res = $this->service->getFileContent('part1/part1-text.html');
        $this->assertEquals(
            $res,
            '<p>text part 1 text</p>'
        );
    }

    /**
     * @test
     */
    public function serviceTestGetFileContentWithCustomRoutePrefixResult()
    {
        $this->setServiceProperty('routePrefix', 'route-developer-docs');

        $res = $this->service->getFileContent('index.html');
        $this->assertEquals(
            $res,
            '<p>text</p>
<img src="/route-developer-docs/image?path=image/test.jpg">
<img src="/route-developer-docs/image?path=/image/test.jpg">
<img src="//localhost/image/test.jpg">
<img src="http://localhost/image/test.jpg">
<img src="https://localhost/image/test.jpg">
<a href="#page.index">Link</a>
<a href="#part1/index.html">Link</a>
<a href="#/part1/index.html">Link</a>
<a href="//localhost/page">Link</a>
<a href="http://localhost/page1">Link</a>
<a href="https://localhost/page2">Link</a>'
        );
    }

    /**
     * @test
     */
    public function serviceTestGetFileTitleResult()
    {
        $res = $this->service->getFileTitle('index.html');
        $this->assertEquals($res, 'Title index file');

        $res = $this->service->getFileTitle('main.html');
        $this->assertEquals($res, 'Title main file');

        $res = $this->service->getFileTitle('without_title.html');
        $this->assertNull($res);

        $res = $this->service->getFileTitle('empty.html');
        $this->assertNull($res);

        $res = $this->service->getFileTitle('part1/index.html');
        $this->assertEquals($res, 'Part 1 title');
    }

    /**
     * @test
     */
    public function serviceTestGenerateBreadcrumbsResult()
    {
        $res = $this->service->generateBreadcrumbs('');
        $this->assertEquals($res, []);

        $res = $this->service->generateBreadcrumbs('fail.html');
        $this->assertEquals(
            $res,
            [
                [
                    'path' => 'index.html',
                    'title' => 'Title index file',
                ]
            ]
        );

        $res = $this->service->generateBreadcrumbs('index.html');
        $this->assertEquals($res, []);

        $res = $this->service->generateBreadcrumbs('part1/index.html');
        $this->assertEquals(
            $res,
            [
                [

                    'path' => 'index.html',
                    'title' => 'Title index file',
                ],
                [

                    'path' => 'part1/index.html',
                    'title' => 'Part 1 title',
                ]
            ]
        );

        $res = $this->service->generateBreadcrumbs('part1/part1-1/index.html');
        $this->assertEquals(
            $res,
            [
                [

                    'path' => 'index.html',
                    'title' => 'Title index file',
                ],
                [

                    'path' => 'part1/index.html',
                    'title' => 'Part 1 title',
                ],
                [

                    'path' => 'part1/part1-1/index.html',
                    'title' => 'Part 1-1 title',
                ]
            ]
        );

        $res = $this->service->generateBreadcrumbs('part1/part1-1/part1-1-text.html');
        $this->assertEquals(
            $res,
            [
                [

                    'path' => 'index.html',
                    'title' => 'Title index file',
                ],
                [

                    'path' => 'part1/index.html',
                    'title' => 'Part 1 title',
                ],
                [

                    'path' => 'part1/part1-1/index.html',
                    'title' => 'Part 1-1 title',
                ],
                [

                    'path' => 'part1/part1-1/part1-1-text.html',
                    'title' => 'Part 1-1 text title',
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestIndexPageDataResult()
    {
        $res = $this->service->generateIndexPageData();
        $this->assertEquals(
            $res,
            [
                'path' => 'index.html',
                'title' => 'Title index file',
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestImageDataResult()
    {
        $res = $this->service->getImageData(
            'image/test.jpg',
            false
        );
        $this->assertEquals(
            $res,
            [
                'file' => __DIR__ . '/resources/docs/image/test.jpg',
                'headers' => [
                    'Content-Type' => 'image/jpeg',
                    'Content-Length' => 733,
                ]
            ]
        );

        $res = $this->service->getImageData(
            'image/test.png',
            false
        );
        $this->assertEquals(
            $res,
            [
                'file' => __DIR__ . '/resources/docs/image/test.png',
                'headers' => [
                    'Content-Type' => 'image/png',
                    'Content-Length' => 152,
                ]
            ]
        );

        $res = $this->service->getImageData(
            'image/fail.jpg',
            false
        );
        $this->assertEquals(
            $res,
            [
                'file' => null,
                'headers' => [
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestFileMimeTypeResult()
    {
        $file = __DIR__ . '/resources/images/test.jpg';
        $res = $this->getServiceProtectedMethod('mimeType', $file);
        $this->assertEquals($res, 'image/jpeg');

        $file = __DIR__ . '/resources/images/test.png';
        $res = $this->getServiceProtectedMethod('mimeType', $file);
        $this->assertEquals($res, 'image/png');
    }

    /**
     * @test
     */
    public function serviceTestResponseFileFormatResult()
    {
        $res = $this->getServiceProtectedMethod(
            'responseFileFormat',
            'path/index.html',
            'title text'
        );
        $this->assertEquals(
            $res,
            [
                'path' => 'path/index.html',
                'title' => 'title text',
            ]
        );

        $res = $this->getServiceProtectedMethod(
            'responseFileFormat',
            'path/main.html',
            'title text 2'
        );
        $this->assertEquals(
            $res,
            [
                'path' => 'path/main.html',
                'title' => 'title text 2',
            ]
        );
    }

    /**
     * @test
     */
    public function serviceTestFileTitleResult()
    {
        $res = $this->getServiceProtectedMethod(
            'fileTitle',
            'index.html'
        );
        $this->assertEquals($res, 'Title index file');

        $res = $this->getServiceProtectedMethod(
            'fileTitle',
            'main.html'
        );
        $this->assertEquals($res, 'Title main file');

        $res = $this->getServiceProtectedMethod(
            'fileTitle',
            'without_title.html'
        );
        $this->assertNull($res);

        $res = $this->getServiceProtectedMethod(
            'fileTitle',
            'empty.html'
        );
        $this->assertNull($res);

        $res = $this->getServiceProtectedMethod(
            'fileTitle',
            'part1/index.html'
        );
        $this->assertEquals($res, 'Part 1 title');
    }

    /**
     * @test
     */
    public function serviceTestGenerateFilePathResult()
    {
        $res = $this->getServiceProtectedMethod(
            'generateFilePath',
            'index.html'
        );
        $this->assertEquals($res, 'index.html');

        $res = $this->getServiceProtectedMethod(
            'generateFilePath',
            'index.html',
            'part1'
        );
        $this->assertEquals($res, 'part1/index.html');

        $res = $this->getServiceProtectedMethod(
            'generateFilePath',
            'content.html',
            'part1/part1-1'
        );
        $this->assertEquals($res, 'part1/part1-1/content.html');
    }
}
