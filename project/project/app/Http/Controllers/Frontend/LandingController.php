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
        'africa' => ['Africa', 'AF'],
        'kenya' => ['Kenya', 'KE'],
        'nigeria' => ['Nigeria', 'NG'],
        'tanzania' => ['Tanzania', 'TZ'],
        'uganda' => ['Uganda', 'UG'],
        'ghana' => ['Ghana', 'GH'],
        'south-africa' => ['South Africa', 'ZA'],
        'rwanda' => ['Rwanda', 'RW'],
        'ethiopia' => ['Ethiopia', 'ET'],
        'worldwide' => ['Worldwide', 'WW'],
        'usa' => ['the USA', 'US'],
        'uk' => ['the UK', 'GB'],
        'india' => ['India', 'IN'],
        'uae' => ['the UAE', 'AE'],
        'brazil' => ['Brazil', 'BR'],
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

    public function index(?string $country = null)
    {
        $categories = $this->categories()->sortBy('category_title')->groupBy(fn($c) => optional($c->socialMedia)->name ?: 'Other');
        $site = basicControl()->site_title ?: 'Mass Fame Shop';

        [$countryName, $iso] = ($country && isset(self::COUNTRIES[$country])) ? self::COUNTRIES[$country] : [null, null];

        if ($country === 'kenya') {
            $pageTitle = __('Best SMM Panel in Kenya | Buy Followers, Likes & Views with M-Pesa');
            $metaTitle = "Best SMM Panel in Kenya - Cheapest SMM Provider (Lipa Na M-Pesa) | {$site}";
            $metaDesc = "Mass Fame Shop is Kenya's #1 cheapest and most reliable SMM panel. Buy real Instagram followers, likes, TikTok views, YouTube watch hours and Facebook likes with instant automated M-Pesa STK push. 24/7 delivery.";
            $metaKeywords = 'best smm panel kenya, cheapest smm panel kenya, smm panel mpesa, smm provider kenya, buy instagram followers kenya mpesa, buy tiktok views kenya, youtube subscribers kenya, smm nairobi, cheap smm kenya';
        } elseif ($country === 'africa') {
            $pageTitle = __('Best SMM in Africa | #1 Cheapest Social Media Provider');
            $metaTitle = "Best SMM Panel in Africa - Cheapest SMM Reseller Provider | {$site}";
            $metaDesc = "Mass Fame Shop is Africa's premier SMM panel serving Kenya, Nigeria, South Africa, Ghana, Tanzania, Uganda and worldwide. 24/7 instant automated delivery from $0.001 with local mobile money and card payment options.";
            $metaKeywords = 'best smm in africa, best smm panel africa, smm provider africa, cheap smm reseller africa, smm panel nigeria, smm panel south africa, social media marketing africa, best smm panel kenya';
        } elseif ($countryName) {
            $cleanName = ucfirst(preg_replace('/^the /i', '', $countryName));
            $pageTitle = __('Best SMM Panel in') . ' ' . $cleanName . ' | ' . __('Buy Followers, Likes & Views');
            $metaTitle = __('Best SMM Panel in') . " {$cleanName} - #1 Cheapest Social Media Provider | {$site}";
            $metaDesc = "Mass Fame Shop is the leading SMM panel in {$cleanName}. Buy cheap, instant followers, likes, views and subscribers for Instagram, TikTok, YouTube and Facebook with automated delivery.";
            $metaKeywords = strtolower("best smm panel {$cleanName}, smm provider {$cleanName}, buy followers {$cleanName}, cheap smm panel {$cleanName}, smm panel africa");
        } else {
            $pageTitle = __('Best SMM Panel in Africa & Kenya | Buy Social Media Services');
            $metaTitle = "Best SMM Panel in Africa & Kenya - Cheapest Social Media Provider | {$site}";
            $metaDesc = "Mass Fame Shop is Africa's #1 cheapest and most reliable SMM panel. Buy real Instagram followers, likes, TikTok views, YouTube subscribers with instant M-Pesa, card or crypto. Serving Kenya, Nigeria, South Africa & worldwide.";
            $metaKeywords = 'best smm in africa, best smm panel kenya, cheapest smm panel kenya, smm panel mpesa, smm provider africa, buy instagram followers kenya, buy tiktok views kenya, smm reseller panel africa, cheap smm panel worldwide';
        }

        return view(template() . 'landing.index', [
            'groups' => $categories,
            'countries' => self::COUNTRIES,
            'country' => $country,
            'countryName' => $countryName,
            'footer' => footerData(),
            'pageSeo' => [
                'page_title' => $pageTitle,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'meta_keywords' => $metaKeywords,
                'meta_robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
            ],
        ]);
    }

    public function show(string $slug, ?string $country = null)
    {
        // Support direct regional landing aliases like /buy/kenya or /buy/africa or /buy/all/kenya
        if (isset(self::COUNTRIES[$slug]) && $country === null) {
            return $this->index($slug);
        }
        if ($slug === 'all') {
            return $this->index($country);
        }

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
        $site = basicControl()->site_title ?: 'Mass Fame Shop';
        $cheapest = $category->service->first();
        $cheapestText = $cheapest ? strip_tags($price($cheapest->price)) : '';
        $related = $categories->where('social_media_id', $category->social_media_id)->where('id', '!=', $category->id)->take(8);

        if ($country === 'kenya') {
            $heading = __('Buy') . ' ' . $title . ' ' . __('in Kenya (M-Pesa Instant)');
            $pageTitle = __('Best SMM Panel Kenya') . " - {$title} M-Pesa";
            $metaTitle = "Best SMM Panel Kenya: Buy {$title} in Kenya from {$cheapestText} / 1k | {$site}";
            $metaDesc = "Buy {$title} in Kenya with instant automated M-Pesa STK push. Mass Fame Shop is Kenya's #1 cheapest SMM provider for creators, businesses & agencies. Starts from {$cheapestText}.";
            $metaKeywords = strtolower("best smm panel kenya, buy {$title} kenya, {$title} mpesa, cheap smm kenya, {$platform} kenya, smm provider nairobi");
        } elseif ($country === 'africa') {
            $heading = __('Buy') . ' ' . $title . ' ' . __('in Africa (Best SMM Panel)');
            $pageTitle = __('Best SMM in Africa') . " - Buy {$title} Across Africa";
            $metaTitle = "Best SMM in Africa: Buy {$title} Across Africa from {$cheapestText} / 1k | {$site}";
            $metaDesc = "Africa's #1 SMM Provider. Buy {$title} across Kenya, Nigeria, South Africa, Ghana, Tanzania and Uganda. Instant delivery from {$cheapestText} with local mobile money and card checkout.";
            $metaKeywords = strtolower("best smm in africa, buy {$title} africa, smm provider africa, cheap {$title} africa, smm panel nigeria, smm panel south africa");
        } else {
            $where = $countryName ? ' ' . __('in') . ' ' . $countryName : '';
            $heading = __('Buy') . ' ' . $title . $where;
            $pageTitle = $heading;
            $metaTitle = __('Buy') . " {$title}{$where} " . __('from') . ' ' . $cheapestText . ' / 1k | ' . $site;
            $metaDesc = __('Buy') . " {$title}{$where}: " . __('instant start, real-looking results and refill options. Pay with M-Pesa, card or bank. No password needed.');
            $metaKeywords = strtolower("buy {$title}{$where}, {$title} cheap, {$platform} growth, smm panel");
        }

        return view(template() . 'landing.show', [
            'category' => $category,
            'country' => $country,
            'countryName' => $countryName,
            'countries' => self::COUNTRIES,
            'price' => $price,
            'related' => $related,
            'platform' => $platform,
            'heading' => $heading,
            'footer' => footerData(),
            'pageSeo' => [
                'page_title' => $pageTitle,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'meta_keywords' => $metaKeywords,
                'meta_robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
            ],
        ]);
    }

    public function sitemap()
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => route('services'), 'priority' => '0.9', 'freq' => 'daily'],
            ['loc' => route('landing.index'), 'priority' => '0.9', 'freq' => 'daily'],
            ['loc' => url('/order-without-account'), 'priority' => '0.85', 'freq' => 'daily'],
            ['loc' => url('/rental-preview'), 'priority' => '0.8', 'freq' => 'weekly'],
        ];

        // Regional Country Hubs
        foreach (array_keys(self::COUNTRIES) as $country) {
            $priority = in_array($country, ['kenya', 'africa']) ? '0.9' : '0.8';
            $urls[] = ['loc' => url('/buy/' . $country), 'priority' => $priority, 'freq' => 'daily'];
        }

        // Category Pages and Country-Specific Landing Pages
        $categories = $this->categories();
        foreach ($categories as $c) {
            $urls[] = ['loc' => route('landing.show', $c->slug), 'priority' => '0.85', 'freq' => 'daily'];
            foreach (array_keys(self::COUNTRIES) as $country) {
                $priority = in_array($country, ['kenya', 'africa']) ? '0.85' : '0.75';
                $urls[] = ['loc' => route('landing.show', [$c->slug, $country]), 'priority' => $priority, 'freq' => 'daily'];
            }
        }

        // Blog Posts (if available)
        try {
            if (class_exists(\App\Models\Blog::class)) {
                $blogs = \App\Models\Blog::with('details')->where('status', 1)->get();
                foreach ($blogs as $b) {
                    if ($slug = optional($b->details)->slug) {
                        $urls[] = ['loc' => route('blog.details', $slug), 'priority' => '0.7', 'freq' => 'weekly'];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore if blog table not active
        }

        $now = date('c');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $now . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['freq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';
        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
