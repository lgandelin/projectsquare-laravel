<?php

use Page\Task as TaskPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a new task');
$I->loginAsARessource();
$I->goToPage(TaskPage::$URI);
$I->click('.create-task');
$I->seeInCurrentUrl('add_task');
$I->see('Ajouter une tâche');
$I->fillField('.content input[name=title]', 'Nouvelle tâche');
$I->selectOption('.content select[name=project_id]', 1);
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$I->selectOption('.content select[name=status_id]', 2);
$I->selectOption('.content select[name=allocated_user_id]', 1);
$I->click('.content .valid');
$I->see('Tâche créée avec succès');
$I->goToPage(TaskPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);