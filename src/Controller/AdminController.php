<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Card;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
     /**
     * Performs a database query to get all the records related to the given
     * entity. It supports pagination and field sorting.
     *
     * @param string      $entityClass
     * @param int         $page
     * @param int         $maxPerPage
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param string|null $dqlFilter
     *
     * @return Pagerfanta The paginated query results
     */
    public function findAll($entityClass, $page = 1, $maxPerPage = 15, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        if (null === $sortDirection || !\in_array(\strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }
        $queryBuilder = $this->executeDynamicMethod('create<EntityName>ListQueryBuilder', [$entityClass, $sortDirection, $sortField, $dqlFilter]);
        $this->filterQueryBuilder($queryBuilder);
        $this->dispatch(EasyAdminEvents::POST_LIST_QUERY_BUILDER, [
            'query_builder' => $queryBuilder,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection,
        ]);
        return $this->get('easyadmin.paginator')->createOrmPaginator($queryBuilder, $page, $maxPerPage);
    }

    /**
     * Creates Query Builder instance for all the records.
     *
     * @param string      $entityClass
     * @param string      $sortDirection
     * @param string|null $sortField
     * @param string|null $dqlFilter
     *
     * @return QueryBuilder The Query Builder instance
     */
    public function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        // add dqlFilter that display elements of the logged user (elements of User had to be fetch in the new controller UserController)
        if (null === $dqlFilter) {
            $dqlFilter = sprintf('entity.user = %s', $this->getUser()->getId());
        } else {
            $dqlFilter .= sprintf(' AND entity.user = %s', $this->getUser()->getId());
        }
        
        return $this->get('easyadmin.query_builder')->createListQueryBuilder($this->entity, $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * This method overrides the default query builder used to search for this
     * entity. This allows to make a more complex search joining related entities.
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var EntityManager */
        $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);

        // the property to search
        $name = null;
        
        if (($this->entity['class']) == 'App\Entity\Tag') {
            $name = 'name';
        } elseif (($this->entity['class']) == 'App\Entity\Card') {
            $name = 'recto';
        }

        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser()->getId())
            ->andWhere('LOWER(entity.' . $name . ') LIKE :query')
            ->setParameter('query', '%' . strtolower($searchQuery) . '%')
            ;
            
        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.' . $sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }
    






}
