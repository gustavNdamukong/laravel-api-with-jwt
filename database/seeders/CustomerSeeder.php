<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 25 customers that each have 10 invoices
        Customer::factory()
            ->count(25)
            ->hasInvoices(10)
            ->create();

        // 100 customers that each have 5 invoices
        Customer::factory()
        ->count(100)
        ->hasInvoices(5)
        ->create();

        // 100 customers that each have 3 invoices
        Customer::factory()
            ->count(100)
            ->hasInvoices(3)
            ->create();

        // 5 customers that have no invoices
        Customer::factory()
            ->count(100)
            ->create();
    }
}
