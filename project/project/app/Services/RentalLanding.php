<?php
namespace App\Services;

class RentalLanding
{
    public static function starter(): array
    {
        return ['html' => '<section class="hero"><p class="eyebrow">WELCOME TO OUR COMMUNITY</p><h1>Your next chapter starts here.</h1><p>Discover social services for your favourite platforms, with clear prices and easy ordering.</p><div class="cards"><article><h2>Choose your service</h2><p>Find the right option for your goals.</p></article><article><h2>Make it yours</h2><p>Enter your link and choose a quantity.</p></article><article><h2>Stay informed</h2><p>Follow your order with a private tracking link.</p></article></div></section>',
            'css' => 'body{margin:0;background:#f4f7ff;color:#15213c;font:16px/1.7 system-ui}.hero{max-width:1080px;margin:auto;padding:64px 24px}.eyebrow{color:#365de5;letter-spacing:.18em;font-size:12px;font-weight:700}h1{font-size:clamp(32px,6vw,60px);line-height:1.08;max-width:720px}.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:40px}article{padding:24px;background:white;border-radius:20px}h2{font-size:20px}@media(max-width:650px){.cards{grid-template-columns:1fr}.hero{padding:32px 20px}}'];
    }

    /** Defence in depth: allowlisted content inside an opaque-origin, script-free iframe. */
    public static function document(array $template): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $dom->loadHTML('<?xml encoding="UTF-8"><body>'.($template['html'] ?? '').'</body>', LIBXML_NONET);
            $allowed = ['html','body','section','div','article','header','footer','main','nav','aside','h1','h2','h3','h4','h5','h6','p','span','strong','em','b','i','u','small','br','hr','ul','ol','li','img','figure','figcaption','blockquote','a'];
            $nodes = iterator_to_array($dom->getElementsByTagName('*'));
            foreach ($nodes as $node) {
                if (!in_array($node->tagName, $allowed, true)) {
                    $node->parentNode?->removeChild($node);
                    continue;
                }
                foreach (iterator_to_array($node->attributes) as $attribute) {
                    if (!in_array($attribute->name, ['class','id','style','alt','title','src','width','height'], true)
                        || ($attribute->name === 'src' && ($node->tagName !== 'img' || !preg_match('#^https://#i', $attribute->value)))) {
                        $node->removeAttribute($attribute->name);
                    }
                }
            }
            $body = $dom->getElementsByTagName('body')->item(0);
            $html = '';
            if ($body) foreach ($body->childNodes as $child) $html .= $dom->saveHTML($child);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
        $css = str_replace('<', '\\3c ', $template['css'] ?? '');
        return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta http-equiv="Content-Security-Policy" content="default-src \'none\'; style-src \'unsafe-inline\'; img-src https:; base-uri \'none\'; form-action \'none\'"><meta name="referrer" content="no-referrer"><style>*{box-sizing:border-box}img{max-width:100%;height:auto}body{overflow-wrap:anywhere}'.$css.'</style></head><body>'.$html.'</body></html>';
    }
}
