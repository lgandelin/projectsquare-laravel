<?php

use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can\'t add an empty todo from the dashboard');
$I->loginAsARessource();

$I->click('.todos-link');
$I->wait(1);
$I->see('Aucune tâche en cours');

$I->fillField('.todos .new-todo', 'Nouvelle todo');
$I->click('.todos .btn-valid-create-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 1);

$I->fillField('.todos .new-todo', 'Autre todo');
$I->click('.todos .btn-valid-create-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 2);
$I->see('2', '.todos-number');

$I->click('.todos ul li:nth-child(1)');
$I->wait(1);

$I->see('1', '.todos-number');
$I->click('.todos ul li:nth-child(2) .btn-delete-todo');
$I->wait(1);
$I->click('.todos ul li:nth-child(1) .btn-delete-todo');
$I->wait(1);