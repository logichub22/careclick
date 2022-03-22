<?php

use App\User;
use Illuminate\Support\Str;
use App\Models\General\Role;
use App\Models\General\Group;
use App\Models\General\Wallet;
use App\Models\General\Country;
use App\Models\General\Currency;
use App\Models\General\Service;
use App\Models\General\Document;
use Illuminate\Database\Seeder;
use App\Models\General\UserDetail;
use App\Models\General\GroupWallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationDetail;
use App\Models\Organization\OrganizationWallet;
use App\Models\General\Marital;
use App\Models\General\Gender;
use App\Models\General\ResidentType;
use App\Models\General\IncomeClass;
use App\Models\General\SettingCategory;
use App\Models\General\InterestModel;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new Role();
        $superadmin->name = 'superadmin';
        $superadmin->description = 'System administrator responsible for overseeing the general working of system';
        $superadmin->save();

        $admin = new Role();
        $admin->name = 'admin';
        $admin->description = 'Organization administrator who can add and manage his or her own users';
        $admin->save();

        $sp = new Role();
        $sp->name = 'service-provider';
        $sp->description = 'User who can provide services such as insurance, farming etc';
        $sp->save();

        $userr = new Role();
        $userr->name = 'normal-user';
        $userr->description = 'Normal user in the system who does not belong to any organization';
        $userr->save();

        $org_user = new Role();
        $org_user->name = 'organization-user';
        $org_user->description = 'User who belongs to a certain organization';
        $org_user->save();

        $hasGroup = new Role();
        $hasGroup->name = 'group-member';
        $hasGroup->description = 'User who belongs to a certain savings group';
        $hasGroup->save();

        $facilitator = new Role();
        $facilitator->name = 'group-admin';
        $facilitator->description = 'Member in charge of a group (teacher)';
        $facilitator->save();

        $trainer = new Role();
        $trainer->name = 'trainer';
        $trainer->description = 'User who is responsible for training a group';
        $trainer->save();

        $fed = new Role();
        $fed->name = 'super-organization-admin';
        $fed->description = 'User who can oversee and manage other associations';
        $fed->save();

        $super = new User;
        $super->name = 'Support';
        $super->other_names = 'Admin';
        $super->email = 'super@jamborow.co.uk';
        $super->msisdn = '254736355183';
        $super->token = Str::random(40);;
        $super->password = bcrypt('@dev20!8');
        $super->verified = true;
        $super->status = true;
        $super->save();

        $super->attachRole(Role::where('name', 'superadmin')->first());

        $file = base_path() . '/public/countries.txt';
        $data = json_decode(file_get_contents($file));

        foreach ($data->your_response as $content) {
        	$name = $content->name;
        	$country = new Country();
        	$country->name = $name;
        	$country->save();
        }

        $algerian_dinar = new Currency;
        $algerian_dinar->name = 'Algerian Dinar';
        $algerian_dinar->prefix = 'DZD';
        $algerian_dinar->save();

        $kwanza = new Currency;
        $kwanza->name = 'Kwanza';
        $kwanza->prefix = 'AOA';
        $kwanza->save();

        $xof_franc = new Currency;
        $xof_franc->name = 'West African CFA Franc';
        $xof_franc->country_id = '88';
        $xof_franc->prefix = 'XOF';
        $xof_franc->save();

        $xaf_franc = new Currency;
        $xaf_franc->name = 'Central African CFA Franc BEAC';
        $xaf_franc->prefix = 'XAF';
        $xaf_franc->save();

        $pula = new Currency;
        $pula->name = 'Pula';
        $pula->country_id = '25';
        $pula->prefix = 'BWP';
        $pula->save();

        $burundi_franc = new Currency;
        $burundi_franc->name = 'Burundi Franc';
        $burundi_franc->prefix = 'BIF';
        $burundi_franc->save();

        $escudo = new Currency;
        $escudo->name = 'Cape Verde Escudo';
        $escudo->prefix = 'CVE';
        $escudo->save();
        
        $naira = new Currency;
        $naira->name = 'Naira';
        $naira->country_id = '131';
        $naira->prefix = 'NGN';
        $naira->save();

        $comoros_franc = new Currency;
        $comoros_franc->name = 'Comoros Franc';
        $comoros_franc->prefix = 'KMF';
        $comoros_franc->save();

        $congo_franc = new Currency;
        $congo_franc->name = 'Congo Francs';
        $congo_franc->prefix = 'CDF';
        $congo_franc->save();

        $djibouti_franc = new Currency;
        $djibouti_franc->name = 'Djibouti Franc';
        $djibouti_franc->prefix = 'DJF';
        $djibouti_franc->save();

        $egyptian_pound = new Currency;
        $egyptian_pound->name = 'Egyptian Pound';
        $egyptian_pound->prefix = 'EGP';
        $egyptian_pound->save();

        $eriterian_nakfa = new Currency;
        $eriterian_nakfa->name = 'Eriterian Nakfa';
        $eriterian_nakfa->prefix = 'ERN';
        $eriterian_nakfa->save();

        $birr = new Currency;
        $birr->name = 'Birr';
        $birr->prefix = 'ETB';
        $birr->save();

        $dalasi = new Currency;
        $dalasi->name = 'Dalasi';
        $dalasi->prefix = 'GMD';
        $dalasi->save();

        $cedi = new Currency;
        $cedi->name = 'Cedi';
        $cedi->country_id = '69';
        $cedi->prefix = 'GHS';
        $cedi->save();

        $guinea_franc = new Currency;
        $guinea_franc->name = 'Guinea Franc';
        $guinea_franc->prefix = 'GNF';
        $guinea_franc->save();

        $guinea_bissau_peso = new Currency;
        $guinea_bissau_peso->name = 'Guinea-Bissau Peso';
        $guinea_bissau_peso->prefix = 'GWP';
        $guinea_bissau_peso->save();

        $kes = new Currency;
        $kes->name = 'Kenya Shillings';
        $kes->country_id = '93';
        $kes->prefix = 'KES';
        $kes->save();

        $loti = new Currency;
        $loti->name = 'Loti';
        $loti->prefix = 'LSL';
        $loti->save();

        $liberian_dollar = new Currency;
        $liberian_dollar->name = 'Liberian Dollar';
        $liberian_dollar->country_id = '101';
        $liberian_dollar->prefix = 'LRD';
        $liberian_dollar->save();

        $libyan_dinar = new Currency;
        $libyan_dinar->name = 'Libyan Dinar';
        $libyan_dinar->prefix = 'LYD';
        $libyan_dinar->save();

        $malagasy_ariary = new Currency;
        $malagasy_ariary->name = 'Malagasy ariary';
        $malagasy_ariary->prefix = 'MGA';
        $malagasy_ariary->save();

        $malawi_kwacha = new Currency;
        $malawi_kwacha->name = 'Malawi Kwacha';
        $malawi_kwacha->prefix = 'MWK';
        $malawi_kwacha->save();

        $ouguiya = new Currency;
        $ouguiya->name = 'Ouguiya';
        $ouguiya->prefix = 'MRO';
        $ouguiya->save();

        $mauritius_rupees = new Currency;
        $mauritius_rupees->name = 'Rupees';
        $mauritius_rupees->prefix = 'MUR';
        $mauritius_rupees->save();

        $dirham = new Currency;
        $dirham->name = 'Dirham';
        $dirham->prefix = 'MAD';
        $dirham->save();

        $metical = new Currency;
        $metical->name = 'Metical';
        $metical->country_id = '124';
        $metical->prefix = 'MZN';
        $metical->save();

        $namibian_dollar = new Currency;
        $namibian_dollar->name = 'Namibian Dollar';
        $namibian_dollar->prefix = 'NAD';
        $namibian_dollar->save();

     

        $reunion_euro = new Currency;
        $reunion_euro->name = 'Réunion Euro';
        $reunion_euro->prefix = 'EUR';
        $reunion_euro->save();

        $rwandan_franc = new Currency;
        $rwandan_franc->name = 'Rwandan Franc';
        $rwandan_franc->prefix = 'RWF';
        $rwandan_franc->save();

        $dobra = new Currency;
        $dobra->name = 'Dobra';
        $dobra->prefix = 'STD';
        $dobra->save();

        $seychelles_rupees = new Currency;
        $seychelles_rupees->name = 'Seychelles Rupees';
        $seychelles_rupees->prefix = 'SCR';
        $seychelles_rupees->save();

        $leone = new Currency;
        $leone->name = 'Leone';
        $leone->country_id = '159';
        $leone->prefix = 'SLL';
        $leone->save();

        $somalian_shillings = new Currency;
        $somalian_shillings->name = 'Somalian Shillings';
        $somalian_shillings->prefix = 'SOS';
        $somalian_shillings->save();

        $rand = new Currency;
        $rand->name = 'Rand';
        $rand->prefix = 'ZAR';
        $rand->save();

        $south_sudan_pound = new Currency;
        $south_sudan_pound->name = 'South Sudan Pound';
        $south_sudan_pound->prefix = 'SSP';
        $south_sudan_pound->save();

        $sudan_pound = new Currency;
        $sudan_pound->name = 'Sudan Pound';
        $sudan_pound->prefix = 'SDG';
        $sudan_pound->save();

        $lilangeni = new Currency;
        $lilangeni->name = 'Lilangeni';
        $lilangeni->prefix = 'SZL';
        $lilangeni->save();

        $tanzanian_shillings = new Currency;
        $tanzanian_shillings->name = 'Tanzanian Shillings';
        $tanzanian_shillings->country_id = '177';
        $tanzanian_shillings->prefix = 'TZS';
        $tanzanian_shillings->save();

        $tunisian_dinar = new Currency;
        $tunisian_dinar->name = 'Tunisian Dinar';
        $tunisian_dinar->prefix = 'TND';
        $tunisian_dinar->save();

        $ugandan_shillings = new Currency;
        $ugandan_shillings->name = 'Ugandan Shillings';
        $ugandan_shillings->country_id = '186';
        $ugandan_shillings->prefix = 'UGX';
        $ugandan_shillings->save();

        $zambian_kwacha = new Currency;
        $zambian_kwacha->name = 'Zambian Kwacha';
        $zambian_kwacha->country_id = '197';
        $zambian_kwacha->prefix = 'ZMW';
        $zambian_kwacha->save();

        $zimbabwean_dollar = new Currency;
        $zimbabwean_dollar->name = 'Dollar';
        $zimbabwean_dollar->prefix = 'ZWD';
        $zimbabwean_dollar->save();

        // $dollar = new Currency;
        // $dollar->name = 'American Dollar';
        // $dollar->save();

        // $pound = new Currency;
        // $pound->name = 'Sterling Pound';
        // $pound->save();

        $id = new Document();
        $id->name = 'National ID';
        $id->save();

        $passport = new Document();
        $passport->name = 'Passport';
        $passport->save();

        $driving = new Document();
        $driving->name = 'Driving License';
        $driving->save();

        $voter = new Document();
        $voter->name = 'Voter ID';
        $voter->save();

        $insurance = new Service;
        $insurance->name = 'Insurance';
        $insurance->description = 'A risk management service aimed towards protection against financial loss';
        $insurance->save();

        $goods = new Service;
        $goods->name = 'Products';
        $goods->description = 'Service aimed at providing tangible amenities sucha s land and farm produce';
        $goods->save();

        // Create Gender
        $male = new Gender();
        $male->name = 'Male';
        $male->save();

        $female = new Gender();
        $female->name = 'Female';
        $female->save();

        // Create marital statuses
        $married = new Marital();
        $married->name = 'Married';
        $married->save();

        $single = new Marital();
        $single->name = 'Single';
        $single->save();

        $unspec = new Marital();
        $unspec->name = 'Not Specified';
        $unspec->save();

        // Create resident types
        $res1 = new ResidentType();
        $res1->name = 'Residential';
        $res1->save();

        $res2 = new ResidentType();
        $res2->name = 'Non-Residential';
        $res2->save();

        $res3 = new ResidentType();
        $res3->name = 'Foreign National';
        $res3->save();

        // Create income classes
        $c1 = new IncomeClass();
        $c1->name = 'Below 50,000';
        $c1->save();

        $c2 = new IncomeClass();
        $c2->name = '50,001 - 250,000';
        $c2->save();

        $c3 = new IncomeClass();
        $c3->name = '250,001 - 500,000';
        $c3->save();

        $c4 = new IncomeClass();
        $c4->name = '500,001 - less than 1 million';
        $c4->save();

        $c5 = new IncomeClass();
        $c5->name = '1 million - Less than 5 million';
        $c5->save();

        $c6 = new IncomeClass();
        $c6->name = '5million - Less than 10million';
        $c6->save();

        $c6 = new IncomeClass();
        $c6->name = 'Above 20 million';
        $c6->save();

        $c7 = new IncomeClass();
        $c7->name = 'Not Specified';
        $c7->save();

        $setCat1 = new SettingCategory();
        $setCat1->name = 'Group';
        $setCat1->save();

        $setCat2 = new SettingCategory();
        $setCat2->name = 'Currency';
        $setCat2->save();

        $setCat3 = new SettingCategory();
        $setCat3->name = 'Chat';
        $setCat3->save();

        $model1 = new InterestModel;
        $model1->name = 'STRAIGHT-LINE';
        $model1->save();
    }
}
