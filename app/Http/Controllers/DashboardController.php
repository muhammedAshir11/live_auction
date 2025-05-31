<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // protected $eventService;

    // public function __construct(EventService $eventService)
    // {
    //     $this->eventService = $eventService;
    // }

    public function index()
    {
        // fetch event list and details
        // $events = $this->eventService->getEvents();
        return view('dashboard');
    }
}
