<?php

class Gateway {

private $db = null;

public function __construct($db)
{
    $this->db = $db;
}

public function findGalleriesByUsername($user)
{
    $statement = "
        SELECT 
            gallery_name
        FROM
            users_galleries
        WHERE user = ?
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }



    // $statement = "
    //     SELECT 
    //         id
    //     FROM
    //         photos
    //     WHERE path = ?;
    // ";

    // try {
    //     $statement = $this->db->prepare($statement);
    //     $statement->execute(array($path));
    //     $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    //     return $result;
    // } catch (\PDOException $e) {
    //     exit($e->getMessage());
    // }
}

//working
public function findNamesOfAllGalleries()
{
    $statement = "
        SELECT 
            name
        FROM
            galleries;
    ";

    try {
        $statement = $this->db->query($statement);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }
}


public function findPhotoByGallery($name)
{
    $statement = "
        SELECT 
            photo_id
        FROM
            photos_galleries
        WHERE gallery_name = ?;
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($name));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }
}

//working
public function findPhotoByPath($path)
{
    $statement = "
        SELECT 
            id
        FROM
            photos
        WHERE path = ?;
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($path));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
}


public function findPhotoById($id)
{
    $statement = "
        SELECT 
            description, id, path
        FROM
            photos
        WHERE id = ?;
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($id));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
}

//working
public function insertPhoto($description, $galleryName)
{

    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if($fileSize < 10000000){
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $shaHash = hash('sha256', $fileNameNew);
                $firstTwoCharactersHash = substr($shaHash, 0, 2);
                $secondTwoCharactersHash = substr($shaHash, 2, 2);
                $thirdTwoCharactersHash = substr($shaHash, 4, 2);
                $forthTwoCharactersHash = substr($shaHash, 6, 2);


                $filePathDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash;

                $fileDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash.'/'.$fileNameNew;
                mkdir($filePathDestination, 0777, true);

                move_uploaded_file($fileTmpName, $fileDestination);
                header("Location: grid-gallery.php?uploadsuccess");
            } else {
                echo "Your file is too big!";
            }

        }
        else {}
    }
    else {
        echo "You cannot upload files of this type!";
    }
    $statement = "
        INSERT INTO photos 
            (description, path)
        VALUES
            (:description, :path);
    ";

    $statement2 = "
        INSERT INTO photos_galleries 
            (photo_id, gallery_name)
        VALUES
            (:photo_id, :gallery_name);
    ";


    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'description' => $description,
            'path'  => $fileDestination,
        ));
        $result = $this->findPhotoByPath($fileDestination);
        $id = $result[0]['id'];

        $statement2 = $this->db->prepare($statement2);
        $statement2->execute(array(
            'photo_id' => $id,
            'gallery_name'  => $galleryName,
        ));
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }
}


public function createGallery(Array $input)
{
    $statement = "
        INSERT INTO galleries 
            (name, owner)
        VALUES
            (:name, :owner);
    ";

    $statement2 = "
        INSERT INTO users_galleries 
            (user, gallery_name)
        VALUES
            (:user, :gallery_name);
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'name' => $input['name'],
            'owner' => $input['owner']
        ));


        $statement2 = $this->db->prepare($statement);
        $statement2->execute(array(
            'gallery_name' => $input['name'],
            'user' => $input['user']
        ));
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
}


// public function updateGallery($name, Array $input)
// {
//     $statement = "
//        INSERT INTO photo_galleries 
//             (photo_id, gallery_name)
//         VALUES
//             (:id, :name);
//     ";

//     try {
//         $statement = $this->db->prepare($statement);
//         $statement->execute(array(
//             'name' =>  $name,
//             'id' => $input['id']
//         ));
//         return $statement->rowCount();
//     } catch (\PDOException $e) {
//         exit($e->getMessage());
//     }    
// }

public function deletePhotoById($id)
{
    $statement = "
        DELETE FROM photos
        WHERE id = :id;
    ";

    $statement2 = "
        DELETE FROM photos_galleries
        WHERE photo_id = :id;
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('id' => $id));

        $statement2 = $this->db->prepare($statement);
        $statement2->execute(array('id' => $id));
        return $statement2->rowCount();
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
}

public function deleteGalleryByName($name)
{

    $result = $this->findPhotoByGallery($name);

    foreach ($result as $value) {
        $this->deletePhotoById($value);
    }

    $statement = "
        DELETE FROM galleries
        WHERE name = :name;
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('name' => $name));
        return $statement->rowCount();
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
}

public function getUserByNameAndPassword($name, $pasword) {
    $statement = "
        SELECT fn 
        FROM users
        WHERE username=:username AND password=:password;
    ";
    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('username' => $name, 'password' => $pasword));
        return $statement->rowCount();
    } catch (\PDOException $e) {
        exit($e->getMessage());
    } 
}
}