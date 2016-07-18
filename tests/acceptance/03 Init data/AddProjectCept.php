<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a project');
$I->loginAsARessource();

$I->click('Projets');
$I->wait(1);
$I->see('Liste des projets');

$I->click('.content .add');
$I->wait(1);
$I->see('Ajouter un projet');

$I->fillField('.content input[name=name]', 'Projet Test');
$option = $I->grabTextFrom('.content select[name=client_id] option:nth-child(2)');
$I->selectOption('.content select[name=client_id]', $option);
$I->fillField('.content input[name=website_front_url]', 'http://projectsquare.io');
$I->fillField('.content input[name=website_back_url]', 'http://projectsquare.io/wp-admin');
$I->fillField('.content input[name=color]', '#333333');
$I->click('.page-header');
$I->wait(1);

$I->click('.content button[type=submit]');
$I->wait(1);

$I->see('Projet ajouté avec succès');