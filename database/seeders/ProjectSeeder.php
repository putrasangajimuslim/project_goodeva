<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shoppingCart = array(
            array(
                "title" => "Shopping Cart",
                'description' => 'Membuat website online shop',
                'category' => 'Codeigniter',
            ),
            array(
                "title" => "Klinik",
                'description' => 'Membuat website reservasi klinik',
                'category' => 'Codeigniter',
            ),
            array(
                "title" => "Sistem Parkir",
                'description' => 'Membuat website vallet parkir',
                'category' => 'Laravel',
            ),
            array(
                "title" => "Laravel Vue Vite",
                'description' => 'Membuat website crud dengan vite vue laravel',
                'category' => 'Laravel vue vite'
            )
        );

        foreach ($shoppingCart as $item) {
            DB::table('projects')->insert([
                'title' => $item['title'],
                'description' => $item['description'],
                'category' => $item['category'],
                'created_at' => now()
            ]);
        }
    }
}
