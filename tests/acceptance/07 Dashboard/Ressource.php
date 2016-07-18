<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that an administrator has access to all information from the dashboard');
$I->loginAsARessource();
$I->see('Projets');
$I->see('Tickets');
$I->see('Messages');
$I->see('Planning');
$I->see('Agence');