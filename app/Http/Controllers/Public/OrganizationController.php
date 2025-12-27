<?php

namespace App\Http\Controllers\Public;

use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends PublicController
{
    public function about(): Response
    {
        return Inertia::render('public/about/Index', $this->getSharedData());
    }

    public function visionMission(): Response
    {
        return Inertia::render('public/vision-mission/Index', $this->getSharedData());
    }

    public function organization(): Response
    {
        return Inertia::render('public/organization/Index', $this->getSharedData());
    }

    public function legality(): Response
    {
        return Inertia::render('public/legality/Index', $this->getSharedData());
    }
}
