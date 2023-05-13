<?php declare(strict_types=1);

namespace App\Tests\Account\Controller;

use App\Account\Controller\AccountController;
use App\Account\Entity\Account;
use App\Account\Service\AccountService;
use App\Tests\Account\Helper\AuthenticationHelper;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AccountControllerTest extends TestCase
{
    use AuthenticationHelper;
    private AccountController $accountController;
    private AccountService $accountServiceMock;

    private Account $userAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountServiceMock =  $this->createMock(AccountService::class);
        $this->accountController = new AccountController($this->accountServiceMock);

        $account = new Account();
        $account->setEmail($this->accounts['user']['email']);
        $account->setPassword($this->accounts['user']['password']);
        $account->setFirstName($this->accounts['user']['firstName']);
        $account->setInsertion('');
        $account->setLastName($this->accounts['user']['lastName']);
        $account->setRoles($this->accounts['user']['roles']);
        $this->userAccount = $account;
    }

    public function testCreateAccountAction()
    {
        $request = new Request([], [], [], [], [], [], json_encode($this->accounts['user']));

        $this->accountServiceMock->expects($this->once())
            ->method('createAccount')
            ->with($this->accounts['user'])
            ->willReturn($this->userAccount);

        $response = $this->accountController->createAccountAction($request);


        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($this->userAccount->toArray(), json_decode($response->getContent(), true));
    }

    public function testReadAccountAction()
    {
        $this->accountServiceMock->expects($this->once())
            ->method('readAccount')
            ->with($this->userAccount->getUuid())
            ->willReturn($this->userAccount);

        $response = $this->accountController->readAccountAction($this->userAccount->getUuid());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->userAccount->toArray(), json_decode($response->getContent(), true));
    }
    public function testReadAccountWhenNotFoundAction()
    {
        $this->accountServiceMock->expects($this->once())
            ->method('readAccount')
            ->with($this->userAccount->getUuid())
            ->willThrowException(new Exception('Account not found', 404));

        $response = $this->accountController->readAccountAction($this->userAccount->getUuid());

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Account not found'], json_decode($response->getContent(), true));
    }

    public function testUpdateAccountAction()
    {
        $request = new Request([], [], [], [], [], [], json_encode($this->accounts['user']));

        $this->accountServiceMock->expects($this->once())
            ->method('updateAccount')
            ->with($this->userAccount->getUuid(), $this->accounts['user'])
            ->willReturn($this->userAccount);

        $response = $this->accountController->updateAccountAction($request, $this->userAccount->getUuid());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->userAccount->toArray(), json_decode($response->getContent(), true));
    }
    public function testUpdateAccountWhenNotFoundAction()
    {
        $request = new Request([], [], [], [], [], [], json_encode($this->accounts['user']));

        $this->accountServiceMock->expects($this->once())
            ->method('updateAccount')
            ->with($this->userAccount->getUuid(), $this->accounts['user'])
            ->willThrowException(new Exception('Account not found', 404));

        $response = $this->accountController->updateAccountAction($request, $this->userAccount->getUuid());

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Account not found'], json_decode($response->getContent(), true));
    }

    public function testDeleteAccountAction()
    {
        $this->accountServiceMock->expects($this->once())
            ->method('deleteAccount')
            ->with($this->userAccount->getUuid())
            ->willReturn($this->userAccount);

        $response = $this->accountController->deleteAccountAction($this->userAccount->getUuid());

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals([], json_decode($response->getContent(), true));
    }
    public function testDeleteAccountWhenNotFoundAction()
    {
        $this->accountServiceMock->expects($this->once())
            ->method('deleteAccount')
            ->with($this->userAccount->getUuid())
            ->willThrowException(new Exception('Account not found', 404));

        $response = $this->accountController->deleteAccountAction($this->userAccount->getUuid());

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Account not found'], json_decode($response->getContent(), true));
    }

}
