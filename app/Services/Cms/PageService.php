<?php

namespace App\Services\Cms;

use App\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageService
{
    /**
     * Cari halaman berdasarkan slug yang aktif dan published.
     * Termasuk relasi sections dan template.
     */
    public function findBySlug(string $slug): Page
    {
        $page = Page::with(['template', 'sections' => function ($query) {
                $query->where('is_visible', true)->orderBy('order');
            }])
            ->where('slug', $slug)
            ->published() // Menggunakan scopePublished
            ->first();

        if (!$page) {
            throw new ModelNotFoundException("Halaman dengan slug '{$slug}' tidak ditemukan atau tidak aktif.");
        }

        return $page;
    }

    /**
     * Siapkan data view yang seragam untuk frontend.
     */
    public function preparePageData(Page $page): array
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'subtitle' => $page->subtitle,
            'content' => $page->content, // HTML mentah jika ada
            'structure' => $page->content_structure, // JSON structure jika pakai page builder
            'template' => $page->template ? [
                'name' => $page->template->name,
                'view_path' => $page->template->view_path,
                'config' => array_merge($page->template->default_config ?? [], $page->content_structure['config'] ?? [])
            ] : null,
            'sections' => $page->sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->section_name,
                    'type' => $section->component_type,
                    'data' => $section->data,
                    'style' => $section->style_config,
                ];
            }),
            'meta' => [
                'title' => $page->meta_title ?? $page->title,
                'description' => $page->meta_description,
                'keywords' => $page->meta_keywords,
                'image' => $page->featured_image,
                'indexable' => $page->is_indexable,
                'published_at' => $page->published_at?->format('d M Y'),
                'author' => $page->author?->name
            ]
        ];
    }
}
