<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

class CommentManager extends AbstractManager
{
    public function findByPost(int $postId): array
    {
        $postManager = new PostManager();
        $postObject = $postManager->findOne($postId);

        $query = $this->db->prepare(
            'SELECT comments.*, users.username, users.email, users.password, users.role, users.created_at, users.id as user_id 
             FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = :postId'
        );
        $query->execute([':postId' => $postId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($results as $item) {
            $user = new User($item['username'], $item['email'], $item['password'], $item['role'], new DateTime($item['created_at']));
            $user->setId($item['user_id']);
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
        $comment->setId((int)$this->db->lastInsertId());
    }
}