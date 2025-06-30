<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
        
    }
    
    public function findByEmail(string $email): ?User
    {
        $query =  $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute([':email' => $email]);
        $item = $query->fetch(PDO::FETCH_ASSOC);
        
        if($item)
        {
            $user = new User($item['username'], $item['email'], $item['password'], $item['role'], new DateTime($item['created_at']));
            $user->setId($item['id']);
            return $user;
        }
        
        return null;
    }
    
    public function create(User $user): void
    {
        $query = $this->db->prepare('INSERT INTO users (username, email, password, role, created_at) VALUES (:username, :email, :password, :role, :created_at)');
        $query->execute([
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole(),
            ':created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
        $user->setId($this->db->lastInsertId());
        
    }
    
    public function findById(int $id): ?User
    {
        $query =  $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $query->execute([':id' => $id]);
        $userData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($userData)
        {
            $user = new User($userData['username'], $userData['email'], $userData['password'], $userData['role'], new DateTime($userData['created_at']));
            $user->setId($userData['id']);
            return $user;
        }
        return null;
    }
}