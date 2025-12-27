<?php

namespace App\Http\Controllers\Public;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    protected function getSharedData(): array
    {
        return [
            'topbarMenu' => MenuHelper::getPublicMenu('topbar'),
            'navbarMenu' => MenuHelper::getPublicMenu('navbar'),
            'footerMenu' => MenuHelper::getPublicMenu('footer'),
        ];
    }
}
