<?php

namespace App\Repository;

use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Provider>
 *
 * @method Provider|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provider|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provider[]    findAll()
 * @method Provider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Provider[]    findAllProviders()
 * @method Provider[]    findAllActive()
 * @method Provider[]    findAllInactive()
 * @method Provider|null findProviderById($id)
 * @method Provider[]    createProvider($provider)
 * @method Provider[]    modifyProvider($provider)
 * @method Provider[]    deleteProvider($id)
 */
class ProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Provider $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Provider $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Provider[] Returns an array of Provider objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Provider
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //MÈTODES PER MOSTRAR INFORMACIÓ PER PANTALLA:
    // Funció per obtenir tots els proveidors
    public function findAllProviders(){
        return $this-> getEntityManager()
            ->createQuery('SELECT * FROM App\Entity\Provider provider') // SELECT * FROM `provider`
            ->getResult();
    }

    // Funció per filtrar els proveidors que estiguin actius
    public function findAllActive(){
        return $this->getEntityManager()
            ->createQuery(('SELECT * FROM App\Entity\Provider providers WHERE provider.activity = 1'))
            ->getResult();
    }

    // Funció per filtrar els proveidors que estiguin insactius
    public function findAllInactive(){
        return $this->getEntityManager()
            ->createQuery(('SELECT * FROM App\Entity\Provider providers WHERE provider.activity = 0'))
            ->getResult();
    }

    // Funció per buscar a un proveidor per el seu id
    public function findProviderById($id):?Provider {
        return $this->getEntityManager()
            ->createQuery(('SELECT * FROM App\Entity\Provider providers WHERE provider.id = :id'))
            ->setParameter('id', $id)
            ->getSingleResult(); 
    }

    // MÈTODES PER LA CREACIÓ DE NOUS PROVEIDORS
    public function createProvider($provider):void{ // Mirar de modificar i que passin els altributs per separat
        try {
            // Intentarem inserir les dades del proveidor nou a la base de dades
            $this->getEntityManager()
            ->createQuery('INSERT INTO App\Entity\Provider providers VALUES (provider.name, provider.email, provider.phone, provider.type, provider.activity) VALUES (name, email, phone, type, activity)')
                ->setParameter('name', $provider->getName())
                ->setParameter('email', $provider->getEmail())
                ->setParameter('phone', $provider->getPhone())
                ->setParameter('type', $provider->getType())
                ->setParameter('activity', $provider->getActivity())
                ->setParameter('id', $provider->getId())
                ->execute();
            $this->_em->persist($provider);
            $this->_em->flush();
    
            // Si es pot realitzar correctament retornarem un missatge d'èxit
            echo "Seccussfully created!";
        } catch (\Exception $e) {
            // Si es produeix algun error informarem per pantalla
            echo "There has been an error: " . $e->getMessage();
        }
    }

    // MÈTODES PER L'ACTUALITZACIÓ DE PROVEIDORS
    public function modifyProvider($provider):void{ // Enviem l'id per buscar-lo amb l'altre funció
        try {
            // Intentarem actualitzar les dades del proveidor especificat
            $this->getEntityManager()
            ->createQuery('UPDATE App\Entity\Provider providers SET provider.name = :name, provider.email = :email, provider.phone = :phone, provider.type = :type, provider.activity = :activity WHERE provider.id = :id')
                ->setParameter('name', $provider->getName())
                ->setParameter('email', $provider->getEmail())
                ->setParameter('phone', $provider->getPhone())
                ->setParameter('type', $provider->getType())
                ->setParameter('activity', $provider->getActivity())
                ->setParameter('id', $provider->getId())
                ->execute();

            $this->_em->flush();
    
            // Si es pot realitzar correctament retornarem un missatge d'èxit
            echo "Seccussfully updated!";
        } catch (\Exception $e) {
            // Si es produeix algun error informarem per pantalla
            echo "There has been an error: " . $e->getMessage();
        }
    }

    // MÈTODES PER L'ELIMINACIÓ DE PROVEIDORS
    public function deleteProvider($id):void{
        $provider = $this->findProviderById($id); // Busquem el proveidor per l'id
        $this->remove($provider); // Eliminem el proveidor de la base de dades
    }
}
