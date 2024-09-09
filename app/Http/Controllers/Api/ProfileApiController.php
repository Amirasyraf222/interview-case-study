<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;

class ProfileApiController extends ApiController
{
    protected $profile;

    function __construct(ProfileRepository $profile)
    {
        $this->profile = $profile;
    }

    public function index()
    {
        $results = $this->profile->index();

        return $this->formatResponse($results);
    }

    public function store(Request $request)
    {
        $results = $this->profile->store($request);

        return $this->formatResponse($results);
    }

}
