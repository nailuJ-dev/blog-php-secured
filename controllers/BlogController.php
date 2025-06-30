<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class BlogController extends AbstractController
{
    public function home() : void
    {
        $postManager = new PostManager();
        $posts = $postManager->findLatest();
        
        foreach ($posts as $post)
        {
            $post->setTitle(htmlspecialchars($post->getTitle(), ENT_QUOTES, 'UTF-8'));
            $post->setExcerpt(htmlspecialchars($post->getExcerpt(), ENT_QUOTES, 'UTF-8'));
        }
        
        $this->render("home", ['posts' => $posts]);
    }

    public function category(string $categoryId) : void
    {
        $categoryManager = new CategoryManager();
        $category = $categoryManager->findOne((int)$categoryId);
        // si la catégorie existe
        if ($category)
        {
            $postManager = new PostManager();
            $posts = $postManager->findByCategory((int)$categoryId);
            
            $category->setTitle(htmlspecialchars($category->getTitle(), ENT_QUOTES, 'UTF-8'));
            foreach ($posts as $post)
            {
                $post->setTitle(htmlspecialchars($post->getTitle(), ENT_QUOTES, 'UTF-8'));
                $post->setExcerpt(htmlspecialchars($post->getExcerpt(), ENT_QUOTES, 'UTF-8'));
            }
            $this->render("category", ['category' => $category, 'posts' => $posts]);
        } else 
        {
            $this->redirect("index.php");
        }
    }

    public function post(string $postId) : void
    {
        
        // si le post existe
        $this->render("post", []);

        // sinon
        $this->redirect("index.php");
    }

    public function checkComment() : void
    {
        $this->redirect("index.php?route=post&post_id={$_POST["post_id"]}");
    }
}