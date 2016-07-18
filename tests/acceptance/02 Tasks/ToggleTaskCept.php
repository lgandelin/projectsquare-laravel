<?php

use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can\'t add an empty task from the dashboard');
$I->loginAsARessource();

$I->click('.tasks-link');
$I->wait(1);
$I->see('Aucune tâche en cours');

$I->fillField('.tasks .new-task', 'Nouvelle tâche');
$I->click('.tasks .btn-valid-create-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 1);

$I->fillField('.tasks .new-task', 'Autre tâche');
$I->click('.tasks .btn-valid-create-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 2);
$I->see('2', '.tasks-number');

$I->click('.tasks ul li:nth-child(1)');
$I->wait(1);

$I->see('1', '.tasks-number');
$I->click('.tasks ul li:nth-child(2) .btn-delete-task');
$I->wait(1);
$I->click('.tasks ul li:nth-child(1) .btn-delete-task');
$I->wait(1);