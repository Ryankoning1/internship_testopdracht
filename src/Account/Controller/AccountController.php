<?php declare(strict_types=1);

namespace App\Account\Controller;

use App\Account\Service\AccountService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController
{
    public function __construct(
        private readonly AccountService $accountService
    )
    {}

    public function getAccountListAction(): Response
    {
        try {
            $response = [];
            $accounts = $this->accountService->getAccounts();

            foreach($accounts as $account) {
                $response[] = $account->toArray();
            }

            return new JsonResponse($response);

        } catch (Exception $e) {

            return new JsonResponse(['error' => $e->getMessage()], ($e->getCode() === 0 ? 500 : $e->getCode()));

        }
    }

    public function createAccountAction(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        try {
            $account = $this->accountService->createAccount($requestData);

            return new JsonResponse($account->toArray(), 201);
        } catch (Exception $e) {

            return new JsonResponse(['error' => $e->getMessage()], ($e->getCode() === 0 ? 500 : $e->getCode()));
        }
    }

    public function readAccountAction(string $uuid): Response
    {
        try {
            $account = $this->accountService->readAccount($uuid);


            return new JsonResponse($account?->toArray());

        } catch (Exception $e) {

            return new JsonResponse(['error' => $e->getMessage()], ($e->getCode() === 0 ? 500 : $e->getCode()));

        }
    }

    public function updateAccountAction(Request $request, string $uuid): Response
    {

        $requestData = json_decode($request->getContent(), true);

        try {
            $account = $this->accountService->updateAccount($uuid, $requestData);

            return new JsonResponse($account->toArray());

        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], ($e->getCode() === 0 ? 500 : $e->getCode()));
        }
    }

    public function deleteAccountAction(string $uuid): Response
    {
        try {
            $this->accountService->deleteAccount($uuid);

            return new JsonResponse([], 204);

        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], ($e->getCode() === 0 ? 500 : $e->getCode()));
        }
    }

}
