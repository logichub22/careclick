<?php

/*
|--------------------------------------------------------------------------
| API Test Routes
|--------------------------------------------------------------------------
|
| These routes point to the api endpoints from advance bank for
| reference.
|
*/

Route::namespace('Api')->group(function () {
	Route::get('api-test', 'ApiSimulatorController@apiHome');
	Route::get('states', 'ApiSimulatorController@getStates');
	Route::get('countries', 'ApiSimulatorController@getCountries');
	Route::get('marital-status', 'ApiSimulatorController@marital');
	Route::get('income-classes', 'ApiSimulatorController@getIncomeClasses');
	Route::get('customer', 'ApiSimulatorController@getCustomer');
});



/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| These routes point to the public pages of the application. No
| middleware is assigned to them as a result
|
*/


Route::namespace('Front')->group(function () {

	Route::get('/', 'PageController@index')->name('welcome');
	Route::get('push', 'PageController@getData')->name('welcome');
	Route::get('about', 'PageController@about')->name('about');
	Route::get('article1', 'PageController@article1')->name('article');
	Route::get('article2', 'PageController@article2')->name('article');
	Route::get('article3', 'PageController@article3')->name('article');
	Route::get('article4', 'PageController@article3')->name('article');
	// Route::get('mission', 'PageController@mission')->name('mission');
	Route::get('privacy', 'PageController@privacy')->name('privacy');
	Route::get('contact', 'PageController@contact')->name('contact');
	// Route::get('news', 'PageController@news')->name('news');
	// Route::get('funding', 'PageController@funding')->name('funding');
	// Route::get('investing', 'PageController@investing')->name('investing');
	Route::get('individual-signup', 'PageController@individualSignUp')->name('individual-signup');
	Route::get('organization-signup', 'PageController@organizationSignUp')->name('organization-signup');
	Route::get('account-created', 'PageController@accountCreated')->name('account.created');
	Route::get('registration-successful', 'PageController@regSuccess')->name('reg.success');
	Route::post('contact-message', 'PageController@postContact')->name('postmessage');
	Route::post('userjson', 'PageController@userData')->name('userjson');
	Route::get('ip-test', 'PageController@ipTest');
	Route::get('test-csv', 'PageController@testCsv');
	Route::get('get-json/{currency_unit}', 'PageController@getJson');

	Route::get('change-language', 'PageController@changeLanguage')->name('lang.change');
	Route::get('notifications', 'PageController@userNotifications')->name('notifications.all');

	Route::get('load/level_two/{id}', 'PageController@loadLevelTwo');
	Route::get('load/level_three/{id}', 'PageController@loadLevelThree');
	Route::post('group-email', 'PageController@sendEmailGroups')->name('general.group-email');
});

/*
|--------------------------------------------------------------------------
| Backend Organization Routes
|--------------------------------------------------------------------------
|
| These routes point to the restricted pages of the application for an
| organization. The admin middleware is assigned to them as a
| result
|
*/

Route::middleware(['auth', 'set-locale','role:admin|service-provider|super-organization-admin'])->group(function () {
	Route::namespace('Back\Organization')->group(function () {
		Route::prefix('organization')->group(function () {
			Route::get('', 'PageController@getDashboard')->name('organization.dashboard');
			Route::get('dashboard', 'PageController@getDashboard')->name('organization.dashboard');
			Route::get('profile', 'PageController@manageProfile')->name('organization.profile');
			Route::resource('groups', 'OrgGroupController');
			Route::get('contributions', 'OrgGroupController@contributions')->name('orggroup.contributions');
			Route::post('contributions-settings', 'OrgGroupController@contributionSettings')->name('orggroup.contributions-settings');
			Route::post('activate-group/{id}', 'OrgGroupController@activateGroup')->name('association.activate-group');
			Route::post('deactivate-group/{id}', 'OrgGroupController@deactivateGroup')->name('association.deactivate-group');
			Route::get('group-settings/{id}', 'OrgGroupController@groupSettings')->name('orggroupsets');
			Route::post('storedata', 'OrgGroupController@storeGroupData')->name('group-data');
			Route::get('add-group-member/{id}', 'OrgGroupController@getAddMember')->name('orggroup.addmember');
			Route::post('add-member', 'OrgGroupController@postAddMember')->name('addmember');
			Route::get('group/member/{id}', 'OrgGroupController@viewMember')->name('group.viewmember');
			Route::post('import-members', 'OrgGroupController@importMembers')->name('groupmembers.import');
			Route::get('download-template', 'OrgGroupController@downloadCsvTemplate')->name('downloadcsvtemplate');
			Route::get('download-user-template', 'UserController@downloadCsvTemplate')->name('download-user-csv-template');
			Route::post('email-member', 'OrgGroupController@sendMemberEmail')->name('emailmember');
			Route::post('cancel-member/{id}', 'OrgGroupController@cancelMember')->name('cancelmember');
			Route::post('renew-member/{id}', 'OrgGroupController@renewMember')->name('renewmember');
			Route::post('delete-member/{id}', 'OrgGroupController@deleteMember')->name('deletemember');
			Route::post('set-group-admin', 'OrgGroupController@setGroupAdmin')->name('set-group-admin');
			Route::get('invite-members/{id}', 'OrgGroupController@inviteMembers')->name('getinvitemembers');
			Route::resource('users', 'UserController');
			Route::delete('users/{id}/delete', 'UserController@destroy')->name('users.delete');
			Route::get('org_user_template', 'UserController@generateTemplate')->name('org_user_file.generate');
			Route::get('stream-permit', 'UserController@streamPermit')->name('streampermit');
			Route::get('stream-tax', 'UserController@streamTax')->name('streamtax');
			Route::get('stream-id/{$id}', 'UserController@streamId')->name('streamid');
			Route::post('update-organization', 'PageController@updateOrganization')->name('organization.update');
			Route::post('activate/{id}', 'UserController@activate')->name('activate');
			Route::post('deactivate/{id}', 'UserController@deactivate')->name('deactivate');
			Route::get('wallet/personal', 'WalletController@personal')->name('orgwallet.personal');
			Route::get('add-money', 'WalletController@paymentMethod')->name('org-add-money');
			Route::get('add-money/pay-with-flutterwave', 'WalletController@payWithFlutterwave')->name('org.pay-with-flutterwave');
			Route::get('withdrawal', 'WalletController@withdrawal')->name('org.withdraw-money');
			Route::post('withdrawal', 'WalletController@processWithdrawal')->name('org.process-withdrawal');
			Route::get('wallet/organization', 'WalletController@organization')->name('organization.wallet');
			Route::get('savings', 'WalletController@savings')->name('orgsavings');
			Route::get('savings/add-money', 'WalletController@creditSavings')->name('orgsavings-add-money');
			Route::get('savings/transfer', 'WalletController@debitSavings')->name('orgsavings-transfer');
			Route::post('savings/add-money', 'WalletController@save')->name('org-savings');
			Route::post('savings/transfer', 'WalletController@debit')->name('org-savings-transfer');
			Route::resource('org-packages', 'OrgPackageController');
			Route::get('org-packages/{id}/delete', 'OrgPackageController@destroy')->name('org-packages.delete');
			Route::get('loan-requests', 'OrgPackageController@loanRequests')->name('organization.requests');
			Route::get('loan-requests/{id}', 'OrgPackageController@loanRequestDetails')->name('organization.request_details');
			Route::get('approve-loan/{id}', 'OrgLoanController@approveLoan')->name('organization.approve');
			Route::get('decline-loan/{id}', 'OrgLoanController@declineLoan')->name('organization.decline');
			Route::get('loans', 'OrgLoanController@viewLoans')->name('organization.loans');
			Route::post('loans', 'OrgLoanController@generateLoansCsv')->name('organization.generate_loans_csv');
			Route::get('loans/{id}', 'OrgLoanController@loanDetails')->name('organization.loan_details');
			Route::post('loans/{id}', 'OrgLoanController@generateRepaymentCsv')->name('organization.generate_repayment_csv');
			//Route::get('loan-detail/{id}', 'LoanDetailController@getOrgLoanDetail')->name('organization.loandetail');
			Route::resource('org-loans', 'OrgLoanController');
			Route::get('browse-loans', 'OrgLoanController@browseLoans')->name('organization.browseloans');
			Route::post('filter-loans', 'OrgLoanController@filterLoans')->name('organization.filterloans');
			Route::post('repay-loan', 'OrgLoanController@repayLoan')->name('org.repay-loan');
			Route::get('borrow-loan/{id}', 'OrgLoanController@getBorrowLoan')->name('organization.applyloan');
			Route::post('change-avatar', 'UserController@postChangeAvatar')->name('avatar.change');
			Route::post('import-users', 'UserController@importUsers')->name('orgusers.import');
			Route::get('loan-collections-chart', 'AnalyticsController@collectionsChart')->name('organization.loancollectionschart');
 			Route::get('loan-maturity-chart', 'AnalyticsController@maturityChart')->name('organization.loanmaturitychart');
 			Route::get('loan-released-chart', 'AnalyticsController@releasedChart')->name('organization.loanreleasedchart');
 			Route::get('gender-chart', 'AnalyticsController@genderChart')->name('organization.genderchart');
 			Route::get('balance-chart', 'AnalyticsController@balanceChart')->name('organization.balancechart');
 			Route::get('average-loan-tenure-chart', 'AnalyticsController@averageLoanTenureChart')->name('organization.averageloantenurechart');
 			Route::get('savings-chart', 'AnalyticsController@savingsChart')->name('organization.savingschart');
 			Route::get('report_credit-debit', 'AnalyticsController@reportWallet')->name('organization.reportw');
			Route::post('report', 'AnalyticsController@export')->name('organization.reportw_get');
 			Route::get('report_borrowing', 'AnalyticsController@reportBorrowing')->name('organization.reportb');
 			Route::get('report_lending', 'AnalyticsController@reportLending')->name('organization.reportl');
 			Route::get('report_transactions', 'AnalyticsController@reportTransactions')->name('organization.reportt');
 			Route::get('report_group', 'AnalyticsController@reportGroup')->name('organization.reportgroup');
 			Route::get('report_cash-flow-projection', 'AnalyticsController@reportCashFlow')->name('organization.reportcashflow');
 			Route::get('report_disbursement', 'AnalyticsController@reportDisbursement')->name('organization.reportdisbursement');
 			Route::get('report_profit-loss', 'AnalyticsController@reportProfitLoss')->name('organization.reportprofitloss');
 			Route::get('report_pending-dues', 'AnalyticsController@reportPendingDues')->name('organization.reportpendingdues');

			Route::get('add-payment', 'AccountController@index')->name('payment.index');
			Route::post('add-account', 'AccountController@addAccount')->name('bankaccount.add');
			Route::get('insure-package/{id}', 'OrgPackageController@getInsureLoan')->name('packages.getInsurance');
			Route::post('choose-service', 'ServiceController@chooseService')->name('service.choose');
			Route::post('loanservice', 'OrgLoanController@loanCalculations')->name('loanservice');
			Route::get('view-org-loan/{id}', 'OrgPackageController@getBorrowerDetail')->name('borrower.detail');
			Route::post('change-member-info', 'OrgGroupController@updateMember')->name('org.updatemember');
			Route::resource('services', 'ServiceController');
			Route::get('login-trails', 'PageController@accessLogs')->name('logs.org');
			Route::get('group_template', 'OrgGroupController@generateTemplate')->name('org.generate');
			Route::get('organization-transactions', 'PageController@transactions')->name('org.transactions');
			Route::get('org-ledger', 'PageController@getLedger')->name('org.ledger');
			Route::get('org-schedule/{id}', 'OrgLoanController@generateSchedule')->name('org.schedule');
			Route::get('group-message/{id}', 'OrgGroupController@getGroupMessage')->name('org.groupmessage');
			Route::post('send-bulk-email', 'OrgGroupController@postGroupMessage')->name('org.sendbulkemail');

			Route::post('assign-trainers', 'OrgGroupController@assignTrainer')->name('association.assign-trainer');
			Route::post('change-trainer', 'OrgGroupController@changeTrainer')->name('association.change-trainer');

			Route::post('/make-payment/', 'WalletController@processPayment')->name('org.process-payment');

			Route::get('/verify-payment', 'WalletController@verifyPayment')->name('org.verify-payment');
			Route::get('/reportcsvdata', 'WalletController@reportCSV')->name('csv');

			// Route::webhooks('org.verifyPayment');
			// Route::get('/coopbank', 'OrgCoopBankController@index')->name('org-coopbank');
			// Route::post('coopbank', 'OrgCoopBankController@handleRequest')->name('org-coopbank.request');
		});
	});
});

Route::middleware(['auth', 'role:trainer'])->group(function () {
	Route::namespace('Back\Trainer')->group(function () {
		Route::prefix('trainer')->group(function () {
			Route::get('dashboard', 'PageController@getDashboard')->name('trainer.dashboard');
			Route::get('profile', 'PageController@manageProfile')->name('trainer.profile');
			Route::post('update-profile', 'PageController@updateProfile')->name('trainerupdateprofile');
			Route::get('group-assigned/{id}', 'PageController@viewGroup')->name('trainer.viewgroup');
		});
	});
});


Route::middleware(['auth', 'role:super-organization-admin'])->group(function () {
	Route::namespace('Back\Federation')->group(function () {
		Route::prefix('federation')->group(function () {
			Route::get('stream-permit', 'TrainersController@streamPermit')->name('fed.streampermittrainer');
			Route::get('stream-tax', 'TrainersController@streamTax')->name('fed.streamtaxtrainer');
			Route::get('stream-id/{$id}', 'TrainersController@streamId')->name('fed.streamidtrainer');
			Route::get('dashboard', 'PageController@getDashboard')->name('federation.dashboard');
			Route::get('profile', 'PageController@manageProfile')->name('federation.profile');
			Route::get('group-settings/{id}', 'OrgGroupController@groupSettings')->name('fedgroupsets');
			Route::post('email-member', 'AssociationController@sendMemberEmail')->name('fedemailmember');
			Route::post('update-organization', 'PageController@updateOrganization')->name('fed.update');
			Route::post('activate/{id}', 'AssociationController@activate')->name('fed.activate');
			Route::post('deactivate/{id}', 'AssociationController@deactivate')->name('fed.deactivate');
			Route::resource('associations', 'AssociationController');
			Route::resource('trainers', 'TrainersController');
			Route::post('activate-trainer/{id}', 'TrainersController@activate')->name('trainers.activate');
			Route::post('deactivate-trainer/{id}', 'TrainersController@deactivate')->name('trainers.deactivate');
			Route::post('email-trainer', 'TrainersController@sendMemberEmail')->name('fedemailtrainer');
			Route::get('view-group/{id}', 'AssociationController@viewGroup')->name('federation.viewgroup');
		});
	});
});

/*
|--------------------------------------------------------------------------
| Backend User Routes
|--------------------------------------------------------------------------
|
| These routes point to the restricted pages of the application for a
| user (whether group member, normal or organization user). The user
| middlewares are assigned to them as a result
|
*/
Route::middleware(['auth','set-locale', 'role:normal-user|group-member|group-admin'])->group(function () {
	Route::namespace('Back\Individual')->group(function () {
		Route::prefix('user')->group(function () {
			Route::get('dashboard', 'PageController@getDashboard')->name('user.dashboard');
			Route::get('profile', 'PageController@manageProfile')->name('user.profile');
			Route::post('change-pic', 'PageController@postChangeAvatar')->name('pic.change');
			Route::resource('user-groups', 'UserGroupController');
			Route::get('contributions', 'UserGroupController@myContributions')->name('usergroup.contributions');
			Route::get('make-contributions', 'UserGroupController@makeContributions')->name('usergroup.make-contributions');
			Route::get('get-settings/{id}', 'UserGroupController@getContributionSettings');
			Route::post('make-contributions', 'UserGroupController@contribute')->name('user-contribute');
			Route::get('groupdetail/{id}', 'UserGroupController@groupDetail')->name('mygroup.detail');
			Route::get('new-member/{id}', 'UserGroupController@getAddMember')->name('usergroup.addmember');
			Route::get('settings/{id}', 'UserGroupController@groupSettings')->name('usergroupsettings');
			Route::post('contributions-settings', 'UserGroupController@contributionSettings')->name('usergroup.contributions-settings');
			Route::post('storeusergroupdata', 'UserGroupController@storeGroupData')->name('usergroup.storedata');
			Route::post('save-member', 'UserGroupController@postAddMember')->name('ind.savemember');
			Route::get('usergroup/member/{id}', 'UserGroupController@viewMember')->name('usergroup.viewmember');
			Route::post('user-email-member', 'UserGroupController@sendMemberEmail')->name('user.emailmember');
			Route::post('user-cancel-member/{id}', 'UserGroupController@cancelMember')->name('user.cancelmember');
			Route::post('user-renew-member/{id}', 'UserGroupController@renewMember')->name('user.renewmember');
			Route::post('user-delete-member/{id}', 'UserGroupController@deleteMember')->name('user.deletemember');
			Route::post('send-invite', 'UserGroupController@sendInvite')->name('group.invite');
			Route::resource('user-packages', 'UserPackageController');
			Route::get('insure-package/{id}', 'UserPackageController@getInsureLoan')->name('user-packages.getInsurance');
			Route::get('user-packages/{id}/delete', 'UserPackageController@destroy')->name('user-packages.delete');
			Route::get('user-packages/edit/{id}', 'UserPackageController@edit')->name('user-packages.edit');
			Route::put('user-packages/update/{id}', 'UserPackageController@update')->name('user-packages.update');
			Route::get('loan-requests', 'UserPackageController@loanRequests')->name('user.requests');
			Route::get('approve-loan/{id}', 'UserLoanController@approveLoan')->name('user.approve');
			Route::get('decline-loan/{id}', 'UserLoanController@declineLoan')->name('user.decline');
			Route::resource('user-loans', 'UserLoanController');
			Route::get('loans-available', 'UserLoanController@browseLoans')->name('user.browseloans');
			Route::post('loans-available', 'UserLoanController@searchLoans')->name('user.searchloans');
			Route::get('borrow/{id}', 'UserLoanController@getBorrowLoan')->name('user.applyloan');
			Route::post('user-loanservice', 'UserLoanController@loanCalculations')->name('user.loanservice');
			Route::post('repay-loan', 'UserLoanController@repayLoan')->name('user.repay-loan');
			Route::get('today', 'UserLoanController@calcPaymentDate');
			Route::get('view-user-loan/{id}', 'UserPackageController@getBorrowerDetail')->name('userborrower.detail');
			Route::get('mywallet', 'WalletController@personal')->name('userwallet');
			Route::get('add-money', 'WalletController@paymentMethod')->name('userwallet-add-money');
			Route::get('add-money/pay-with-flutterwave', 'WalletController@payWithFlutterwave')->name('user.pay-with-flutterwave');
			Route::post('/make-payment/', 'WalletController@processPayment')->name('user.process-payment');
			Route::get('/withdrawal', 'WalletController@withdrawal')->name('user.withdraw-money');
			Route::post('/withdrawal', 'WalletController@processWithdrawal')->name('user.process-withdrawal');
			Route::get('/bills-payment', 'WalletController@billsPayment')->name('user.bills-payment');
			Route::post('/bills-payment', 'WalletController@proccessBillsPayment')->name('user.proccess-bills-payment');
			Route::get('savings', 'WalletController@savings')->name('usersavings');
			Route::get('savings/add-money', 'WalletController@creditSavings')->name('savings-add-money');

			Route::get('savings/transfer', 'WalletController@debitSavings')->name('savings-transfer');
			Route::post('savings/add-money', 'WalletController@save')->name('user-savings');
			Route::post('savings/transfer', 'WalletController@debit')->name('user-savings-transfer');
			Route::get('loan-collections-chart', 'AnalyticsController@collectionsChart')->name('user.loancollectionschart');
			Route::get('loan-maturity-chart', 'AnalyticsController@maturityChart')->name('user.loanmaturitychart');
			Route::get('loan-released-chart', 'AnalyticsController@releasedChart')->name('user.loanreleasedchart');
			Route::get('gender-chart', 'AnalyticsController@genderChart')->name('user.genderchart');
			Route::get('balance-chart', 'AnalyticsController@balanceChart')->name('user.balancechart');
			Route::get('average-loan-tenure-chart', 'AnalyticsController@averageLoanTenureChart')->name('user.averageloantenurechart');
			Route::get('savings-chart', 'AnalyticsController@savingsChart')->name('user.savingschart');
			Route::get('report', 'AnalyticsController@reportWallet')->name('user.reportw');
			Route::post('report_credit-debit', 'AnalyticsController@reportWallet_get')->name('user.reportw_get');
			Route::get('report_borrowing', 'AnalyticsController@reportBorrowing')->name('user.reportb');
			Route::get('report_lending', 'AnalyticsController@reportLending')->name('user.reportl');
			Route::get('report_transactions', 'AnalyticsController@reportTransactions')->name('user.reportt');
			Route::get('report_group', 'AnalyticsController@reportGroup')->name('user.reportgroup');
			Route::get('report_cash-flow-projection', 'AnalyticsController@reportCashFlow')->name('user.reportcashflow');
			Route::get('report_disbursement', 'AnalyticsController@reportDisbursement')->name('user.reportdisbursement');
			Route::get('report_profit-loss', 'AnalyticsController@reportProfitLoss')->name('user.reportprofitloss');
			Route::get('report_pending-dues', 'AnalyticsController@reportPendingDues')->name('user.reportpendingdues');
			Route::post('update-profile', 'PageController@updateProfile')->name('updateprofile');
			Route::post('update-member', 'UserGroupController@updateMember')->name('users.updatemember');
			Route::get('add-meeting', 'UserGroupController@addMeeting')->name('user.addmeeting');
			Route::get('user-preferences', 'PageController@getPreferences')->name('user.preferences');
			Route::get('my_template', 'UserGroupController@generateTemplate')->name('user.generate');
			Route::get('loan-schedule/{id}', 'UserLoanController@generateSchedule')->name('user.schedule');
			Route::get('user-transactions', 'PageController@transactions')->name('user.transactions');
			Route::get('unread-notifications', 'PageController@getUnreadNotifications')->name('user.unread');
			Route::post('importusermembers', 'UserGroupController@importMembers')->name('usergroup.import');
			Route::get('user-group-message/{id}', 'UserGroupController@getGroupMessage')->name('user.groupmessage');
			Route::post('user-send-bulk-email', 'UserGroupController@postGroupMessage')->name('user.sendbulkemail');

			Route::get('/verify-payment', 'WalletController@verifyPayment')->name('user.verify-payment');

			// Route::webhooks('user.verifyPayment');

			Route::get('coopbank', 'UserCoopBankController@index')->name('coopbank');
			Route::post('coopbank', 'UserCoopBankController@handleRequest')->name('coopbank.request');
			Route::get('loan-scheduler/{id}', 'UserLoanController@scheduler')->name('user.scheduler');
			Route::get('loan-repay-list/{id}', 'UserLoanController@repaymentList')->name('user.repaylist');
			Route::post('repay-loan', 'UserLoanController@repayLoan')->name('user.repay-loan');
		});
	});
});

Route::post('ussd', 'Services\USSDController@handleUSSD')->name('processussd');
Route::get('verify-bank-account', 'Services\CommonServices@verifyBankAccount')->name('common.verify-bank');
Route::post('verify-fw-withdrawal', 'Services\CommonServices@verifyFwWithdrawal')->name('verify-fw-withdrawal');
Route::post('verify-riby-transfer', 'Back\Organization\OrgLoanController@verifyRibyTransfer')->name('org.verify-riby-transfer');
Route::get('sync-riby-applications', 'CronjobController@syncRibyApplications');
Route::get('sync-riby-packages', 'CronjobController@syncRibyLoanPackages');
Route::post('addNewAdmin', 'CronjobController@addNewAdmin');

/*
|--------------------------------------------------------------------------
| Backend Superadmin Routes
|--------------------------------------------------------------------------
|
| These routes point to the restricted pages of the application for a
| user with the superadmin role
|
*/
Route::middleware(['auth', 'role:superadmin'])->group(function () {
	Route::namespace('Back\Superadmin')->group(function () {
		Route::prefix('superadmin')->group(function () {
			Route::get('dashboard', 'PageController@getDashboard')->name('super.dashboard');
			Route::resource('roles', 'RoleController');
			Route::resource('permissions', 'PermissionController');
			Route::get('profile', 'PageController@manageProfile')->name('super.profile');
			Route::resource('organizations', 'OrganizationController');
			Route::post('update-super-profile', 'PageController@updateProfile')->name('super.updateprofile');
			Route::post('deactivate-org/{id}', 'OrganizationController@deactivate')->name('organization.deactivate');
			Route::post('activate-org/{id}', 'OrganizationController@activate')->name('organization.activate');
			Route::resource('all-users', 'UserController');
			Route::post('super-avatar', 'PageController@postChangeAvatar')->name('super.picchange');
			Route::resource('all-groups', 'GroupController');
			Route::resource('packages', 'LoanPackageController');
			Route::get('all-transactions', 'PageController@transactions')->name('super.transactions');
			Route::resource('configs', 'SystemMessagesController');
			Route::get('access-logs', 'PageController@accessLogs')->name('logs.all');
			Route::get('graphical-analysis', 'AnalyticsController@getGraphical')->name('super.graphs');
			Route::get('reporting', 'AnalyticsController@reports')->name('super.reports');
			Route::get('months', 'AnalyticsController@getMonthlyUsers');
			Route::get('get_monthly_user_chart_data', 'AnalyticsController@getMonthlyUsers')->name('superchart.userdata');
			Route::get('manage-settings', 'PageController@getSettings')->name('super.settings');
			Route::resource('currencies', 'CurrencyController');
			Route::resource('settings', 'SettingsController');
			Route::post('createcat', 'SettingsController@addCategory')->name('createcat');
			Route::post('setting-deactivate/{id}', 'SettingsController@deactivateSetting')->name('settings.deactivate');
			Route::post('setting-activate/{id}', 'SettingsController@activateSetting')->name('settings.activate');
			Route::post('email-member-super', 'UserController@sendMemberEmail')->name('super.emailmember');
			Route::post('region-settings', 'SettingsController@regionSettings')->name('super.regionSettings');
		});
	});
});

Auth::routes();
Route::namespace('Auth')->group(function () {
	Route::get('register/verify/{token}', 'RegisterController@verifyEmail')->name('verify');
	Route::post('register-organization', 'RegisterController@registerOrganization')->name('register.organization');
	Route::get('logout', 'LoginController@logout');
});

Route::get('/home','HomeController@index')->name('home');
Route::post('careclick/register','CareClickController@register');
Route::post('careclick/login','CareClickController@login');
Route::get('careclick/get-patient-object','CareClickController@getPatientObject');
Route::post('careclick/add-question','CareClickController@AddQuestion');
Route::get('careclick/fetch-appointment','CareClickController@FetchAppointment');
Route::get('careclick/appointment-hostory','CareClickController@AppointmentHistory');
Route::get('careclick/fetch-order','CareClickController@FetchOrder');
Route::get('careclick/order-history','CareClickController@OrderHistory');
Route::get('careclick/search-providers','CareClickController@SearchProvider');
Route::get('careclick/fetch-provider','CareClickController@FetchProvider');
Route::get('careclick/fetch-providers','CareClickController@FetchProviders');
Route::get('careclick/fetch-plans','CareClickController@FetchPlans');
Route::post('careclick/fetch-subscribe','CareClickController@Subscribe');
Route::post('careclick/checkoutplans','CareClickController@CheckOutPlans');
Route::post('careclick/checkout','CareClickController@CheckOut');
Route::post('careclick/addcoupon','CareClickController@AddCoupon');
Route::post('careclick/removecoupon','CareClickController@RemoveCoupon');
Route::post('careclick/decreaseproduct','CareClickController@DecreaseProduct');
Route::post('careclick/increaseproduct','CareClickController@IncreaseProduct');
Route::post('careclick/addtocart','CareClickController@AddToCart');
Route::get('careclick/fetch-brand','CareClickController@FetchBrand');
Route::get('careclick/fetchcartproduct','CareClickController@FetchCartProduct');
Route::get('careclick/fetch-categories','CareClickController@FetchCategories');
Route::get('careclick/filter-products','CareClickController@FilterProducts');
Route::get('careclick/fetch-product','CareClickController@FetchProduct');
Route::get('careclick/search-products','CareClickController@SearchProducts');
Route::get('careclick/fetch-products','CareClickController@FetchProducts');
Route::get('careclick/fetch-favorite-posts','CareClickController@FetchFavoritePosts');
Route::post('careclick/toggle-favorite-posts','CareClickController@TogglePostFavorite');
Route::post('careclick/toggle-like-posts','CareClickController@TogglePostLike');
Route::post('careclick/reply-post-comment','CareClickController@ReplyPostComment');
Route::post('careclick/add-post-comment','CareClickController@AddPostComment');
Route::get('careclick/fetch-post-comment','CareClickController@FechPostComments');
Route::get('careclick/fetch-category','CareClickController@FechPostCategory');
Route::get('careclick/search-posts','CareClickController@SearchPosts');
Route::get('careclick/filter-posts','CareClickController@FilterPosts');
Route::get('careclick/fetch-post','CareClickController@FetchPost');
Route::get('careclick/fetch-posts','CareClickController@FetchPosts');
Route::post('careclick/notify-provider','CareClickController@NotifyProvider');
Route::get('careclick/fetch-notifications','CareClickController@FetchNotifications');
Route::patch('careclick/change-profile-details','CareClickController@ChangeProfileDetails');
Route::post('careclick/change-password','CareClickController@ChangePassword');
Route::post('careclick/update-profile-image','CareClickController@UpdateProfileimage');
Route::post('careclick/update-profile','CareClickController@UpdateProfile');
Route::get('careclick/get-countries','CareClickController@GetCountries');
Route::get('careclick/get-states','CareClickController@GetStates');
Route::get('careclick/get-cities','CareClickController@GetCities');
Route::post('careclick/verify-code','CareClickController@VerifyCode');
Route::post('careclick/resend-code','CareClickController@ResendCode');
Route::post('careclick/resend-otp','CareClickController@ResendOtp');
Route::post('careclick/forgot-passwordweb','CareClickController@ForgotPasswordWeb');
Route::post('careclick/forgot-password','CareClickController@ForgotPassword');
Route::post('careclick/verify_users','CareClickController@VerifyUsers');
Route::post('careclick/create_password','CareClickController@CreatePassword');
Route::post('careclick/reset_password','CareClickController@ResetPassword');
Route::get('careclick/auth_users','CareClickController@AuthUsers');
Route::get('careclick/log_out','CareClickController@LogOut');
Route::get('careclick/add_review','CareClickController@AddReview');
Route::get('careclick/add_doctorreview','CareClickController@FetchDoctorReview');
Route::get('careclick/filter_appointment','CareClickController@FilterAppointment');
Route::get('careclick/fetch_appointment','CareClickController@FetchAppointments');
Route::post('careclick/mark_completed','CareClickController@MarkasCompleted');
Route::get('careclick/checkurgentappointment','CareClickController@CheckUrgentAppointment');
















