<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderApiController extends ApiController
{
    protected $order;

    function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    public function index()
    {
        $results = $this->order->index();

        return $this->formatResponse($results);
    }

    public function pending()
    {
        $results = $this->order->pending();

        return $this->formatResponse($results);
    }

    public function store(Request $request)
    {
        $results = $this->order->store($request->all());

        return $this->formatResponse($results);
    }

    public function payment(Request $request)
    {
        $results = $this->order->payment($request->all());

        return $this->formatResponse($results);
    }
}
