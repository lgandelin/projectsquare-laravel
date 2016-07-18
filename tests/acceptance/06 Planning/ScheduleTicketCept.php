<?php

use Page\Ticket as TicketPage;
use Page\Planning as PlanningPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can schedule a new ticket already allocated');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 0);
$I->click('.create-ticket');
$I->fillField('.content input[name=title]', 'New ticket');
$I->selectOption('.content select[name=type_id]', 2);
$I->selectOption('.content select[name=project_id]', 1);
$I->fillField('.content input[name=due_date]', (new \DateTime())->format('d/m/Y'));
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$I->selectOption('.content select[name=status_id]', 2);
$I->selectOption('.content select[name=allocated_user_id]', 1);
$I->selectOption('.content select[name=priority]', 3);
$I->click('.content .valid');
$I->see(TicketPage::$messageTicketCreatedSuccess);
$I->goToPage(PlanningPage::$URI);
$I->see('New ticket');
$I->dragAndDrop('#my-tickets-list .ticket', '#planning tbody tr:first-child td');
$I->wait(5);
$I->reloadPage();
$I->see('New ticket');
$I->seeNumberOfElements('#planning tbody .fc-event', 1);

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can schedule a new ticket that has not been allocated');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->seeNumberOfElements('.content table tbody tr', 1);
$I->click('.create-ticket');
$I->fillField('.content input[name=title]', 'Another ticket');
$I->selectOption('.content select[name=type_id]', 2);
$I->selectOption('.content select[name=project_id]', 1);
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$I->selectOption('.content select[name=status_id]', 2);
$I->selectOption('.content select[name=priority]', 3);
$I->click('.content .valid');
$I->see(TicketPage::$messageTicketCreatedSuccess);
$I->goToPage(PlanningPage::$URI);
$I->dragAndDrop('#non-allocated-tickets-list .ticket', '#planning tbody tr:first-child td');
$I->wait(5);
$I->reloadPage();
$I->see('Another ticket');
$I->seeNumberOfElements('#planning tbody .fc-event', 2);