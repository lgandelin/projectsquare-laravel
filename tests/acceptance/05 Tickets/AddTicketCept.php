<?php

use Page\Ticket as TicketPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that I can add a new ticket');
$I->loginAsARessource();
$I->goToPage(TicketPage::$URI);
$I->click('.create-ticket');
$I->see('Ajouter un ticket');
$I->fillField('.content input[name=title]', 'Nouveau ticket');
$option = $I->grabTextFrom('.content select[name=type_id] option:nth-child(3)');
$I->selectOption('.content select[name=type_id]', $option);
$option = $I->grabTextFrom('.content select[name=project_id] option:nth-child(2)');
$I->selectOption('.content select[name=project_id]', $option);
$I->fillField('.content input[name=due_date]', (new \DateTime('tomorrow'))->format('d/m/Y'));
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$option = $I->grabTextFrom('.content select[name=status_id] option:nth-child(3)');
$I->selectOption('.content select[name=status_id]', $option);
$option = $I->grabTextFrom('.content select[name=allocated_user_id] option:nth-child(2)');
$I->selectOption('.content select[name=allocated_user_id]', $option);
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
$I->see('Ajouter un ticket');
$I->fillField('.content input[name=title]', 'Nouveau ticket');
$option = $I->grabTextFrom('.content select[name=type_id] option:nth-child(3)');
$I->selectOption('.content select[name=type_id]', $option);
$option = $I->grabTextFrom('.content select[name=project_id] option:nth-child(2)');
$I->selectOption('.content select[name=project_id]', $option);
$I->fillField('.content input[name=due_date]', (new \DateTime())->sub(new \DateInterval('P1D'))->format('d/m/Y'));
$I->fillField('.content textarea[name=description]', 'Lorem ipsum');
$option = $I->grabTextFrom('.content select[name=status_id] option:nth-child(3)');
$I->selectOption('.content select[name=status_id]', $option);
$option = $I->grabTextFrom('.content select[name=allocated_user_id] option:nth-child(2)');
$I->selectOption('.content select[name=allocated_user_id]', $option);
$I->selectOption('.content select[name=priority]', 3);
$I->click('.content .valid');
$I->see(TicketPage::$messageTicketDueDateError);