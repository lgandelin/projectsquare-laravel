<?php

use Page\Task as TaskPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can delete a task');
$I->loginAsARessource();
$I->goToPage(TaskPage::$URI);
$I->click('.content table tbody tr:nth-child(1) .btn-delete');
$I->acceptPopup();
$I->wait(2);
$I->see('Tâche supprimée avec succès');
$I->seeNumberOfElements('table tbody tr', 0);