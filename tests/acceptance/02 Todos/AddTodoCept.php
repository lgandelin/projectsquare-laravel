<?php

use Page\Dashboard as DashboardPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a todo');
$I->loginAsARessource();

$I->click('.todos-link');
$I->wait(1);
$I->see('Aucune tâche en cours');

$I->fillField('.todos .new-todo', 'Nouvelle todo');
$I->click('.todos .btn-valid-create-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 1);
$I->see('1', '.todos-number');

$I->fillField('.todos .new-todo', 'Autre todo');
$I->click('.todos .btn-valid-create-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 2);

$I->see('2', '.todos-number');