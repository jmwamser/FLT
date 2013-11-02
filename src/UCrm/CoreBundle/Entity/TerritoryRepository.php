<?php

namespace UCrm\CoreBundle\Entity;


use Doctrine\ORM\EntityRepository;

class TerritoryRepository extends EntityRepository
{
	
	public function findAllWithUser()
	{
		return $this->createQueryBuilder('t')
			->select('t, u')
			//->from('UCrm\CoreBundle\Entity\Territory', 't')
			->leftJoin('t.user', 'u')
			->getQuery()->getResult();
	}

	public function findAllWithUserFor($user)
	{
		return $this->createQueryBuilder('t')
			->select('t, u')
			//->from('UCrm\CoreBundle\Entity\Territory', 't')
			->leftJoin('t.user', 'u')
			->where('t.checkedOutTo = :uid')
			->setParameter('uid', $user->getId())
			->getQuery()->getResult();
	}

}
