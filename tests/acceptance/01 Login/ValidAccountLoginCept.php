<?php

use Page\Login as LoginPage;
use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can log in when I have a valid account');
$I->loginAsARessource();
$I->see(DashboardPage::$title);
$I->dontSee(LoginPage::$errorLogin);