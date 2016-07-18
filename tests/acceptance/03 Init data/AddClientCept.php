<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a client');
$I->loginAsARessource();

$I->click('Clients');
$I->wait(1);
$I->see('Liste des clients');

$I->click('.content .add');
$I->wait(1);
$I->see('Ajouter un client');

$I->fillField('.content input[name=name]', 'Client Test');
$I->click('.content button[type=submit]');
$I->wait(1);

$I->see('Client ajouté avec succès');