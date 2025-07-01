<?php

class CategoryManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
        
    }
    
    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM categories');
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $categories = [];
        foreach ($results as $item)
        {
            $category = new Category($item['title'], $item['description']);
            $category->setId($item['id']);
            $categories[] = $category;
            
        }
        return $categories;
    }
    
    public function findOne(int $id): ?Category
    {
        $query =  $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $query->execute([':id' => $id]);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($item)
        {
            $category = new Category($item['title'], $item['description']);
            $category->setId($item['id']);
            return $category;
        }
        return null;
    }
}