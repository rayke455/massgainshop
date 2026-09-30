<?php

namespace App\Traits;

use App\Models\Blog;
use App\Models\ContentDetails;


trait Frontend
{
    protected function getSectionsData($sections, $content, $selectedTheme)
    {
        if ($sections == null) {
            $data = ['support' => $content,];
            $data['footer'] = footerData();
            return view("themes.$selectedTheme.support", $data)->toHtml();
        }

        $contentData = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($sections, $selectedTheme) {
                $query->whereIn('name', $sections);
            })
            ->get();

        foreach ($sections as $section) {
            $singleContent = $contentData->where('content.theme', $selectedTheme)->where('content.name', $section)->where('content.type', 'single')->first() ?? [];
            if ($section == 'blog') {
                $blogs = Blog::with('details:id,blog_id,title,slug,description', 'author', 'author.details')
                    ->select('id', 'views', 'author_id', 'thumbnail_image', 'thumbnail_image_driver', 'thumbnail_image_two_driver', 'thumbnail_image_two', 'created_at')
                    ->where('status', 1)
                    ->latest();
                if (request()->is('/') || request()->is('about')) {
                    $blogs = $blogs->limit(3)->get();
                } else {
                    $blogs = $blogs->paginate(basicControl()->paginate);
                }

                $data[$section] = [
                    'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                    'multiple' => $blogs
                ];
            } else {
                $multipleContents = $contentData->where('content.theme', $selectedTheme)->where('content.name', $section)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                    return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
                });

                $data[$section] = [
                    'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                    'multiple' => $multipleContents
                ];
            }

            $replacement = view("themes.$selectedTheme.sections.{$section}", $data)->toHtml();

            $content = str_replace('<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[' . $section . ']]</div>', $replacement, $content);
            $content = str_replace('<span class="delete-block">×</span>', '', $content);
            $content = str_replace('<span class="up-block">↑</span>', '', $content);
            $content = str_replace('<span class="down-block">↓</span></div>', '', $content);
            $content = str_replace('<p><br></p>', '', $content);
        }

        return $content;
    }
}
