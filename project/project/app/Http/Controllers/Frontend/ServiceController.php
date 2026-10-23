<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\SocialMedia;
use App\Traits\PageSeo;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use PageSeo;

    public function services()
    {
        $siteTitle = basicControl()->site_title ?: 'Mass Fame Shop';
        $data['footer'] = footerData();
        $data['socialMedia'] = SocialMedia::has('category')->with('category')->orderBy('sort_by', 'asc')->get();
        $data['pageSeo'] = $this->pageSeoData('services', basicControl()->theme) ?: [];
        $data['pageSeo']['page_title'] = __('Services & Pricing - Best SMM in Africa & Kenya');
        $data['pageSeo']['meta_title'] = "SMM Services & Live Pricing | Best SMM in Africa & Kenya - {$siteTitle}";
        $data['pageSeo']['meta_description'] = "Explore transparent, live prices for Instagram followers, likes, TikTok views, YouTube watch hours and Facebook engagement on {$siteTitle}. Africa's #1 cheapest SMM provider.";
        $data['pageSeo']['meta_keywords'] = 'best smm in africa, best smm panel kenya, smm services pricing, buy followers cheap, smm panel mpesa, mass fame shop';
        $data['pageSeo']['meta_robots'] = 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
        return view(template() . 'service', $data);
    }

    public function getServices(Request $request)
    {
        $socialMedia = $request->social_media_id;
        $categoryId = $request->category;
        $searchTerm = $request->search;

        $currency = Currency::where('code', $request->currency)->first();
        $categories = Category::with('socialMedia')
            ->select('id', 'category_title', 'social_media_id')
            ->with(['service' => function ($query) {
                $query->select('id', 'category_id', 'service_title', 'price', 'min_amount', 'max_amount', 'description', 'service_status')
                    ->where('service_status', 1)->userRate();
            }])
            ->whereHas('service', function ($query) {
                $query->where('service_status', 1)->userRate()
                    ->whereHas('provider', function ($query) {
                        $query->where('status', 1);
                    })->orWhereDoesntHave('provider');
            })
            ->when(isset($socialMedia) && $socialMedia != 0, function ($query) use ($socialMedia) {
                return $query->where('social_media_id', $socialMedia);
            })
            ->when(isset($categoryId), function ($query) use ($categoryId) {
                return $query->where('id', $categoryId);
            })
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw("category_title REGEXP '\\\\b{$searchTerm}\\\\b'")
                        ->orWhereHas('service', function ($query) use ($searchTerm) {
                            $query->whereRaw("service_title REGEXP '\\\\b{$searchTerm}\\\\b'");
                        });
                });
            })
            ->where('status', 1)
            ->orderBy('sort_by', 'asc')
            ->paginate(basicControl()->paginate);

        $categories->getCollection()->transform(function ($category) use ($currency) {
            foreach ($category->service as $service) {

                preg_match('/Speed:\s*([^\]]+)/i', $service->service_title, $matches);
                $speedRaw = $matches[1] ?? null;
                $timeInMinutes = null;

                if ($speedRaw) {
                    preg_match('/(\d+)(?:-(\d+))?\s*(Day|Hour|Minute)s?/i', $speedRaw, $parts);

                    if ($parts) {
                        $min = (int)$parts[1];
                        $max = isset($parts[2]) ? (int)$parts[2] : $min;
                        $unit = strtolower($parts[3]);

                        $average = ($min + $max) / 2;

                        switch ($unit) {
                            case 'day':
                                $timeInMinutes = $average * 24 * 60;
                                break;
                            case 'hour':
                                $timeInMinutes = $average * 60;
                                break;
                            case 'minute':
                                $timeInMinutes = $average;
                                break;
                        }
                    }
                }

                $service->average_time_minutes = $timeInMinutes ?? null;
                $service->min_amount = conveter($service->min_amount);
                $service->max_amount = conveter($service->max_amount);
                if ($currency) {
                    if ($service->user_rate) {
                        $service->priceSelectedCurrency = currencyPositionBySelectedCurrency(doubleval($service->user_rate) * doubleval($currency->conversion_rate), $currency->code);
                    } else {
                        $service->priceSelectedCurrency = currencyPositionBySelectedCurrency(doubleval($service->price) * doubleval($currency->conversion_rate), $currency->code);
                    }
                } else {
                    $service->price = isset($service->user_rate) ? currencyPosition(doubleval($service->user_rate)) : currencyPosition(doubleval($service->price));
                }
            }
            return $category;
        });

        return response()->json($categories);
    }

    public function getCategory(Request $request)
    {
        $socialMedia = $request->social_media_id;
        $categories = Category::select('id', 'category_title', 'social_media_id')
            ->when(isset($socialMedia), function ($query) use ($socialMedia) {
                return $query->where('social_media_id', $socialMedia);
            })
            ->where('status', 1)
            ->get();
        return response()->json($categories);
    }
}
