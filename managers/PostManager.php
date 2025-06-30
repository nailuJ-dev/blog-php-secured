<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */
require '../models/Post.php';
require 'AbstractManager.php';

class PostManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
        
    }
    
    private function hydratePost(array $item): Post
    {
        $author = new User($item['username'], $item['email'], $item['role'], $item['created_at'], $item['author']);
        $categories = $this->findCategoriesForPost($item['id']);
        return new Post($item['title'], $item['excerpt'], $item['content'], $item['created_at'], $item['id'], $categories, $author);
    }
    
    public function findLatest(): array
    {
        $query = $this->db->prepare(
            'SELECT posts.*, users.username, users.email, users.role, users.created_at FROM posts 
             JOIN users ON posts.author = users.id ORDER BY posts.created_at DESC LIMIT 4'
        );
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $posts = [];
        foreach ($results as $item) {
            $posts[] = $this->hydratePost($item);
        }
        return $posts;
    }
    
    public function findOne(int $id): ?Post
    {
        $query = $this->db->prepare(
            'SELECT posts.*, users.username, users.email, users.role, users.created_at FROM posts 
             JOIN users ON posts.author = users.id WHERE posts.id = :id'
        );
        $query->execute([':id' => $id]);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            return $this->hydratePost($item);
        }
        return null;
    }

    public function findByCategory(int $categoryId): array
    {
        $query = $this->db->prepare(
            'SELECT posts.*, users.username, users.email, users.role, users.created_at FROM posts 
             JOIN users ON posts.author = users.id
             JOIN posts_categories ON posts.id = posts_categories.post_id
             WHERE posts_categories.category_id = :categoryId ORDER BY posts.created_at DESC'
        );
        $query->execute([':categoryId' => $categoryId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($results as $item) {
            $posts[] = $this->hydratePost($item);
        }
        return $posts;
    }
    
    private function findCategoriesForPost(int $postId): array
    {
        $query = $this->db->prepare(
            'SELECT categories.* FROM categories
             JOIN posts_categories ON categories.id = posts_categories.category_id WHERE posts_categories.post_id = :postId'
        );
        $query->execute([':postId' => $postId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $categories = [];
        foreach ($results as $item) {
            $categories[] = new Category($item['title'], $item['description'], $item['id']);
        }
        return $categories;
    }
}