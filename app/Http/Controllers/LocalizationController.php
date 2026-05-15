<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LocalizationController extends Controller
{
    public function index()
    {
        $locale = session('locale', 'en');
        app()->setLocale($locale);

        syncLangFiles('messages');

        return Inertia::render('localization/Index');
    }

    public function changeLanguage(string $locale)
    {
        $availableLocales = ['en', 'es'];

        if (! in_array($locale, $availableLocales)) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return to_route('localization.index');
    }
}
