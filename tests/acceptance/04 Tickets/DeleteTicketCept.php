<?php

use Page\Ticket as TicketPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can delete a ticket');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('table tbody tr:nth-child(1) a.btn-delete');
$I->acceptPopup();
$I->wait(2);
$I->see(TicketPage::$messageTicketDeletedSuccess);
$I->seeNumberOfElements('table tbody tr', 0);