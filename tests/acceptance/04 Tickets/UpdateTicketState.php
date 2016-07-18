<?php

use Page\Ticket as TicketPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can update ticket state');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('table tbody tr:nth-child(1) .see-more');
$I->fillField('textarea[name=comments]', 'Lorem ipsum dolor sit amet');
$I->click('.ticket-state button[type=submit]');
$I->see(TicketPage::$messageTicketUpdatedSuccess);
$I->see('Lorem ipsum dolor sit amet', '.ticket-history');

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can\'t update a ticket with a due date that has already passed');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('table tbody tr:nth-child(1) .see-more');
$I->fillField('input[name=due_date]', (new \DateTime())->sub(new \DateInterval('P1D'))->format('d/m/Y'));
$I->click('.ticket-state button[type=submit]');
$I->see(TicketPage::$messageTicketDueDateError);