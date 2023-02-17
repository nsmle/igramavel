<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class OpenApiController extends Controller
{
    /**
     * OpenAPI file.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function __invoke(Request $request, string $fileName)
    {
        $docsFile = resource_path('docs/open-api.yaml');

        if ($fileName == 'json') {
            return response()->json(Yaml::parseFile($docsFile));
        } else if ($fileName == 'yaml') {
            return response()->file($docsFile, ['Content-Type' => 'applicaton/yaml']);
        }

        return abort(404);
    }
}
