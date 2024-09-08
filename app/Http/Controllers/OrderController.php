<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Order/Index');
    }

    public function payment()
    {
        return Inertia::render('Order/Payment');
    }
}
