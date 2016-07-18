<?php

use Page\Clients as ClientsPage;
use Page\Projects as ProjectsPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that when I delete a client, the associated project is deleted as well');
$I->loginAsARessource();
$I->goToPage(ProjectsPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);
$I->goToPage(ClientsPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);
$I->click('table tbody tr:nth-child(1) a.btn-delete');
$I->acceptPopup();
$I->wait(2);

$I->seeNumberOfElements('.content table tbody tr', 0);
$I->goToPage(ProjectsPage::$URI);
$I->seeNumberOfElements('table tbody tr', 0);