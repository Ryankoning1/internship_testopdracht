<?php declare(strict_types=1);

namespace App\Tests\Account\Controller;

use App\Tests\Account\Helper\AuthenticationHelper;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerFunctionalTest extends WebTestCase
{
    use AuthenticationHelper;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testAdminAccountShouldBeAbleToSeeAccounts()
    {
        $this->client->request('GET', '/api/v1/account', [], [], $this->getAdminAuthentication());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserAccountShouldNotBeAbleToSeeAccounts()
    {
        $this->expectException(AccessDeniedException::class);

        $this->client->catchExceptions(false);
        $this->client->request('GET', '/api/v1/account', [], [], $this->getUserAuthentication());

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
    public function testAnonymousAccountShouldNotBeAbleToSeeAccounts()
    {
        $this->client->request('GET', '/api/v1/account');

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexAction()
    {
        $this->client->request('GET', '/api/v1');


        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('', $this->client->getResponse()->getContent());
    }

}
