<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group(['middleware' => 'api-header'], function () {
//     Route::post('login', 'ApiController@login');
// });
      

Route::group(['middleware' => 'api-header'], function () {
    Route::namespace('Api')->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('forgotpassword', 'AuthController@forgotPassword');
        Route::post('register', 'AuthController@register');
        Route::get('getRegisterData', 'GeneralController@getRegisterData');
        Route::get('getRoles', 'GeneralController@getRoles');
        Route::get('getAdministrativeRegions', 'GeneralController@getAdministrativeRegions');
        Route::get('getTransactionTypes', 'GeneralController@getTransactionTypes');
        Route::get('getLendingData','GeneralController@getLendingData');
        
       // Organization routes
       Route::namespace('Organization')->group(function () {
        Route::group(['prefix' => 'organization'], function () {
                Route::post('create', 'AuthController@register');
            });
        });

           
            

        Route::group(['prefix' => 'ussd'], function () {
            Route::get('getProfile/{phone}', 'UserController@getProfileUssd');
         });
        
   
        
        

        // Authenticated routes
        Route::group(['middleware' => 'auth:api'], function () {
            // User profile and data routes
            Route::get('logout', 'AuthController@logout');
            Route::post('updateProfile', 'UserController@updateProfile');
            Route::get('getProfile', 'UserController@getProfile');
            Route::post('changePassword', 'UserController@changePassword');
            Route::get('getUserNotifications', 'UserController@getUserNotifications');
            // Transactions
            Route::get('getLatestTransactions', 'UserController@getLatestTransactions');
            Route::get('getTransactions', 'UserController@getTransactions');
            Route::get('getCreditTransactions', 'UserController@getCreditTransactions');
            Route::get('getDebitTransactions', 'UserController@getDebitTransactions');
            //Loans and Loan Packages
            Route::get('getUserLoanPackages', 'UserController@getUserLoanPackages');
            
            Route::get('getUserPackageDetailsByID/{id}','UserController@getUserPackageDetailsByID');
            Route::get('getLoanBorrowerDetails/{id}','UserController@getLoanBorrowerDetails');

            // Groups
            Route::get('getGroupRegistrationData', 'GroupController@getGroupRegistrationData');
            Route::get('getLevelTwoData/{id}', 'GroupController@loadLevelTwo');
            Route::get('getLevelThreeData/{id}', 'GroupController@loadLevelThree');

            Route::get('getGroupTransactions/{id}', 'GroupController@getGroupTransactions');
            
            Route::get('getUserGroups', 'GroupController@getUserGroups');
            Route::get('getSingleUserGroup/{id}', 'GroupController@getSingleUserGroup');
            Route::post('makeContribution/{id}', 'GroupController@makeContribution');

            Route::post('createGroup', 'GroupController@createGroup');
            Route::post('addGroupMember/{id}', 'GroupController@addMember');

            //Borrowing
            //Loans packages (available for users to borrow)
            Route::get('getLatestPackages','LoansController@getLatestPackages');
            Route::get('getAllPackages','LoansController@getAllPackages');
            Route::get('getPackageDetails/{id}','LoansController@getPackageDetails');
            Route::post('loanApplicationRequest/{id}','LoansController@initiateLoanApplication');
            Route::post('confirmLoanApplication/{id}','LoansController@confirmLoanApplication');
            
            // Borrowed loans
            Route::get('getUserLoans', 'UserController@getUserLoans');
            Route::get('getUserLoanById/{id}', 'UserController@getUserLoanById');
            Route::post('repayLoan/{id}', 'UserController@repayLoan');

            Route::post('createPackage','LoansController@createPackage');

            Route::get('get-balance', 'UserController@getWalletBalance');

            Route::get('checkBalance', 'UserController@checkBalance');
            Route::post('creditWallet', 'UserController@creditWallet');
            Route::post('debitWallet', 'UserController@debitWallet');
            
            //withdrawal testing with postman but not getting it
            Route::get('withdrawal', 'individual\WalletController@withdrawal')->name('org.withdraw-money');
            Route::post('withdrawal', 'individual\WalletController@processWithdrawal')->name('org.process-withdrawal');
        

            Route::prefix('organization')->group(function () {
                Route::get('balance', 'OrganizationController@balance');
                Route::get('org-details', 'OrganizationController@orgDetails');

                // Loan Packages
                Route::get('loan-packages', 'OrganizationController@getLoanPackages');
                Route::get('loan-packages/{id}', 'OrganizationController@showLoanPackage');
                Route::get('loan-requests', 'OrganizationController@getLoanRequests');
                Route::get('loan-requests/{id}', 'OrganizationController@showLoanRequest');
                Route::post('approve-loan/{id}', 'OrganizationController@approveLoan');
                Route::post('decline-loan/{id}', 'OrganizationController@declineLoan');
                Route::post('azure', 'OrganizationController@testAzureImageUpload');

                // Withdrawal
               
                Route::post('withdrawal', 'OrganizationController@processWithdrawal');

                
            });
            
            
            Route::prefix('user')->group(function () {
              
                Route::post('withdrawal', 'UserController@processWithdrawal');
                Route::post('pay-bills', 'UserController@proccessBillsPayment');



            });

        });

        // Route::get('get-balance', 'UserController@getWalletBalance');
        Route::post('credit-wallet', 'UserController@creditJustriteWallet');
        Route::get('checkCardNo', 'UserController@checkCardNo');
        Route::post('update-kyc', 'UserController@updateKyc');
    });
});
