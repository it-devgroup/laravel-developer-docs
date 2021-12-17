<?php

namespace ItDevgroup\LaravelDeveloperDocs\Http\Controllers;

use ItDevgroup\LaravelDeveloperDocs\DeveloperDocsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Trait ControllerTrait
 * @package ItDevgroup\LaravelDeveloperDocs\Http\Controllers
 */
trait ControllerTrait
{
    /**
     * @var DeveloperDocsServiceInterface
     */
    private DeveloperDocsServiceInterface $developerDocsService;

    /**
     * DeveloperDocsController constructor.
     * @param DeveloperDocsServiceInterface $developerDocsService
     */
    public function __construct(
        DeveloperDocsServiceInterface $developerDocsService
    ) {
        $this->developerDocsService = $developerDocsService;
    }

    /**
     * @return View
     */
    public function page(): View
    {
        return view('developerDocs::page');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function get(Request $request): Response
    {
        $path = $this->developerDocsService->getPathFromFile($request->input('path'));

        return new Response(
            [
                'categories' => $this->developerDocsService->generateFolderMenu($path),
                'sections' => $this->developerDocsService->generateFileMenu($path),
                'backLink' => $this->developerDocsService->generateBackPathFromFile($request->input('path')),
                'breadcrumbs' => $this->developerDocsService->generateBreadcrumbs($request->input('path')),
                'title' => $this->developerDocsService->getFileTitle($request->input('path')),
                'content' => $this->developerDocsService->getFileContent($request->input('path')),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function image(Request $request): Response
    {
        $this->developerDocsService->getImageData($request->input('path'), true);

        return new Response([], Response::HTTP_NOT_FOUND);
    }
}
