<?php

use Page\Ticket as TicketPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can update a ticket');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('table tbody tr:nth-child(1) .see-more');
$I->fillField('input[name=title]', 'New ticket updated');
$I->click('.ticket-infos button[type=submit]');
$I->see(TicketPage::$messageTicketUpdatedSuccess);