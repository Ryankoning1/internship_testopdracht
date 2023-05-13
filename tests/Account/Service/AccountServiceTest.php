<?php declare(strict_types=1);

namespace App\Tests\Account\Service;

use App\Account\Entity\Account;
use App\Account\Repository\AccountRepository;
use App\Account\Service\AccountService;
use App\Acme\Service\RandomUserClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class AccountServiceTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();

        $this->accountRepositoryMock = $this->createMock(AccountRepository::class);
        $this->encoderMock = $this->createStub(UserPasswordHasher::class);
        $this->randomUserClientMock = $this->createMock(RandomUserClient::class);


        $this->accountService = new AccountService(
            $this->accountRepositoryMock,
            $this->encoderMock,
            $this->randomUserClientMock
        );
    }

    /**
     * @dataProvider validateAccountProvider
     */
    public function testValidateAccount(array $accountData, bool $expectedResult) {
        if($expectedResult === false) {
            $this->expectException(\Exception::class);
        }

        $isValid = $this->accountService->validateAccount($accountData);

        if($expectedResult !== false) {
            $this->assertTrue($isValid);
        }

    }

    /**
     * @dataProvider sanitizeAccountProvider
     */
    public function testSanitizeAccount(array $dirtyAccountData, array $cleanAccountData) {
        $sanitizedData = $this->accountService->sanitizeAccount($dirtyAccountData);

        $this->assertSame($cleanAccountData, $sanitizedData);
    }

    public function testCreateAccount() {
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'Sewpassword4#56',
            'firstName' => 'John',
            'insertion' => '',
            'lastName' => 'Doe',
        ];

        $account = new Account();
        $account->setEmail($requestData['email']);
        $account->setFirstName($requestData['firstName']);
        $account->setInsertion($requestData['insertion']);
        $account->setLastName($requestData['lastName']);

        $this->encoderMock->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashedPassword');

        $this->accountRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $requestData['email']])
            ->willReturn(null);

        $this->accountRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn($account);

        $result = $this->accountService->createAccount($requestData);
        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals($requestData['email'], $result->getEmail());
    }

    public function testReadAccount() {
        $account = new Account();
        $uuid = $account->getUuid();


        $this->accountRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['uuid' => $uuid])
            ->willReturn($account);

        $result = $this->accountService->readAccount($uuid);
        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals($uuid, $result->getUuid());
    }

    public function testGetAccounts() {
        $accounts = [
            new Account(),
            new Account(),
            new Account(),
        ];

        $this->accountRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($accounts);

        $result = $this->accountService->getAccounts();

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(Account::class, $result);
    }

    public function testGetAccountsNoAccounts(): void
    {
        $this->accountRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = $this->accountService->getAccounts();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetAccountsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error retrieving accounts');

        $this->accountRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willThrowException(new \Exception('Error retrieving accounts'));

        $this->accountService->getAccounts();
    }

    public function testUpdateAccount() {
        $account = new Account();
        $uuid = $account->getUuid();


        $this->accountRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['uuid' => $uuid])
            ->willReturn($account);

        $this->accountRepositoryMock->expects($this->once())
            ->method('save')
            ->with($account)
            ->willReturn($account);

        $this->encoderMock->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashedPassword');

        // Update the account
        $newData = [
            'email' => 'updated@example.com',
            'password' => 'Sewpassword4#56',
            'firstName' => 'Jane',
            'insertion' => 'van',
            'lastName' => 'Doe'
        ];
        $updatedAccount = $this->accountService->updateAccount($account->getUuid(), $newData);

        // Check that the account was updated correctly
        $this->assertSame($newData['email'], $updatedAccount->getEmail());
        $this->assertSame($newData['firstName'], $updatedAccount->getFirstName());
        $this->assertSame($newData['insertion'], $updatedAccount->getInsertion());
        $this->assertSame($newData['lastName'], $updatedAccount->getLastName());
        $this->assertSame('hashedPassword', $updatedAccount->getPassword());
    }

    public function testDeleteAccount() {
        $account = new Account();
        $uuid = $account->getUuid();


        $this->accountRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['uuid' => $uuid])
            ->willReturn($account);

        $this->accountRepositoryMock->expects($this->once())
            ->method('removeAccount')
            ->with($account)
            ->willReturn($account);

        $deletedAccount = $this->accountService->deleteAccount($account->getUuid());

        $this->assertSame($account, $deletedAccount);
    }


    public function testImportAccounts()
    {
        $this->assertTrue($this->accountService->importAccounts());
    }

    private function sanitizeAccountProvider(): array {
        return [
            [
                // Dirty data
                [
                    'password' => '   password123   ',
                    'email' => '  test@example.com ',
                    'firstName' => '   John',
                    'insertion' => 'de  ',
                    'lastName' => 'Doe  ',
                ],
                // Clean data
                [
                    'password' => 'password123',
                    'email' => 'test@example.com',
                    'firstName' => 'John',
                    'insertion' => 'de',
                    'lastName' => 'Doe',
                ],
            ],
            [
                // Dirty data with special characters
                [
                    'password' => '   passwörd123   ',
                    'email' => '  tëst@example.com ',
                    'firstName' => '   Jöhn',
                    'insertion' => 'dë  ',
                    'lastName' => 'Döe  ',
                ],
                // Clean data with special characters
                [
                    'password' => 'passwörd123',
                    'email' => 'tëst@example.com',
                    'firstName' => 'Jöhn',
                    'insertion' => 'dë',
                    'lastName' => 'Döe',
                ],
            ],
        ];
    }

    private function validateAccountProvider(): array {
        return [
            [
                // Valid account data
                [
                    'firstName' => 'John',
                    'insertion' => '',
                    'lastName' => 'Doe',
                    'email' => 'test@example.com',
                    'password' => 'Password123!',
                ],
                true,
            ],
            [
                // Invalid account data - missing firstName
                [
                    'insertion' => '',
                    'lastName' => 'Doe',
                    'email' => 'test@example.com',
                    'password' => 'Password123!',
                ],
                false,
            ],
            [
                // Invalid account data - invalid email format
                [
                    'firstName' => 'John',
                    'insertion' => '',
                    'lastName' => 'Doe',
                    'email' => 'invalid-email',
                    'password' => 'Password123!',
                ],
                false,
            ],
            [
                // Invalid account data - weak password
                [
                    'firstName' => 'John',
                    'insertion' => '',
                    'lastName' => 'Doe',
                    'email' => 'test@example.com',
                    'password' => 'weak',
                ],
                false,
            ],
            [
                // Account data with special characters
                [
                    'firstName' => 'Jöhn',
                    'insertion' => '',
                    'lastName' => 'Döe',
                    'email' => 'test@example.com',
                    'password' => 'Password123!',
                ],
                true,
            ],
        ];
    }
}
