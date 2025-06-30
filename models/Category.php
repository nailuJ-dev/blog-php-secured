<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class Category
{
    private ?int $id = null;
    
    public function __construct(private string $title, private string $description)
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setId($id);
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setTitle(string $title): void
    {
        $this->title = trim($title);
    }
    
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }    
}