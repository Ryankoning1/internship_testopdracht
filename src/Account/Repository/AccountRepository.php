<?php declare(strict_types=1);

namespace App\Account\Repository;

use App\Account\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Account|null find($uuid, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository {


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function removeAccount(Account $account): ?Account
    {
        $this->getEntityManager()->remove($account);
        $this->getEntityManager()->flush();

        return $account;
    }

    public function save(Account $account): ?Account
    {
        if($account->getUpdatedOn() === $account->getCreatedOn()) {
            $this->getEntityManager()->persist($account);
        }

        $this->getEntityManager()->flush();

        return $account;
    }
}
