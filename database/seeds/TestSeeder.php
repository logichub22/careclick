<?php
use Illuminate\Database\Seeder;
use App\Models\General\LoanPackage;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\Models\General\UserDetail;
use App\Models\General\Role;
use App\Models\General\Group;
use App\Models\General\Document;
use App\Models\General\GroupWallet;
use App\Models\Organization\OrganizationDetail;
use App\Models\Organization\OrganizationWallet;
use App\Models\General\Marital;
use App\Models\General\Gender;
use App\Models\General\ResidentType;
use App\Models\General\IncomeClass;
use App\Models\General\Loan;
use App\SavingsWallet;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Organization';
        $user->other_names = 'Admin';
        $user->email = 'test@admin.com';
        $user->msisdn = '234123456789';
        // $user->country = "Nigeria";
        $user->token = Str::random(40);;
        $user->password = bcrypt('@test20!8');
        $user->verified = true;
        $user->status = true;
        $user->save();

        // Update User Wallet Table
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 200000;
        $wallet->save();

        //Update Savings Wallet Table
        $savings_wallet = new SavingsWallet;
        $savings_wallet->org_id = $user->id;
        $savings_wallet->user_id = $user->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();


        $userDetailAdmin = new UserDetail;
        $userDetailAdmin->user_id = $user->id;
        $userDetailAdmin->gender = Gender::all()->random()->id;
        $userDetailAdmin->marital_status = Marital::all()->random()->id;
        $userDetailAdmin->doc_type = Document::all()->random()->id;
        $userDetailAdmin->doc_no = '30406010';
        $userDetailAdmin->dob = \Carbon\Carbon::today();
        $userDetailAdmin->residence = ResidentType::all()->random()->id;
        $userDetailAdmin->country = 131;
        $userDetailAdmin->city = 'Abuja';
        $userDetailAdmin->state = 'Bomoko';
        $userDetailAdmin->postal_code = '020';
        $userDetailAdmin->address = 'Maine Street, Bomoko';
        $userDetailAdmin->income = IncomeClass::all()->random()->id;
        $userDetailAdmin->occupation = 'Farmer';
        $userDetailAdmin->save();


        // Federations User
        $federationAdmin = new User;
        $federationAdmin->name = 'Test';
        $federationAdmin->other_names = 'Federation';
        $federationAdmin->email = 'test@federation.com';
        $federationAdmin->msisdn = '255768130321';
        $federationAdmin->token = Str::random(40);;
        $federationAdmin->password = bcrypt('password');
        $federationAdmin->verified = true;
        $federationAdmin->status = true;
        $federationAdmin->save();

        // Update Fed User Wallet Table
        $fedUserWallet = new Wallet;
        $fedUserWallet->user_id = $federationAdmin->id;
        $fedUserWallet->balance = 500000;
        $fedUserWallet->save();

        $fedOrg = new Organization;
        $fedOrg->admin_id = $federationAdmin->id;
        $fedOrg->verified = true;
        $fedOrg->status = true;
        $fedOrg->save();

        $fedDetail = new OrganizationDetail;
        $fedDetail->org_id = $fedOrg->id;
        $fedDetail->name = 'Federation';
        $fedDetail->domain = 'http://www..org/';
        $fedDetail->address = 'Lagos, Nigeria';
        $fedDetail->country = 131;
        $fedDetail->org_email = 'support@federation.org';
        $fedDetail->org_msisdn = '254742412800';
        $fedDetail->project_id = random_int(1, 5);
        $fedDetail->is_financial = 2;
        $fedDetail->status = true;
        $fedDetail->save();

        // Create Wallet For Third Firm
        $fedWallet = new OrganizationWallet();
        $fedWallet->org_id = $fedOrg->id;
        $fedWallet->balance = 0;
        $fedWallet->save();
        
        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $federationAdmin->id;
        $savings_wallet->user_id = $fedOrg->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        $insuradmin = new User;
        $insuradmin->name = 'Apa';
        $insuradmin->other_names = 'Admin';
        $insuradmin->email = 'insurance@insurance.com';
        $insuradmin->msisdn = '254727315115';
        $insuradmin->token = Str::random(40);;
        $insuradmin->password = bcrypt('123456');
        $insuradmin->verified = true;
        $insuradmin->status = true;
        $insuradmin->save();

        $thirdadmin = new User;
        $thirdadmin->name = 'Third';
        $thirdadmin->other_names = 'Admin';
        $thirdadmin->email = 'admin2@admin2.com';
        $thirdadmin->msisdn = '254727315098';
        $thirdadmin->token = Str::random(40);;
        $thirdadmin->password = bcrypt('123456');
        $thirdadmin->verified = true;
        $thirdadmin->status = true;
        $thirdadmin->save();

        $wallet = new Wallet;
        $wallet->user_id = $insuradmin->id;
        $wallet->balance = 700000;
        $wallet->save();
        
        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $insuradmin->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        $normuser = new User;
        $normuser->name = 'Normal';
        $normuser->other_names = 'User';
        $normuser->email = 'user@user.com';
        $normuser->msisdn = '234123456780';
        $normuser->token = Str::random(40);;
        $normuser->password = bcrypt('123456');
        $normuser->verified = true;
        $normuser->status = true;
        $normuser->save();

        $normdetail = new UserDetail;
        $normdetail->user_id = $normuser->id;
        $normdetail->gender = Gender::all()->random()->id;
        $normdetail->marital_status = Marital::all()->random()->id;
        $normdetail->doc_type = Document::all()->random()->id;
        $normdetail->doc_no = '30406010';
        $normdetail->dob = \Carbon\Carbon::today();
        $normdetail->residence = ResidentType::all()->random()->id;
        $normdetail->country = 131;
        $normdetail->city = 'Abuja';
        $normdetail->state = 'Bomoko';
        $normdetail->postal_code = '020';
        $normdetail->address = 'Maine Street, Bomoko';
        $normdetail->income = IncomeClass::all()->random()->id;
        $normdetail->occupation = 'Farmer';
        $normdetail->save();

        // Update User Wallet Table
        $wallet = new Wallet;
        $wallet->user_id = $normuser->id;
        $wallet->balance = 45000;
        $wallet->save();

        //Update Savings Wallet Table
        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $normuser->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        $normuser2 = new User;
        $normuser2->name = 'John';
        $normuser2->other_names = 'Doe';
        $normuser2->email = 'john@example.com';
        $normuser2->msisdn = '234123456787';
        $normuser2->token = Str::random(40);;
        $normuser2->password = bcrypt('123456');
        $normuser2->verified = true;
        $normuser2->status = true;
        $normuser2->save();

        // Update User2 Wallet Table
        $wallet = new Wallet;
        $wallet->user_id = $normuser2->id;
        $wallet->balance = 38000;
        $wallet->save();

        // Update User2 Savings Wallet Table
        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $normuser2->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        $user->attachRole(Role::where('name', 'admin')->first());
        $normuser->attachRole(Role::where('name', 'normal-user')->first());
        $normuser2->attachRole(Role::where('name', 'normal-user')->first());
        $insuradmin->attachRole(Role::where('name', 'service-provider')->first());
        $federationAdmin->attachRole(Role::where('name', 'super-organization-admin')->first());

        //$this->command->info('Creating default organization for admin user');

        $organization = new Organization;
        $organization->admin_id = $user->id;
        $organization->verified = true;
        $organization->status = true;
        $organization->save();

        $detail = new OrganizationDetail;
        $detail->org_id = $organization->id;
        $detail->name = 'Jamborow Limited';
        $detail->domain = 'https://jamborow.co.uk';
        $detail->address = 'Abuja, Nigeria';
        $detail->country = 131;
        $detail->org_email = 'operations@jamborow.co.uk';
        $detail->org_msisdn = '254720529864';
        $detail->project_id = random_int(1, 5);
        $detail->status = true;
        $detail->save();

        // Create Wallet For Organization
        $orgWallet = new OrganizationWallet();
        $orgWallet->org_id = $organization->id;
        $orgWallet->balance = 100000;
        $orgWallet->save();

       // $this->command->info('Creating default insurance company');

        $insurance = new Organization;
        $insurance->admin_id = $insuradmin->id;
        $insurance->verified = true;
        $insurance->status = true;
        $insurance->save();

        $detail2 = new OrganizationDetail;
        $detail2->org_id = $insurance->id;
        $detail2->name = 'APA Insurance';
        $detail2->domain = 'http://www.apainsurance.org/';
        $detail2->address = 'Ring Road Parklands';
        $detail2->country = 131;
        $detail2->org_email = 'support@apa.org';
        $detail2->org_msisdn = '254719412800';
        $detail2->project_id = random_int(1, 5);
        $detail2->status = true;
        $detail2->save();

        // Create Wallet For Insurance Firm
        $orgWallet = new OrganizationWallet();
        $orgWallet->org_id = $insurance->id;
        $orgWallet->balance = 240000;
        $orgWallet->save();
        
        

        $third = new Organization;
        $third->admin_id = $thirdadmin->id;
        $third->verified = true;
        $third->status = true;
        $third->save();

        $detail3 = new OrganizationDetail;
        $detail3->org_id = $third->id;
        $detail3->name = 'Third Organization';
        $detail3->domain = 'http://www.thirdorg.org/';
        $detail3->address = 'Lagos, Nigeria';
        $detail3->country = 131;
        $detail3->org_email = 'support@third.org';
        $detail3->org_msisdn = '254722412800';
        $detail3->project_id = random_int(1, 5);
        $detail3->status = true;
        $detail3->save();

        // Create Wallet For Third Firm
        $orgWallet = new OrganizationWallet();
        $orgWallet->org_id = $third->id;
        $orgWallet->balance = 0;
        $orgWallet->save();
        

        $package = new LoanPackage();
        $package->user_id = $normuser->id;
        $package->name = "First Package";
        $package->repayment_plan = "monthly";
        $package->min_credit_score = 1;
        $package->currency = "NGN";
        // $package->max_credit_score = 6;
        $package->insured = false;
        $package->min_amount = 100000;
        $package->max_amount = 500000;
        $package->interest_rate = 14.5;
        $package->save();

        $package2 = new LoanPackage();
        $package2->org_id = 1;
        $package2->name = "Second Package";
        $package2->repayment_plan = "weekly";
        $package2->min_credit_score = 5;
        $package->currency = "NGN";
        // $package2->max_credit_score = 8;
        $package2->insured = true;
        $package2->min_amount = 2000;
        $package2->max_amount = 10000;
        $package2->interest_rate = 10;
        $package2->save();

        $package3 = new LoanPackage();
        $package3->org_id = Organization::all()->random()->id;
        $package3->name = "Third Package";
        $package3->repayment_plan = "bi-weekly";
        $package3->min_credit_score = 8;
        $package->currency = "NGN";
        // $package3->max_credit_score = 10;
        $package3->insured = false;
        $package3->min_amount = 100000;
        $package3->max_amount = 250000;
        $package3->interest_rate = 20;
        $package3->save();

        $package3 = new LoanPackage();
        $package3->user_id = 4;
        $package3->name = "Fourth Package";
        $package3->repayment_plan = "monthly";
        $package3->min_credit_score = 3;
        $package->currency = "NGN";
        // $package3->max_credit_score = 10;
        $package3->insured = false;
        $package3->min_amount = 60000;
        $package3->max_amount = 200000;
        $package3->interest_rate = 30;
        $package3->save();

        $loan = new Loan();
        $loan->loan_title = "Loan 1";
        $loan->user_id = 2;
        $loan->org_id = 2;
        $loan->loan_package_id = 3;
        $loan->amount = 100000;
        $loan->borrower_credit_score = 8;
        $loan->status = 0;
        $loan->save();
    }
}
// 