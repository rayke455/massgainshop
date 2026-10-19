<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Str;

/**
 * Google landing pages: /buy (all), /buy/{category}, /buy/{category}/{country}, plus /sitemap.xml.
 * Built from the live catalogue, so new categories get a page automatically.
 */
class LandingController extends Controller
{
    /** Markets with their own page (slug => [name, ISO, demonym-free phrase]). */
    public const COUNTRIES = [
        'kenya' => ['Kenya', 'KE'], 'nigeria' => ['Nigeria', 'NG'], 'tanzania' => ['Tanzania', 'TZ'], 'uganda' => ['Uganda', 'UG'],
        'ghana' => ['Ghana', 'GH'], 'south-africa' => ['South Africa', 'ZA'], 'rwanda' => ['Rwanda', 'RW'], 'usa' => ['the USA', 'US'],
        'uk' => ['the UK', 'GB'], 'india' => ['India', 'IN'], 'uae' => ['the UAE', 'AE'], 'brazil' => ['Brazil', 'BR'],
    ];

    private function categories()
    {
        return Category::with(['socialMedia:id,name', 'service' => fn($q) => $q->where('service_status', 1)
                ->select('id', 'category_id', 'service_title', 'price', 'min_amount', 'max_amount', 'refill', 'description')
                ->orderByRaw('CAST(price AS DECIMAL(16,6)) asc')])
            ->where('status', 1)
            ->whereHas('service', fn($q) => $q->where('service_status', 1))
            ->get()
            ->each(fn($c) => $c->slug = Str::slug($c->category_title));
    }

    public function index()
    {
        $categories = $this->categories()->sortBy('category_title')->groupBy(fn($c) => optional($c->socialMedia)->name ?: 'Other');
        $site = basicControl()->site_title;
        return view(template() . 'landing.index', [
            'groups' => $categories,
            'countries' => self::COUNTRIES,
            'footer' => footerData(),
            'pageSeo' => [
                'page_title' => __('Buy social media services'),
                'meta_title' => __('Buy followers, likes and views') . " | {$site}",
                'meta_description' => __('Cheap, fast followers, likes, views and more for Instagram, TikTok, YouTube, Facebook and Telegram. Instant start, pay with M-Pesa or card.'),
                'meta_keywords' => 'buy followers, buy likes, buy views, smm panel',
                'meta_robots' => 'index,follow',
            ],
        ]);
    }

    public function show(string $slug, ?string $country = null)
    {
        $categories = $this->categories();
        $category = $categories->firstWhere('slug', $slug);
        abort_unless($category, 404);
        if ($country !== null) {
            abort_unless(isset(self::COUNTRIES[$country]), 404);
        }

        [$countryName, $iso] = $country ? self::COUNTRIES[$country] : [null, null];
        // Prices in the country's currency (if it's switched on), else the visitor's currency.
        $cur = $iso ? Currency::where('code', config("country_currency.$iso"))->where('status', 1)->first() : null;
        $price = $cur
            ? fn($amount) => zkPerK((float) $amount * (float) $cur->conversion_rate, $cur->code)
            : fn($amount) => zkShowPrice($amount);

        $title = $category->category_title;
        $platform = optional($category->socialMedia)->name;
        $site = basicControl()->site_title;
        $where = $countryName ? ' ' . __('in') . ' ' . $countryName : '';
        $cheapest = $category->service->first();
        $related = $categories->where('social_media_id', $category->social_media_id)->where('id', '!=', $category->id)->take(8);

        return view(template() . 'landing.show', [
            'category' => $category,
            'country' => $country,
            'countryName' => $countryName,
            'countries' => self::COUNTRIES,
            'price' => $price,
            'related' => $related,
            'platform' => $platform,
            'heading' => __('Buy') . ' ' . $title . $where,
            'footer' => footerData(),
            'pageSeo' => [
                'page_title' => __('Buy') . ' ' . $title . $where,
                'meta_title' => __('Buy') . " {$title}{$where} " . __('from') . ' ' . ($cheapest ? strip_tags($price($cheapest->price)) : '') . ' / 1k | ' . $site,
                'meta_description' => __('Buy') . " {$title}{$where}: " . __('instant start, real-looking results and refill options. Pay with M-Pesa, card or bank. No password needed.'),
                'meta_keywords' => strtolower("buy {$title}{$where}, {$title} cheap, {$platform} growth"),
                'meta_robots' => 'index,follow',
            ],
        ]);
    }

    public function sitemap()
    {
        $urls = [url('/'), route('services'), route('landing.index')];
        foreach ($this->categories() as $c) {
            $urls[] = route('landing.show', $c->slug);
            foreach (array_keys(self::COUNTRIES) as $country) {
                $urls[] = route('landing.show', [$c->slug, $country]);
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>' . e($u) . '</loc><changefreq>daily</changefreq></url>' . "\n";
        }
        $xml .= '</urlset>';
        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
