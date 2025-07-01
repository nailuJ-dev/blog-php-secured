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
        $postManager = new PostManager();
        $post = $postManager->findOne((int)$postId);
        
        if ($post)
        {
            $commentManager = new CommentManager();
            $comments = $commentManager->findByPost((int)$postId);
            
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrfToken;
            
            $post->setTitle(htmlspecialchars($post->getTitle(), ENT_QUOTES, 'UTF-8'));
            $post->setContent($post->getContent());
            
            foreach ($comments as $comment)
            {
                $comment->setContent(htmlspecialchars($comment->getContent(), ENT_QUOTES, 'UTF-8'));
            }
            
            $this->render("post", [
                'post' => $post,
                'comments' => $comments,
                'csrf_token' => $csrfToken
            ]);
        } else
        {
            $this->redirect("index.php");
        }
    }

    public function checkComment() : void
    {
        if ($_POST['csrf-token'] != $_SESSION['csrf_token']) {
            $this->redirect("index.php?route=post&post_id={$_POST["post_id"]}");
            return;
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect("index.php?route=login");
            return;
        }
        
        $postId = (int)$_POST['post_id'];
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
        
        $userManager = new UserManager();
        $user = $userManager->findById($_SESSION['user_id']);
        
        $postManager = new PostManager();
        $post = $postManager->findOne($postId);
        
        $comment = new Comment($content, $user, $post);
        $commentManager = new CommentManager();
        $commentManager->create($comment);
        
        $this->redirect("index.php?route=post&post_id={$_POST["post_id"]}");
    }
}