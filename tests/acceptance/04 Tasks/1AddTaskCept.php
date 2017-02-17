<?php

use Page\Task as TaskPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a new task');
$I->loginAsARessource();
$I->goToPage(TaskPage::$URI);
$I->click('.content .add');
$I->see('Ajouter une tâche');
$I->fillField('.content input[name=title]', 'Nouvelle tâche');
$option = $I->grabTextFrom('.content select[name=project_id] option:nth-child(2)');
$I->selectOption('.content select[name=project_id]', $option);
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$option = $I->grabTextFrom('.content select[project_id=status_id] option:nth-child(3)');
$I->selectOption('.content select[name=status_id]', $option);
$option = $I->grabTextFrom('.content select[name=allocated_user_id] option:nth-child(2)');
$I->selectOption('.content select[name=allocated_user_id]', $option);
$I->click('.content button[type=submit]');
$I->see('Tâche créée avec succès');
$I->goToPage(TaskPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);