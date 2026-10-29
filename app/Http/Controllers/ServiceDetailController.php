<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceDetailController extends Controller
{
    public function __invoke(string $slug): View
    {
        // 404 jika slug tidak ada atau bidang usaha disembunyikan admin
        $service = Service::active()->with('items')->where('slug', $slug)->firstOrFail();

        return view('service-detail', ['service' => $service->toDetail()]);
    }
}
