<?php

namespace App\Traits;

use App\Models\PageDetail;

trait PageSeo {
    public function pageSeoData($slug, $selectedTheme)
    {
        $pageDetails = PageDetail::with('page')
            ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
            })
            ->first();

        if ($pageDetails){
            $data = [
                'page_title' => optional($pageDetails->page)->page_title,
                'meta_title' => optional($pageDetails->page)->meta_title,
                'meta_keywords' => implode(',', optional($pageDetails->page)->meta_keywords ?? []),
                'meta_description' => optional($pageDetails->page)->meta_description,
                'og_description' => optional($pageDetails->page)->og_description,
                'meta_robots' => optional($pageDetails->page)->meta_robots,
                'meta_image' => getFile(optional($pageDetails->page)->meta_image_driver, optional($pageDetails->page)->meta_image),
                'breadcrumb_image' => optional($pageDetails->page)->breadcrumb_status ?
                    getFile(optional($pageDetails->page)->breadcrumb_image_driver, optional($pageDetails->page)->breadcrumb_image) : null,
            ];

            return $data;
        }
    }
}
