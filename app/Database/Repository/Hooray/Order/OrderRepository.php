<?php
declare(strict_types=1);

namespace App\Database\Repository\Hooray\Order;

use App\Database\Entity\Hooray\Order\Order;
use Doctrine\ORM\EntityRepository;

/**
 * @method Order|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Order|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Order[] findAll()
 * @method Order[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends EntityRepository<OrderRepository>
 */
class OrderRepository extends EntityRepository
{
}