<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartApiController extends ApiController
{
    protected $cart;

    function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $results = $this->cart->list();

        return $this->formatResponse($results);
    }

    public function destroy($id)
    {
        $results = $this->cart->remove($id);

        return $this->formatResponse($results);
    }
}
