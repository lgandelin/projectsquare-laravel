<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the client has access only to the information he needs from the dashboard');
$I->loginAsAClient();
$I->see('Tickets');
$I->see('Calendrier');
$I->see('Référencement');
$I->see('Messages');
$I->see('Fichiers');
$I->see('Tickets');
$I->see('Messages');
$I->dontSee('Projets');
$I->dontSee('Tâches');
$I->dontSee('Planning');
$I->dontSee('Agence');