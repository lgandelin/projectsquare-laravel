<?php

use Page\Login as LoginPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    public function login($email, $password)
    {
        $I = $this;
        $I->amOnPage(LoginPage::$URI);
        $I->fillField(LoginPage::$emailField, $email);
        $I->fillField(LoginPage::$passwordField, $password);
        $I->click(LoginPage::$submitButton);
    }

    public function loginAsARessource()
    {
        $this->login('lgandelin@web-access.fr', 'GhDt6XdX');
    }

    public function loginAsAClient()
    {
        $this->login('client@web-access.fr', '111aaa');
    }

    public function goToPage($page)
    {
        $this->amOnPage($page);
    }
}
