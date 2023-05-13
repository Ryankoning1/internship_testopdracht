<?php declare(strict_types=1);

namespace App\Account\Entity;

use App\Account\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_ADMIN_USER = 'ROLE_ADMIN_USER';
    const ROLE_API_USER = 'ROLE_API_USER';
    const ROLE_USER = 'ROLE_USER';

    const ROLES = [
        self::ROLE_ADMIN_USER,
        self::ROLE_API_USER,
        self::ROLE_USER,
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private readonly string $uuid;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $roles;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $firstName;

    #[ORM\Column(type: 'string', length: 18, nullable: true)]
    private ?string $insertion;

    #[ORM\Column(type: 'string', length: 18, nullable: true)]
    private ?string $lastName;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $createdOn;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedOn;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
        $dateTime = new DateTime;
        $this->setRoles([self::ROLE_USER]);
        $this->setUpdatedOn($dateTime);
        $this->setCreatedOn($dateTime);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Account
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): Account
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): Account
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getInsertion(): ?string
    {
        return $this->insertion;
    }

    public function setInsertion(?string $insertion): Account
    {
        $this->insertion = $insertion;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): Account
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true);
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = json_encode($roles);

        return $this;
    }


    public function getCreatedOn(): DateTime
    {
        return $this->createdOn;
    }

    public function setCreatedOn(DateTime $createdOn): Account
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(DateTime $updatedOn): Account
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    public function getSalt(): ?string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function toArray(): array
    {

        return [
            'uuid' => $this->getUuid(),
            'firstName' => $this->getfirstName(),
            'insertion' => $this->getInsertion(),
            'lastName' => $this->getlastName(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            'createdOn' => $this->getCreatedOn()?->getTimestamp(),
            'updatedOn' => $this->getUpdatedOn()?->getTimestamp()
            ];
    }
}
