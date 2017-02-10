<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a resource to a project');
$I->loginAsARessource();

$I->click('Projets');
$I->wait(1);
$I->see('Liste des projets');

$I->click('table tbody tr:nth-child(1) .see-more');
$I->wait(1);
$I->see('Editer un projet');

$option = $I->grabTextFrom('.content select[name=user_id] option:nth-child(2)');
$I->selectOption('.content select[name=user_id]', $option);
$I->wait(1);

$option = $I->grabTextFrom('.content select[name=role_id] option:nth-child(3)');
$I->selectOption('.content select[name=role_id]', $option);
$I->wait(1);

$I->click("(//*[@class='btn valid'])[2]");
$I->wait(1);

$I->see('Collaborateur assigné au projet avec succès');

$I->seeNumberOfElements('.left-bar ul .menu:nth-child(2) .sub-menu li', 1);
$I->see('Projet Test', '.left-bar ul .menu:nth-child(2) .sub-menu li');