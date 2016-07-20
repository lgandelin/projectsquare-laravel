<?php

use Page\Task as TaskPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can update a task');
$I->loginAsARessource();
$I->goToPage(TaskPage::$URI);
$I->click('table tbody tr:nth-child(1) .see-more');
$I->fillField('input[name=title]', 'New task updated');
$I->click('.content button[type=submit]');
$I->see('Tâche mise à jour avec succès');