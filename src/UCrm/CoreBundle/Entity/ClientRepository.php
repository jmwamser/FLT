<?php

namespace UCrm\CoreBundle\Entity;


use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
	
	public function findAllOfUser($user)
	{
		return $this->createQueryBuilder('c')
			->where('c.userId = :uid')
			->setParameter('uid', $user->getId())
			->getQuery()->getResult();
	}

	public function findAllInTerritory($territory)
	{
		return $this->createQueryBuilder('c')
			->where('c.territoryId = :territory_id')
			->setParameter('territory_id', $territory->getId())
			->getQuery()->getResult();
	}

}
