<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddressBook;
use App\Models\CustomerInfo;

class CustomerInfoSeeder extends Seeder
{
    public function run()
    {
        CustomerInfo::create([
            'name'                          => 'Amir',
            'phone'                         => '0127335606',
            'address'                       => 'Bsp Skypark',
            'email'                         => 'amir@yahoo.com',
            'postcode'                      => 40150,
            'city'                          => 'Jenjarom',
            'state'                         => 'Selangor',
            'user_id'                       => 1,
            'is_default_shipping_address'   => true,
            'is_default_billing_address'    => true,
        ]);
    }
}
