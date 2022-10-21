<?php

namespace Database\Seeders;

use App\Models\Finance\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run()
    {
        $terms = ['12', '18', '24', '36', '48', '60'];
        foreach ($terms as $term) {
            $option = new Term();
            $option->option = $term;
            $option->save();
        }
    }
}
