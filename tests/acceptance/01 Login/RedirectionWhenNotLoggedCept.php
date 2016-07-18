<?php

use Page\Login as LoginPage;
use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I am redirected to the login page when I am not logged');
$I->amOnPage(DashboardPage::$URI);
$I->see(LoginPage::$title);