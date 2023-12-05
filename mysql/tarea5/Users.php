<?php

class Users
{
    private $db;
    private $id;
    private $usuario;
    private $pwd;
    private $email;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function validarUsuario($usuario, $contrasena)
    {
        // Verificar si el usuario y la contraseña son válidos
        $stmt = $this->db->prepare("SELECT pwd FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $hash = $stmt->fetchColumn(); // Utilizar fetchColumn directamente
            return password_verify($contrasena, $hash);
        }

        return false;
    }

    public function altaUsuario($usuario, $contrasena, $email)
    {
        // Dar de alta un nuevo usuario
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, pwd, email) VALUES (:usuario, :contrasena, :email)");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    public function modificarUsuario($usuarioId, $nuevosDatos)
    {
        // Modificar los datos de un usuario
        $columnas = array_keys($nuevosDatos);
        $valores = array_values($nuevosDatos);

        // Hash de la nueva contraseña si se proporciona
        if (isset($nuevosDatos['pwd'])) {
            $nuevosDatos['pwd'] = password_hash($nuevosDatos['pwd'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE usuarios SET ";
        foreach ($columnas as $columna) {
            $sql .= "$columna = ?, ";
        }
        $sql = rtrim($sql, ', '); // Eliminar la última coma
        $sql .= " WHERE id = ?"; // Cambiado de 'usuario' a 'id'

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($valores, [$usuarioId]));
    }

    public function eliminarUsuario($usuarioId)
    {
        // Eliminar un usuario
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
    }

    public function obtenerTodosLosUsuarios()
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($usuarioId)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    public function getPwd()
    {
        return $this->pwd;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
