<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the installation page works correctly');
$I->goToPage('/');
$I->see('Installation');

$I->see('Compte administrateur');
$I->fillField('first_name', 'Louis');
$I->fillField('last_name', 'Gandelin');
$I->fillField('email', 'lgandelin@web-access.fr');
$I->fillField('password', 'GhDt6XdX');
$I->click('button[type=submit]');

$I->see('Agence');
$I->fillField('agency_name', 'Webaccess');
$I->click('button[type=submit]');

$I->goToPage('/');
$I->see('Tableau de bord');