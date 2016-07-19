<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can delete a todo');
$I->loginAsARessource();

$I->click('.todos-link');
$I->wait(1);

$I->click('.todos ul li:nth-child(2) .btn-delete-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 1);
$I->see('1', '.todos-number');

$I->click('.todos ul li:nth-child(1) .btn-delete-todo');
$I->wait(1);
$I->seeNumberOfElements('.todos ul li', 0);

$I->see('Aucune tâche en cours');
$I->see('0', '.todos-number');