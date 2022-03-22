<?php

use Illuminate\Database\Seeder;
use App\Models\General\RepaymentPlan;


class repayment_plansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = new RepaymentPlan();
        $plan->name = "Weekly";
        $plan->save();

        $plan = new RepaymentPlan();
        $plan->name = "Bi-Weekly";
        $plan->save();

        $plan = new RepaymentPlan();
        $plan->name = "Monthly";
        $plan->save();

        $plan = new RepaymentPlan();
        $plan->name = "Annualy";
        $plan->save();

    }
}
