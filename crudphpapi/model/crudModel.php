<?php

interface CrudInterface{
    public function getAll();
    public function getOne();
    public function insert();
    public function update();
    public function delete();
}

class Crud_model{

    protected $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        $sql = "SELECT * FROM users";
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute()){
                $data =  $stmt->fetchAll();
                if ($stmt->rowCount() > 0){
                    return $data;
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    } 

    public function getOne($data){
        $sql = "SELECT * FROM users WHERE User_ID = ?";
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->User_ID])){
                $data =  $stmt->fetchAll();
                if ($stmt->rowCount() > 0){
                    return $data;
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function insert($data){
        $sql = 'INSERT INTO users(FirstName, LastName, is_Admin) VALUES(?, ?, Default)';

        if (!isset($data->FirstName) || !isset($data->LastName)) {
            return "Error: FirstName and LastName are required fields.";
        }

        if (empty($data->FirstName) || empty($data->LastName)) {
            return "Error: FirstName and LastName cannot be empty.";
        }

        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->FirstName, $data->LastName])){
                return 'Data Successfully Inserted';
            }else{
                return 'Data Unsuccessfully Inserted';
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function update($data){
        $sql = "UPDATE users SET is_Admin = CASE WHEN is_Admin = 0 THEN 1 WHEN is_Admin = 1 THEN 0 END WHERE User_ID = ?";

        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->User_ID])) {
                return "Data Successfully Updated";
            } else {
                return "Data Unsuccessfully Updated";
            }
        } catch (PDOException $e) {
            return $e->getMessage();  
        }
    } 

    public function delete($data){
        $sql = "DELETE FROM users WHERE User_ID = ?";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->User_ID])) {
                return "User successfully deleted.";
            } else {
                return "Failed to delete user.";
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}