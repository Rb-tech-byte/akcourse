<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\View;

class ActiveTemplateMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $viewShare['activeTemplate']     = activeTemplate();
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        view()->share($viewShare);

        view()->composer(['Template::partials.header', 'templates.basic.partials.header', 'Template::partials.footer', 'templates.basic.partials.footer'], function ($view) {
            $pages = Page::where('is_default', Status::NO)
                ->where('tempname', activeTemplate())
                ->orderBy('id', 'asc')
                ->get();

            $headerSeries = collect();
            try {
                $headerSeries = \Illuminate\Support\Facades\DB::connection('ak23')
                    ->table('series')
                    ->where('status', '!=', 'archived')
                    ->orderBy('sequence_no', 'asc')
                    ->get(['id', 'slug', 'title', 'short_description']);
            } catch (\Throwable $e) {
                $headerSeries = \App\Models\Category::active()->get();
            }

            $view->with([
                'pages'        => $pages,
                'headerSeries' => $headerSeries,
            ]);
        });

        View::addNamespace('Template',resource_path('views/templates/'.activeTemplateName()));
        return $next($request);
    }
}
