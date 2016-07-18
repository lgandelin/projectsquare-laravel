<?php

use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a task');
$I->loginAsARessource();

$I->click('.tasks-link');
$I->wait(1);
$I->see('Aucune tâche en cours');

$I->fillField('.tasks .new-task', 'Nouvelle tâche');
$I->click('.tasks .btn-valid-create-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 1);
$I->see('1', '.tasks-number');

$I->fillField('.tasks .new-task', 'Autre tâche');
$I->click('.tasks .btn-valid-create-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 2);

$I->see('2', '.tasks-number');