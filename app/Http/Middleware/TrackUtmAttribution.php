<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUtmAttribution
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin*') && ! $request->expectsJson()) {
            $utmSource = $request->query('utm_source');
            $utmMedium = $request->query('utm_medium');
            $utmCampaign = $request->query('utm_campaign');
            $utmContent = $request->query('utm_content');

            if (! session()->has('attribution.landing_page')) {
                session()->put('attribution.landing_page', $request->fullUrl());
            }

            if (! session()->has('attribution.referrer') && $request->headers->get('referer')) {
                session()->put('attribution.referrer', $request->headers->get('referer'));
            }

            $touchLabel = $utmSource
                ? trim($utmSource . ($utmMedium ? ' / ' . $utmMedium : ''))
                : ($request->headers->get('referer') ? parse_url($request->headers->get('referer'), PHP_URL_HOST) : 'direct');

            if (! session()->has('attribution.first_touch_source')) {
                session()->put('attribution.first_touch_source', $touchLabel);
            }

            if ($utmSource || $utmMedium || $utmCampaign || $utmContent) {
                session()->put('attribution.utm_source', $utmSource);
                session()->put('attribution.utm_medium', $utmMedium);
                session()->put('attribution.utm_campaign', $utmCampaign);
                session()->put('attribution.utm_content', $utmContent);
                session()->put('attribution.latest_touch_source', $touchLabel);
            } elseif (! session()->has('attribution.latest_touch_source')) {
                session()->put('attribution.latest_touch_source', $touchLabel);
            }
        }

        return $next($request);
    }
}
