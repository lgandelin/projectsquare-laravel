<?php

use Page\Login as LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can\'t log in if I don\'t have a valid account');
$I->login('john.doe@gmail.com', '111aaa');
$I->see(LoginPage::$errorLogin);