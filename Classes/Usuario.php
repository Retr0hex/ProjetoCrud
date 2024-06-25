<?php
class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db){
        $this->conn = $db;
    }

    public function criar($nome, $sexo, $fone, $email, $senha, $isAdmin = 0){
        $query = "INSERT INTO " . $this->table_name . " (nome, sexo, fone, email, senha, admin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
        $stmt->execute([$nome, $sexo, $fone, $email, $hashed_password, $isAdmin]);
        return $stmt; 
    }

    public function atualizar($id, $nome, $sexo, $email, $fone){
        $query = "UPDATE " . $this->table_name . " SET nome = ?, sexo = ?, fone = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $sexo, $fone, $email, $id]);
        return $stmt;
    }

    public function deletar($id){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Retorna o número de linhas afetadas (deve ser 1 se deletou com sucesso)
    }

    public function ler(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function lerPorId($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($usuario_id) {
        $query = "SELECT admin FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$usuario_id]);
        $admin_status = $stmt->fetchColumn();
        return $admin_status == 1; // Retorna true se for admin, false caso contrário
    }

    public function login($email, $senha){
        $query = "SELECT * FROM  " . $this->table_name .  " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha'])){
            return $usuario;
        }
        return false;
    }

    // Métodos adicionais conforme necessário...
}
?>
