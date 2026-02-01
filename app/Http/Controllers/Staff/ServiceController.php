<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'active')->paginate(12);

        return view('staff.services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);

        return view('staff.services.show', compact('service'));
    }
}