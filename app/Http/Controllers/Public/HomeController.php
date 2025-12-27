<?php

namespace App\Http\Controllers\Public;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('public/home/Index', [
            'topbarMenu' => MenuHelper::getPublicMenu('topbar'),
            'navbarMenu' => MenuHelper::getPublicMenu('navbar'),
            'footerMenu' => MenuHelper::getPublicMenu('footer'),
        ]);
    }
}
