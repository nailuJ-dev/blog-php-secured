<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */
require '../models/Comment.php';
require 'AbstractManager.php';

class CommentManager extends AbstractManager
{
    public function findByPost(int $postId): array
    {
        $postManager = new PostManager();
        $postObject = $postManager->findOne($postId);

        $query = $this->db->prepare(
            'SELECT comments.*, users.username, users.email, users.role, users.created_at 
             FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = :postId'
        );
        $query->bindParam(':postId', $postId, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($results as $item) {
            $user = new User($item['username'], $item['email'], $item['role'], $item['user_created_at'], $item['user_id']);
            $comments[] = new Comment($item['content'], $user, $postObject, $item['id']);
        }
        return $comments;
    }

    public function create(Comment $comment): void
    {
        $query = $this->db->prepare('INSERT INTO comments (content, user_id, post_id) VALUES (:content, :user_id, :post_id)');
        $query->execute([
            ':content' => $comment->getContent(),
            ':user_id' => $comment->getUser()->getId(),
            ':post_id' => $comment->getPost()->getId(),    
        ]);
        $comment->setId($this->db->lastInsertId());
    }
}