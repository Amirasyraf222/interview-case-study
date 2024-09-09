<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\CustomerInfo;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use DB;

class ProfileRepository extends GeneralRepository
{
    public function index()
    {
        try {

            $data = [
                'DataArray' => $this->toArray(
                    CustomerInfo::where('user_id',Auth::user()->id)->get()->toArray()
                )
            ];
            if (!empty($data)) {
                return $this->response(
                    200,
                    'Successfully retrieved record.',
                    $data
                );
            } else {
                return $this->response(
                    404,
                    'Record not available.'
                );
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->response(
                404,
                'Record not available.'
            );
        }
    }

    public function toArray($results)
    {
        $data = [];

        foreach ($results as $key => $result) {
            $data[$key] = [
                'id'                    => $result['id'],
                'user_id'               => $result['user_id'],
                'email'                 => $result['email'],
                'name'                   => $result['name'],
                'phone'                  => $result['phone'],
                'address'               => $result['address'],
            ];
        }
        return $data;
    }

    public function store(Request $request)
    {
        CustomerInfo::where('user_id',Auth::user()->id)->update([
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return $this->response(
            200,
            'Successfully update record.',
        );
    }
}