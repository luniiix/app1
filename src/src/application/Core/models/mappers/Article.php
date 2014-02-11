<?php 

class Core_Model_Mapper_Article
{
    
    public function find($id){
        $dbTable = new Core_Model_DbTable_Article();
        $rowSet = $dbTable->find(array('article_id' => $id));
        $row = $rowSet->current();
        
        $article = new Core_Model_Article();
        $article->setArticleId($row->article_id)
                ->setArticleTitle($row->article_title)
                ->setArticleContent($row->article_content);
        
        $authorRow = $row->findParentRow('Core_Model_DbTable_Author');
        $author = new Core_Model_Author();
        $author->setAuthorId($authorRow->author_id)
                ->setAuthorName($authorRow->author_name)
                ->setUserId($authorRow->user_id);
        
        $article->setAuthor($author);
        
        return $article;
    }
    
    public function fetchLast($count)
    {
        $dbTable = new Core_Model_DbTable_Article();
        $rowSet = $dbTable->fetchAll(null,array('article_id DESC'),$count);
        
        if (0 === $rowSet->count()) {
            return false;
        }
        
        $articles = array();
        foreach($rowSet as $row) {
            $article = new Core_Model_Article();
            $article->setArticleId($row->article_id)
                    ->setArticleTitle($row->article_title)
                    ->setArticleContent($row->article_content);
            
            $authorRow = $row->findParentRow('Core_Model_DbTable_Author');
            $author = new Core_Model_Author();
            $author->setAuthorId($authorRow->author_id)
                   ->setAuthorName($authorRow->author_name)
                   ->setUserId($authorRow->user_id);
            
            $article->setAuthor($author);
            $articles[] = $article;
        }
        
        return $articles;
    }
    
    public function delete(Core_Model_Article $article){
        $dbTable = new Core_Model_DbTable_Article();
        $rowSet = $dbTable->find(array('article_id' => $article->getArticleId()));
        
        $row = $rowSet->current();
        $row->delete();
    }
}