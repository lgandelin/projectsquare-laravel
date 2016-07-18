<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can delete a task');
$I->loginAsARessource();

$I->click('.tasks-link');
$I->wait(1);

$I->click('.tasks ul li:nth-child(2) .btn-delete-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 1);
$I->see('1', '.tasks-number');

$I->click('.tasks ul li:nth-child(1) .btn-delete-task');
$I->wait(1);
$I->seeNumberOfElements('.tasks ul li', 0);

$I->see('Aucune tâche en cours');
$I->see('0', '.tasks-number');