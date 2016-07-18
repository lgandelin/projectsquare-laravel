<?php

use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the left bar can toggle correctly');
$I->loginAsARessource();
$I->goToPage(DashboardPage::$URI);

$I->click('.toggle-left-bar');
$I->seeElement('.left-bar-minified');

$I->reloadPage();
$I->seeElement('.left-bar-minified');

$I->moveMouseOver('.left-bar ul .menu:nth-child(3)');
$I->wait(1);
$I->see('Tickets');
$I->click('Tickets');
$I->see('Liste des tickets');
$I->seeElement('.left-bar-minified');

$I->click('.toggle-left-bar');
$I->dontSeeElement('.left-bar-minified');