<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * "Quick import": turns a provider's huge, messy catalogue into a short list of sensible services,
 * grouped into clean categories like "Instagram Followers".
 */
class SmartImport
{
    /** What a service sells, in match order (more specific first). */
    public const TYPES = [
        'Live Stream' => ['live stream', 'livestream', 'live view', 'concurrent', ' live '],
        'Story Views' => ['story view', 'story'],
        'Comments' => ['comment'],
        'Reactions' => ['reaction', 'emoji'],
        'Members' => ['member', 'group join', 'channel join'],
        'Subscribers' => ['subscriber', 'subs '],
        'Followers' => ['follower', 'follow '],
        'Likes' => ['like'],
        'Views' => ['view', 'play', 'watch', 'impression', 'stream'],
        'Shares' => ['share', 'retweet', 'repost'],
        'Saves' => ['save'],
        'Votes' => ['vote', 'poll'],
        'Traffic' => ['traffic', 'visit'],
    ];

    /** Services that should never be offered to customers. */
    private const SKIP = ['test', 'do not order', "don't order", 'dont order', 'not working', 'disabled'];

    /** Country-targeted services are niche; only offered when nothing general exists. */
    private const GEO = ['brazil', 'brazilian', 'india', 'indian', 'turkey', 'turkish', 'arab', 'arabic', 'russia', 'russian',
        'indonesia', 'vietnam', 'egypt', 'korea', 'korean', 'japan', 'iran', 'pakistan', 'bangladesh', 'usa', 'america',
        'germany', 'german', 'france', 'french', 'italy', 'spain', 'mexico', 'thailand', 'philippines', 'azerbaijan', 'china', 'chinese'];

    public function __construct(private array $platforms)
    {
    }

    public function platformOf(array $s): string
    {
        $haystack = ' ' . strtolower(self::plain(($s['category'] ?? '') . ' ' . ($s['name'] ?? ''))) . ' ';
        foreach ($this->platforms as $label => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return $label;
                }
            }
        }
        return 'Other';
    }

    public static function typeOf(array $s): string
    {
        // Auto-likes/views on every new post (provider type "Subscriptions"): its own type.
        if (strcasecmp((string) ($s['type'] ?? ''), 'Subscriptions') === 0) {
            return 'Auto Engagement';
        }
        // The service name is more precise than the provider category, so try it first.
        foreach ([$s['name'] ?? '', $s['category'] ?? ''] as $text) {
            $haystack = ' ' . strtolower(self::plain($text)) . ' ';
            foreach (self::TYPES as $label => $needles) {
                foreach ($needles as $needle) {
                    if (str_contains($haystack, $needle)) {
                        return $label;
                    }
                }
            }
        }
        return 'Other';
    }

    /** Platform → type → number of services, for the picker. */
    public function matrix(Collection $services): array
    {
        $m = [];
        foreach ($services as $s) {
            if ($this->skippable($s)) {
                continue;
            }
            $p = $this->platformOf($s);
            $t = self::typeOf($s);
            $m[$p][$t] = ($m[$p][$t] ?? 0) + 1;
        }
        uasort($m, fn($a, $b) => array_sum($b) <=> array_sum($a));
        foreach ($m as &$types) {
            arsort($types);
        }
        return $m;
    }

    /**
     * Pick the best services for every chosen platform × type.
     * strategy: best (cheapest + at least one refill), cheapest, refill (refill only).
     */
    public function pick(Collection $services, array $platforms, array $types, int $perType, string $strategy, array $alreadyImported): Collection
    {
        $groups = [];
        foreach ($services as $s) {
            if ($this->skippable($s) || isset($alreadyImported[(string) $s['service']])) {
                continue;
            }
            $p = $this->platformOf($s);
            $t = self::typeOf($s);
            if (in_array($p, $platforms, true) && in_array($t, $types, true)) {
                $groups[$p . '|' . $t][] = $s;
            }
        }

        $picked = collect();
        foreach ($groups as $key => $items) {
            [$p, $t] = explode('|', $key, 2);
            $items = collect($items)->sortBy(fn($s) => (float) $s['rate'])
                // near-duplicates (same name, different server): keep the cheapest
                ->unique(fn($s) => preg_replace('/[^a-z0-9]+/', '', strtolower(self::cleanName($s['name']))))
                ->values();
            $general = $items->reject(fn($s) => $this->isGeo($s));
            if ($general->isNotEmpty()) {
                $items = $general->values();
            }
            $refill = $items->filter(fn($s) => !empty($s['refill']));

            if ($strategy === 'cheapest') {
                $chosen = $items->take($perType);
            } elseif ($strategy === 'refill') {
                $chosen = $refill->take($perType);
            } else {
                // Best mix: the cheapest refill service (if any) plus the cheapest others.
                $first = $refill->first();
                $rest = $items->reject(fn($s) => $first && $s['service'] == $first['service']);
                $chosen = collect($first ? [$first] : [])->merge($rest->take($perType - ($first ? 1 : 0)));
            }

            foreach ($chosen as $s) {
                $picked->push($s + ['_platform' => $p, '_type' => $t]);
            }
        }

        return $picked->sortBy([['_platform', 'asc'], ['_type', 'asc'], [fn($a, $b) => (float) $a['rate'] <=> (float) $b['rate']]])->values();
    }

    public function isGeo(array $s): bool
    {
        $name = ' ' . preg_replace('/[^a-z]+/', ' ', strtolower(self::plain($s['name'] ?? ''))) . ' ';
        foreach (self::GEO as $g) {
            if (str_contains($name, ' ' . $g . ' ')) {
                return true;
            }
        }
        return false;
    }

    public function skippable(array $s): bool
    {
        if ((float) ($s['rate'] ?? 0) <= 0) {
            return true;
        }
        $name = strtolower(self::plain($s['name'] ?? ''));
        foreach (self::SKIP as $bad) {
            if (preg_match('/\b' . preg_quote($bad, '/') . '\b/', $name)) {
                return true;
            }
        }
        return false;
    }

    /** Readable name: plain letters, no emojis/decorations, tidy separators. */
    public static function cleanName(string $name): string
    {
        $s = self::plain(html_entity_decode(strip_tags($name), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        // Emojis, pictographs, flags, variation selectors and joiners.
        $s = preg_replace('/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{1F1E6}-\x{1F1FF}\x{FE00}-\x{FE0F}\x{200B}-\x{200D}\x{2060}\x{20E3}\x{E000}-\x{F8FF}]/u', '', $s);
        $s = preg_replace('/\s*[~|•●★☆✓✔➜➤→»]+\s*/u', ' - ', $s);   // decorative separators
        $s = preg_replace('/\[\s*\]|\(\s*\)/u', '', $s);               // empty brackets
        $s = preg_replace('/\s*-\s*(-\s*)+/u', ' - ', $s);             // repeated dashes
        $s = preg_replace('/\s+/u', ' ', $s);
        $s = trim($s, " -:,.\t\n");
        return Str::limit($s !== '' ? $s : trim($name), 250, '');
    }

    /** Map "fancy" Unicode letters (𝗕𝗼𝗹𝗱, 𝐒𝐞𝐫𝐢𝐟, ᴺᴱᵂ…) to plain ASCII. Works without the intl extension. */
    public static function plain(string $text): string
    {
        static $small = [
            'ᴀ' => 'A', 'ʙ' => 'B', 'ᴄ' => 'C', 'ᴅ' => 'D', 'ᴇ' => 'E', 'ꜰ' => 'F', 'ɢ' => 'G', 'ʜ' => 'H', 'ɪ' => 'I', 'ᴊ' => 'J',
            'ᴋ' => 'K', 'ʟ' => 'L', 'ᴍ' => 'M', 'ɴ' => 'N', 'ᴏ' => 'O', 'ᴘ' => 'P', 'ʀ' => 'R', 'ꜱ' => 'S', 'ᴛ' => 'T', 'ᴜ' => 'U',
            'ᴠ' => 'V', 'ᴡ' => 'W', 'ʏ' => 'Y', 'ᴢ' => 'Z',
            'ᴬ' => 'A', 'ᴮ' => 'B', 'ᴰ' => 'D', 'ᴱ' => 'E', 'ᴳ' => 'G', 'ᴴ' => 'H', 'ᴵ' => 'I', 'ᴶ' => 'J', 'ᴷ' => 'K', 'ᴸ' => 'L',
            'ᴹ' => 'M', 'ᴺ' => 'N', 'ᴼ' => 'O', 'ᴾ' => 'P', 'ᴿ' => 'R', 'ᵀ' => 'T', 'ᵁ' => 'U', 'ⱽ' => 'V', 'ᵂ' => 'W',
            'ℎ' => 'h', 'ℬ' => 'B', 'ℰ' => 'E', 'ℱ' => 'F', 'ℋ' => 'H', 'ℐ' => 'I', 'ℒ' => 'L', 'ℳ' => 'M', 'ℛ' => 'R', 'ℯ' => 'e',
            'ℊ' => 'g', 'ℴ' => 'o', 'ℭ' => 'C', 'ℌ' => 'H', 'ℑ' => 'I', 'ℜ' => 'R', 'ℨ' => 'Z', 'ℂ' => 'C', 'ℍ' => 'H', 'ℕ' => 'N',
            'ℙ' => 'P', 'ℚ' => 'Q', 'ℝ' => 'R', 'ℤ' => 'Z',
        ];
        $text = strtr($text, $small);

        return preg_replace_callback('/[\x{1D400}-\x{1D6A3}\x{1D7CE}-\x{1D7FF}\x{FF01}-\x{FF5E}]/u', function ($m) {
            $cp = mb_ord($m[0], 'UTF-8');
            if ($cp <= 0xFF5E) {                       // full-width ASCII
                return chr($cp - 0xFEE0);
            }
            if ($cp >= 0x1D7CE) {                      // mathematical digits
                return (string) (($cp - 0x1D7CE) % 10);
            }
            $i = ($cp - 0x1D400) % 52;                 // mathematical letters: 13 styles of A-Z a-z
            return $i < 26 ? chr(65 + $i) : chr(97 + $i - 26);
        }, $text);
    }
}
