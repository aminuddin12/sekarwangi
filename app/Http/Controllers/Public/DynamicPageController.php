<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Cms\PageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;
use Inertia\Response;

class DynamicPageController extends PublicController // Extends PublicController untuk mewarisi menu
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Handle halaman dinamis.
     * Logic:
     * 1. Cek apakah ada halaman statis khusus (misal home)
     * 2. Cari di database berdasarkan slug
     * 3. Render sesuai 'view_path' dari template database
     */
    public function show(string $slug = 'home'): Response
    {
        try {
            $page = $this->pageService->findBySlug($slug);
            $pageData = $this->pageService->preparePageData($page);

            // Tentukan View Component berdasarkan template di database
            // Fallback ke 'DefaultPage' jika tidak ada template khusus
            $component = $page->template->view_path ?? 'public/templates/DefaultPage';

            // Gabungkan data shared menu (dari PublicController) dengan data halaman
            return Inertia::render($component, array_merge($this->getSharedData(), [
                'page' => $pageData
            ]));

        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }
}
