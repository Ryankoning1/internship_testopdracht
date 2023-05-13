<?php declare(strict_types=1);

namespace App\Account\Service;

use App\Account\Entity\Account;
use App\Account\Repository\AccountRepository;
use App\Acme\Service\RandomUserClient;
use GWSN\Helpers\Services\Validators\EmailValidate;
use GWSN\Helpers\Services\Validators\PasswordValidate;
use GWSN\Helpers\Services\Validators\StringValidate;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly UserPasswordHasherInterface $encoder,
        private readonly RandomUserClient $randomUserClient
    )
    {
    }

    public function validateAccount(array $requestData): bool
    {
        $stringCheckKeys = [
            'firstName',
            'insertion',
            'lastName'
        ];

        foreach ($stringCheckKeys as $checkKey) {
            StringValidate::Validate($checkKey, $requestData, true);
        }

        EmailValidate::Validate('email', $requestData, true);

        PasswordValidate::Validate($requestData['password'], 7);

        return true;
    }

    public function sanitizeAccount(array $data): array
    {
        $stringCheckKeys = [
            'password',
            'email',
            'firstName',
            'insertion',
            'lastName'
        ];

        foreach ($stringCheckKeys as $checkKey) {
            if (array_key_exists($checkKey, $data)) {
                $data[$checkKey] = trim($data[$checkKey]);
            }
        }
        return $data;
    }

    public function createAccount(mixed $requestData): Account
    {
        // Sanitize data and validate
        $requestData = $this->sanitizeAccount($requestData);
        $this->validateAccount($requestData);

        // Check if account already exists
        $account = $this->accountRepository->findOneBy(['email' => $requestData['email']]);
        if ($account !== null) {
            throw new Exception('Account already found', 400);
        }
        $account = new Account();

        $this->writeRequestDataToAccount($account, $requestData);

        $this->save($account);
        return $account;
    }

    public function readAccount(string $uuid): ?Account
    {
        $account = $this->accountRepository->findOneBy(['uuid' => $uuid]);

        if ($account === null) {
            throw new Exception('Account not found', 404);
        }

        return $account;
    }

    /** @return Account[] */
    public function getAccounts(): array
    {
        return $this->accountRepository->findAll();
    }

    public function updateAccount(string $uuid, mixed $requestData): Account
    {
        // Sanitize data and validate
        $requestData = $this->sanitizeAccount($requestData);
        $this->validateAccount($requestData);

        // Check if account exists
        $account = $this->readAccount($uuid);

        $this->writeRequestDataToAccount($account, $requestData);

        $account->setUpdatedOn(new \DateTime());
        $this->save($account);

        return $account;
    }

    public function deleteAccount(string $uuid): Account
    {
        $account = $this->readAccount($uuid);

        return $this->accountRepository->removeAccount($account);
    }

    private function writeRequestDataToAccount(Account $account, array $requestData): Account
    {
        $account->setEmail($requestData['email']);

        $account->setFirstName($requestData['firstName']);
        $account->setInsertion($requestData['insertion']);
        $account->setLastName($requestData['lastName']);

        $account->setPassword($this->encoder->hashPassword($account, $requestData['password']));

        return $account;
    }

    private function save(Account $account): Account
    {
        // Save
        return $this->accountRepository->save($account);
    }

    public function importAccounts(): bool
    {
        // @todo implement
        // $this->randomUserClient->getUsers(10);

        return true;
    }

}
