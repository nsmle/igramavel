<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Contracts\View\{View, Factory};

class HomeController extends Controller
{
    /**
     * Api endpoints documentation page.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function __invoke(Request $request): View|Factory
    {
        $docsFile = '/docs/open-api.json';

        $seo = [
            "title" => config('app.name') . "- Unofficial Instagram RESTful API",
            "description" => "An unofficial Instagram RESTful API. easy  to fetch any feed and interact with Instagram (like, follow, etc.) with JWT token implementation.",
            "keywords" => "instagram,instagram-api,instagram-feed,instagram-scraper,instagram-crawler,instagram-client,instagram-photos,instagram-stories,instagram-bot,instagram-post,instagram-posts,instagram-downloader,instagram-private,instagram-private-api,instagram-story-downloader,instagram-private-downloader,rest-api,jwt,php,laravel",
            "language" => config('app.locale'),
            "author" => "nsmle, hello@fiki.tech",
            "robots" => app()->environment('production') ? "index, follow" : "noindex, nofollow",
            "Googlebot" => app()->environment('production') ? "index, follow" : "noindex, nofollow",
            "revised" => "Friday, February 18th, 2023, 7:45 pm",
            "url" => route('homepage'),
            "canonical" => "https://github.com/nsmle/igramapi",
        ];
        $openGraphSeo = [
            "og:title" => $seo['title'],
            "og:type" => "website",
            "og:url" => $seo['url'],
            "og:image" => asset('images/banner.png'),
            "og:site_name" => config('app.name'),
            "og:description" => $seo['description'],
            "og:locale" => "en_US",
        ];
        $twitterSeo = [
            "twitter:card" => "summary_large_image",
            "twitter:url" => $seo['url'],
            "twitter:title" => $seo['title'],
            "twitter:description" => $seo['description'],
            "twitter:image" => $openGraphSeo['og:image'],
            "twitter:creator" => "@nsmle_",
        ];

        return view('swagger', [
            "docsFile" => $docsFile,
            "seo" => array_merge($seo, $openGraphSeo, $twitterSeo),
        ]);
    }
}
