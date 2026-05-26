<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml for Z-Pets Store';

    public function handle(): int
    {
        $urls = [];
        $base = rtrim(config('app.url'), '/');

        $urls[] = ['loc' => $base.'/', 'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => $base.'/products', 'priority' => '0.9', 'changefreq' => 'daily'];
        $urls[] = ['loc' => $base.'/search', 'priority' => '0.5', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => $base.'/cart', 'priority' => '0.3', 'changefreq' => 'never'];

        Category::where('is_active', true)->each(function ($cat) use (&$urls, $base) {
            $urls[] = [
                'loc' => $base.'/category/'.$cat->slug,
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $cat->updated_at->toAtomString(),
            ];
        });

        Product::where('is_active', true)->each(function ($p) use (&$urls, $base) {
            $urls[] = [
                'loc' => $base.'/products/'.$p->slug,
                'priority' => '0.7',
                'changefreq' => 'weekly',
                'lastmod' => $p->updated_at->toAtomString(),
            ];
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach ($urls as $url) {
            $xml .= '  <url>'.PHP_EOL;
            $xml .= '    <loc>'.htmlspecialchars($url['loc']).'</loc>'.PHP_EOL;
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'.PHP_EOL;
            $xml .= '    <priority>'.$url['priority'].'</priority>'.PHP_EOL;
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'.PHP_EOL;
            }
            $xml .= '  </url>'.PHP_EOL;
        }
        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated: '.count($urls).' URLs');
        $this->info('File: '.public_path('sitemap.xml'));

        return self::SUCCESS;
    }
}
