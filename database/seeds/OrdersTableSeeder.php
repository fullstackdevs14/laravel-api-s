<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->delete();

        $users = \App\ApplicationUser::get();

        foreach ($users as $user) {

            if($user['email'] !== 'thomasbourcy@live.com' && $user['email'] !== 'fanny@sipperapp.com' && $user['email'] !== 'test@test.com')
            {

                //Create orders
                for ($i = 0; $i < rand(1, 10); ++$i) {

                    //$faker = Faker::create('fr_FR');
                    $orderId = strtoupper(substr(uniqid(), 8, 11));

                    $partner = \App\PartnerMenu::orderByRaw("RAND()")
                        ->take(1)
                        ->get()[0];

                    $accepted = rand(0, 1);

                    if ($accepted == 1) {
                        $delivered = rand(0, 1);
                    } else {
                        $delivered = 0;
                    }

                    $order_last_insert = DB::table('orders_info')->insertGetId([
                        'applicationUser_id' => $user['id'],
                        'partner_id' => $partner['partner_id'],
                        'orderId' => $orderId,
                        'HHStatus' => rand(0, 1),
                        'accepted' => $accepted,
                        'delivered' => $delivered,
                        'incident' => 0,
                    ]);

                    //Create items
                    for ($i = 0; $i < rand(1, 10); ++$i) {

                        $item = \App\PartnerMenu::where('partner_id', $partner['partner_id'])->orderByRaw("RAND()")
                            ->take(1)
                            ->get()[0];

                        DB::table('orders')->insert([
                            'order_id' => $order_last_insert,
                            'category_id' => $item['category_id'],
                            'itemName' => $item['name'],
                            'itemPrice' => $item['price'],
                            'itemHHPrice' => $item['HHPrice'],
                            'tax' => 20.00,
                            'alcohol' => $item['alcohol'],
                            'quantity' => rand(1, 9),

                        ]);
                    }
                }
            }
        }
    }
}

