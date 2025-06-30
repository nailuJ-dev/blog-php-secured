<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class Post
{
    private ?int $id = null;
    private array $categories = [];
    
    public function __construct(private string $title, private string $excerpt, private string $content, private DateTime $createdAt, private User $author)
    {
        $this->setTitle($title);
        $this->setExcerpt($excerpt);
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setCreatedAt($createdAt);
    }
    
    public function getId(): ?int 
    { 
        return $this->id;
    }
    
    public function getTitle(): string 
    { 
        return $this->title;
    }
    
    public function getExcerpt(): string 
    { 
        return $this->excerpt;
    }
    
    public function getContent(): string 
    { 
        return $this->content;
    }
    
    public function getCreatedAt(): DateTime 
    { 
        return $this->created_at;
    }
    
    public function getAuthor(): User 
    { 
        return $this->author; 
    }
    
    public function getCategories(): array 
    { 
        return $this->categories;
    }
    
    public function setId(int $id): void 
    { 
        $this->id = $id;
    }
    
    public function setTitle(string $title): void 
    { 
        $this->title = trim($title);
    }
    
    public function setExcerpt(string $excerpt): void
    { 
        $this->excerpt = $excerpt;
    }
    
    public function setContent(string $content): void
    { 
        $this->content = $content;
    }
    
    public function setCreatedAt(DateTime $createdAt): void
    { 
        $this->created_at = $createdAt;
    }
    
    public function setAuthor(User $author): void 
    { 
        $this->author = $author; 
    }
    
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }
}