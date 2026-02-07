<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CleanLivewireResponse
{
    /**
     * Strip any HTML/garbage that appears before Livewire's JSON response.
     * This fixes issues where spam/injected content breaks JSON parsing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process Livewire update requests
        if (!$request->is('livewire/update') && !$request->is('livewire/upload-file')) {
            return $response;
        }

        $content = $response->getContent();

        if (empty($content) || !is_string($content)) {
            return $response;
        }

        // If response starts with HTML (unexpected), extract JSON part
        $trimmed = ltrim($content);
        if (isset($trimmed[0]) && $trimmed[0] === '<') {
            // Find the first { which should be start of JSON
            $jsonStart = strpos($content, '{"components"');
            if ($jsonStart !== false) {
                $cleanJson = substr($content, $jsonStart);
                $response->setContent($cleanJson);
            }
        }

        return $response;
    }
}
