<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

class AuthController extends AbstractController
{
    public function login() : void
    {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        
        $this->render("login", ['csrf_token' => $csrfToken]);
    }

    public function checkLogin() : void
    {
        // si le login est valide => connecter puis rediriger vers la home
        // $this->redirect("index.php");

        // sinon rediriger vers login
        // $this->redirect("index.php?route=login");
        if ($_POST['csrf-token'] != $_SESSION['csrf_token'])
        {
            $this->redirect('index.php?route=login');
            return;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $userManager = new UserManager();
        $user = $userManager->findByEmail($email);
        
        if ($user && password_verify($password, $user->getPassword()))
        {
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8');
            $this->redirect('index.php');
        } else
        {
            $this->redirect('index.php?route=login');
        }
    }

    public function register() : void
    {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        
        $this->render("register", ['csrf_token' => $csrfToken]);
    }

    public function checkRegister() : void
    {
        // si le register est valide => connecter puis rediriger vers la home
        // $this->redirect("index.php");

        // sinon rediriger vers register
        // $this->redirect("index.php?route=register");
        
        if ($_POST['csrf-token'] != $_SESSION['csrf_token'])
        {
            $this->redirect('index.php?route=register');
            return;
        }
        
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        if ($password != $confirmPassword) {
            $this->redirect("index.php?route=register");
            return;
        }
        
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';
        if (!preg_match($passwordRegex, $password)) {
            $this->redirect("index.php?route=register");
            return;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userManager = new UserManager();
        $user = new User($username, $email, $hashedPassword, 'USER', new DateTime());
        $userManager->create($user);
        
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $username;
        
        $this->redirect("index.php");
    }

    public function logout() : void
    {
        session_destroy();

        $this->redirect("index.php");
    }
}