<?php

namespace LCV\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository {
    public function findAll() {
        $queryBuilder = $this -> createQueryBuilder('a');

        $query = $queryBuilder -> getQuery();

        $result = $query -> getResult();

        return results;
    }

    public function myFindOne($id) {
        $qb = $this -> createQueryBuilder('a');

        $qb -> where('a.id = :id') -> setParameter('id', $id);

        return $qb -> getQuery() -> getResult();
    }

    public function findByAuthorAndDate($author, $year) {
        $qb = $this -> createQueryBuilder('a');

        $qb -> where('a.author = :author') 
        -> setParameter('author', $author) 
        -> andWhere('a.date < :year') 
        -> setParameter('year', $year) 
        -> orderBy('a.date', 'DESC');

        return $qb -> getQuery() -> getResult();
    }

    public function whereCurrentYear(QueryBuilder $qb) {
        $qb -> andWhere('a.date BETWEEN :start AND :end') -> setParameter('start', new DateTime(date('Y') . '01-01')) -> setParameter('end', new DateTime(date('Y') . '-12-31'));
    }
    
    public function getArticles(){
    	$query = $this->createQueryBuilder('a')
    	->orderBy('a.date','DESC')
    	->getQuery();
    
    	return $query->getResult();
    }
    
    public function getLastArticles($limite){
    	$qb = $this->createQueryBuilder('a')
    	 		   ->orderBy('a.date','DESC')
    	 		   ->setMaxResults($limite);
    	

    	    return $qb
    		  ->getQuery()
     		 ->getResult()
      ;
    }
    

    public function getArticlesWithCategory(){
    	$query = $this->createQueryBuilder('a')
    	->leftJoin('a.category','c')
    	->addSelect('c')
        ->leftJoin('a.rate','r')
        ->addSelect('r')
        ->orderBy('c.name','ASC')
    	->getQuery();
    
    	return $query->getResult();
    }
    
    public function getArticleVoteUser($article_id,$user){
        $query = $this->createQueryBuilder('article')
        ->where('article.id = :article_id')
        ->leftJoin('article.rate','rate')
        ->addSelect('rate')
        ->leftJoin('rate.votes','vote')
        ->andWhere('vote.voter = :user')
        ->setParameters(array('user' => $user, 'article_id' => $article_id))
        ->getQuery();
        
        return $query->getResult();
    }
    

}
