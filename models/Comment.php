<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class Comment
{

    private ?int $id = null;
    
    public function __construct(private string $content, private User $user, private Post $post)
    {
        $this->setContent($content);
        $this->setUser($user);
        $this->setPost($post);
    }
    
    public function getId(): int 
    { 
        return $this->id;
    }
    
    public function getContent(): string 
    { 
        return $this->content;
    }
    
    public function getUser(): User 
    { 
        return $this->user; 
    }
    
    public function getPost(): Post 
    { 
        return $this->post;
    }
    
    public function setId(int $id): void 
    { 
        $this->id = $id;
    }
    
    public function setContent(string $content): void 
    { 
        $this->content = $content;
    }
    
    public function setUser(User $user): void
    { 
        $this->user = $user;
    }
    
    public function setPost(Post $post): void 
    { 
        $this->post = $post;
    }
    
}