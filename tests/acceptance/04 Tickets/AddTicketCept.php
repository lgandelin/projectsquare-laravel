<?php

use Page\Ticket as TicketPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a new ticket');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('.create-ticket');
$I->seeInCurrentUrl('add_ticket');
$I->see('Ajouter un ticket');
$I->fillField('.content input[name=title]', 'Nouveau ticket');
$I->selectOption('.content select[name=type_id]', 2);
$I->selectOption('.content select[name=project_id]', 1);
$I->fillField('.content input[name=due_date]', (new \DateTime())->format('d/m/Y'));
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$I->selectOption('.content select[name=status_id]', 2);
$I->selectOption('.content select[name=allocated_user_id]', 2);
$I->selectOption('.content select[name=priority]', 3);
$I->click('.content .valid');
$I->see(TicketPage::$messageTicketCreatedSuccess);
$I->goToPage(TicketPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can\'t create a ticket with a due date that has already passed');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('.create-ticket');
$I->seeInCurrentUrl('add_ticket');
$I->see('Ajouter un ticket');
$I->fillField('.content input[name=title]', 'Nouveau ticket');
$I->selectOption('.content select[name=type_id]', 2);
$I->selectOption('.content select[name=project_id]', 1);
$I->fillField('.content input[name=due_date]', (new \DateTime())->sub(new \DateInterval('P1D'))->format('d/m/Y'));
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$I->selectOption('.content select[name=status_id]', 2);
$I->selectOption('.content select[name=allocated_user_id]', 2);
$I->selectOption('.content select[name=priority]', 3);
$I->click('.content .valid');
$I->see(TicketPage::$messageTicketDueDateError);