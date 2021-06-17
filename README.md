# Описание на програмния код

Бизнес логиката, която е на езика php е разделена на два класа, които са: Gateway и Controller. Също така има и логика в index.php.  Index.php e така имплементиран, че да се държи като REST Client, тоест нашия сървър играе ролята на REST Client и може да приема заявки, които да ги обработва. Заявките, които приема са две: 

1. за създаване на галерия 
2. за вмъкване на снимка в галерия

Като за заявките сървъра слуша на URI-a му и в зависимост от заявката, която е получил той я обработва. Като има проверка за валидност на заявката и при невалидна заявка връща код 400 Bad Request. Имаме и файл, който отговаря за това да установи кънекция с базата дани навия сървър. Този файл е: dbconfig.php. Нека да разгледаме този файл:

```php
<?php
        $conn = null;
        $host = "localhost";
        $port = "3306";
        $db   = "viewitdb";
        $user = "root";
        $pass = "";

        try {
            $conn = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
?>

```

В този файл може да видим, че имаме няколко параметъра, с които установяваме POD конекция до базата данни. Този файл ще го използваме в другите файлове, в които ни трябва кънекция с базата данни, като го добавяме с ключовата дума require:

```php
require 'dbconfig.php';
```

Сега ще разгледаме другите основни класове, които използваме в бизнес логиката.

## Gateway.php

В този файл се намира цялата логика на нашето приложение, като в него са всички функции както тези, които нашия фронт енд използва, така и тези които се изпълняват, когато сървърът ни получи заявка. Важна подробност е, че функциите са така написани, че да могат да се използват както от фронт енда на нашия проект, така и когато сървърът получи REST заявка, тоест нямаме различни функции за една и съща функционалност.

Сега нека да разгледаме функциите:

### Конструктор

За да го създадем в конструктура му подаваме параметър, който ще е кънекцията към базата данни, тъй като този клас ще праща заявки към базата данни:

```php
private $db = null;

public function __construct($db)
{
    $this->db = $db;
}
```



### findGalleriesByUsername($user)

Тази функция ни предотавя възможността да вземем всички галерии, до които потребителя има достъп. 

```php
public function findGalleriesByUsername($user)
{
    $statement = "
        SELECT 
            gallery_name
        FROM
            users_galleries
        WHERE username = ?
    ";

    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }

}
```

Както се вижда от кода, първо си подготвяме стейтмънт, който ще използваме, за да вземем данни от базата данни, който в себе си използва параметри. След това го правим готов за изпълнение и след това го изпълняваме и връщаме резултата от нашата заявка към базата данни. Тази заявка взима имената на всички галерии, които имат за стойност в колоната потребителско име, което е равно на потребителското име, което е подадено като параметър.

### findPhotoByPath($path)

Тази функция ни служи, за да можем да намерим снимка по даден път. Подготвяме стейтмънта и след това го изпълняваме. Като връщаме като резултат, снимката, която сме намерили:

```php

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

```



### findPhotoByGallery($name)

Тази функция ни предоставя възможност да вземем id-тата на снимките, които са в определена галерия.

```php
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
```

Както се вижда от кода, първо си подготвяме стейтмънт, който ще използваме, за да вземем данни от базата данни, който в себе си използва параметри. След това го правим готов за изпълнение и след това го изпълняваме и връщаме резултата от нашата заявка към базата данни. Тази заявка взима id-тата на всички снимки, които имат за стойност в колоната за име на галерията, което е равно на името на галерия, което е подадено като параметър.

### findPhotoById($id)

Тази функция ни предоставя възможността да вземем снимка, която е с конкретно id.

```php
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
```

Както се вижда от кода, първо си подготвяме стейтмънт, който ще използваме, за да вземем данни от базата данни, който в себе си използва параметри. След това го правим готов за изпълнение и след това го изпълняваме и връщаме резултата от нашата заявка към базата данни. Тази заявка взима description-a, id-то и path-a на конкретна снимка, чието id е равно на id-то, което е подадено като параметър на функцията.

### insertPhoto($description, $galleryName)

Тази функция я използваме, за да можем да инсъртнем фота в някоя галерия, която ни е зададе чрез името и, което е параметър на функцията - $galleryName. Първото нещо, което прави функцията е да вземе малко информация за файла, който сме качили и да проверим дали отговаря на някои условия за качване:

```php
$file = $_FILES['image'];

    $fileName = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    $fileError = $_FILES['image']['error'];
    $fileType = $_FILES['image']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if($fileSize < 10000000){
```

След това следва и най-важната част от функцията, а именно да вземем уникално име за съответната снимка:

```php
$fileNameNew = uniqid('', true).".".$fileActualExt;
```

като използваме вградената функция uniqid, която в имплементацията си всъщност използва времето, като нещо уникално. След това функцията взима стойността на функцията sha256 на това уникално име и разделя първите 8 символа на 4 двойки:

```php
$shaHash = hash('sha256', $fileNameNew);
$firstTwoCharactersHash = substr($shaHash, 0, 2);
$secondTwoCharactersHash = substr($shaHash, 2, 2);
$thirdTwoCharactersHash = substr($shaHash, 4, 2);
$forthTwoCharactersHash = substr($shaHash, 6, 2);
```

Като ще използваме всяка една от двойките за име на нова директория и ще нстнем директориите, като в последната директория ще запазим файла:

```php
$filePathDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash;

                $fileDestination = 'uploads/'.$firstTwoCharactersHash.'/'.$secondTwoCharactersHash.'/'.$thirdTwoCharactersHash.'/'.$forthTwoCharactersHash.'/'.$fileNameNew;
                mkdir($filePathDestination, 0777, true);

                move_uploaded_file($fileTmpName, $fileDestination);
```

След това ще подготвим два стейтмънта, с които да вкараме нужната информация за новата снимка в базата данни:

```php
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
```

След това подготвяме тези стейтмънти за изпълнение и след това ги изпълняваме със съответните параметри:

```php
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
```

Като както се вижда с тези стейтмънти ние вкарваме новата снимка в базата данни със снимки и също така обозначаваме в коя галерия е с втория стейтмънт.

### createGallery()

Тази функция я използваме, за да създадем нова галерия от json файл, който сме получили от потребителя през ui-a или чрез заявка към сървъра.

Първо подготвяме стейтмънти, с които ще можем да променим базата данни по нужния начин, за да създадем новата галерия със съответния автор, име и потребители, които имат възможност да я достъпват:

```php
$statement = "
        INSERT INTO galleries 
            (name, owner)
        VALUES
            (:name, :owner);
    ";

    $statement2 = "
        INSERT INTO users_galleries 
            (username, gallery_name)
        VALUES
            (:username, :gallery_name);
    ";
```

След това извършваме манипулации върху информацията за файла и го записваме на сървъра, както и взимаме json съдържанието, за да го декодираме и да вземем нужната информация:

```php
$fileName = $_FILES['gallery']['name'];
    $fileTmpName = $_FILES['gallery']['tmp_name'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $fileNameNew = uniqid('', true);

    $fileDestination = 'JSONFiles/'.$fileNameNew.".".$fileActualExt;

    $allowed = array('json');

    if (in_array($fileActualExt, $allowed)) {
        move_uploaded_file($fileTmpName, $fileDestination);
    } else {
        echo "You cannot upload files of this type!";
        exit();
    }

    $str = file_get_contents($fileDestination);
    $json = json_decode($str, true);
```

След това запазваме в базата данни галерията с нейният собственик и след това вече запазваме и информация за това кои потребители имат достъп до нея:

```php
try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array(
            'name' => $json['galleryName'],
            'owner' => $json['owner']
        ));

        foreach ($json['usernames'] as $name) { 
            $statement3 = $this->db->prepare($statement2);
            $statement3->execute(array(
                'gallery_name' => $json['galleryName'],
                'username' => $name
            ));
        }
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }  
```

### deletePhotoById($id)

Тази функция се използва, за да изтрием снимка от нашата система. Първо подготвяме стейтмънтите, с които ще изтрием снимката от нашата система:

```php
$statement = "
        DELETE FROM photos
        WHERE id = :id;
    ";

    $statement2 = "
        DELETE FROM photos_galleries
        WHERE photo_id = :id;
    ";
```

След това изтриваме снимката от базата данни и я изтриваме от сървъра:

```php
try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('id' => $id));

        $statement2 = $this->db->prepare($statement2);
        $statement2->execute(array('id' => $id));

        unlink($this->findPhotoById($id)[0]['path']);
        return $statement2->rowCount();
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }    
```

### deleteGalleryByName($name)

Тази функция използваме, когато искаме да изтрием цяла галерия.

Първо изтриваме всички снимки, които са в галерията, като името на галерията, която ще трием ни се подава като параметър:

```php
$result = $this->findPhotoByGallery($name);

    foreach ($result as $value) {
        $this->deletePhotoById($value['photo_id']);
    }
```

След това подготвяме и изпълняваме стейтмънт, с който да изтрием галерията от базата ни с данни:

```php
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
```

### getUserByNameAndPassword($name, $pasword)

Тази функция я използваме дали потребителите са въвели верни данни за влизане в системата.

```php
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
```

Имаме стейтмънт, който подготвяме и изпълняваме, за да видим дали съществува потребител с това потребителскко име и парола.

### getOwnerOfGallery($name)

Тази функция ни дава възможността да намерим кой е собственикът на галерия по зададено име като параметър. В тази функция имаме стейтмънт, който подготвяме и изпълняваме, за да вземем от базата данни кой е собственикът на галерията със съответното име:

```php
    $statement = "
        SELECT owner 
        FROM galleries
        WHERE name = :name
    ";
    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('name' => $name));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    } 
```



## Controller

Този клас е с цел да мине през него REST заявката, която е получил сървъра.

В него имам включени тези два файла:

```php
include 'Gateway.php';
require 'dbconfig.php';
```

Той съдърж в себе си инстанция на Gateway и кънекция към базата данни:

```php
private $db;
    private $gateway;


    public function __construct($db)
    {
        $this->db = $db;
        $this->gateway = new Gateway($db);
    }
```

И също така имаме функция, която да обработва заявката, която е получил сървъра:

```php
public function processPostRequest($description, $galleryName) {
        if ($galleryName && $description) {
            $this->gateway->insertPhoto($description, $galleryName);   
        } else {
            $this->gateway->createGallery();
        }
    }
```

В тази функция се прави обработката на заявката по тов дали са зададени двата параметъра.

## Index.php

В този глас се получава REST заявката към сървъра и след това тя се предава към Controller-a.

```php
include 'Controller.php';
require 'dbconfig.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$description = null;
$galleryName = null;

if (isset($_POST['description'])) {
    $description = $_POST['description'];
}
if (isset($_POST['gallery'])) {
    $galleryName = $_POST['gallery'];
}

if (!isset($_POST['description']) || !isset($_POST['gallery']) || !isset($_FILES['image'])) {
    if (!isset($_FILES['gallery'])) {
        header("HTTP/1.1 400 Bad Request");
        exit();
    }
}

$controller = new Controller($conn);
$controller->processPostRequest($description, $galleryName);
```

Имаме обработка на заявката, в която проверяме какви параметри са ни подадени по заявката, като съответно имаме и проверка за грешно подадени данни. Като за заявка за създаване на снимка трябва да има файл с ключ image и полета с ключове съответно description и gallery, а за създаването на галерия трябва да има само един файл, който е с ключ gallery.

# Архитектура на базата данни:

Ще разгледаме таблиците, които имаме в базата данни на нашата система:

## users

В тази таблица пазим информация за потребителите на нашата система.

## galleries

В тази таблица пазим информация за галериите в нашата система.

## photos

В тази таблица пазим информация за снимките в нашата система.

## users_galleries

 В тази таблица пазим информация за това кой потребител до коя галерия има достъп, като имаме колони:

- username - потребителското име на потребителя
- gallery_name - името на галерията, до която потребителя има достъп

## photos_galleries

- photo_id - идентификационния номер на снимка
- gallery_name- името на галерията, в която е тази снимка

# Архитектура на приложението

Приложението е изградено от 3-слойна архитектура, тя се състои от фрон енд част, която е написана на  HTML5, CSS3 и Vanilla Java script, база данни, която е MySQL и бизнес логика и сървър, които са реализирани на PHP. Потребителя има достъп до нашата система, чрез фронт енд-а(UI-а) на нашата ситема, който работи като използва бизнес логикатата, а тя от своя страна се свързва с базата данни. Също така сървърът ни може да получава и REST заявки, които да обработва и прави съответните промени както на самия него, така и в базата данни.  



# Описание на системата

Нашата система е онлайн галерия, в която потребителите могат да:

- Влязат в система чрез специален панел за влизане, в който ако потребителят не въведе правилни данни за влизане в система, тя ще му съобщи.

- Видят всички галерии, до които има достъп.

- Разгледат снимките в галерия, до която има достъп.

- Могат да трият снимка от галерия, на която са собственици.

- Могат да трият снимка от галерия, на която са собственици.

- Качват снимки през UI-a или чрез REST заявка, които се запазват на сървъра, но системата ни използва специален алгоритъм, с който да разпределя снимките в отделни директории, като така приложението има възможност да работи с голям брой снимки без да се забавя. REST заявката трябва да съдържа файл с ключ image, описание с ключ description и име на галерия с ключ gallery. При качване от UI-a потребителите могат да drag and drop-нат снимка и след това ще видят thumnail на нея.

  - Алгоритъмът за запазване на снимки е следният: снимката се получава от нея създаваме уникален идентификационен стринг, на който прилагаме хеширане с функцията sha256, след това взимаме първите 8 символа на хешираната стойност и ги разделяме на двойки. От всяка двойка правим директория, като всяка директория се съдържа в предишната и в последната директория запазваме снимките. Например:
    - Нека хешираната стойност е 1234567890... , системата ни ще направи директория 12, в нея ще направи директория 34, в нея ще направи директория 56, в нея ще направи директория 78 и в тази директория ще запази снимката. Благодарение на този алгоритъм си усигоряваме горе-долу равномерно разпределение на снимките дори и при голям брой, като така системата не се затруднява като зарежда снимките. 

- Създват галерия през UI-a или чрез REST заявка, като за да създате галерия потребителя трябва да качи конфигурационен файл във формата json, в който да са описани кой е собственикът на галерията, името на галерията и кои потребители имат достъп до тази галерия, като ако е чрез рест заявка, тя трябва да съдържа json файл с ключ gallery. При качването на на json файл от  UI-a потребителите могат да drag and drop-нат конфигурационния файл на галерията.

  - Примерен json файл:

    - ```json
      {
          "owner": "gkarnaudov",
          "galleryName": "Georgi's gallery",
          "usernames": [
              "gkarnaudov",
            	"mkdzhambaz"
          ]
      }
      ```

      С този файл ще създадем галерия с име "Georgi's gallery", собственик "gkarnaudov" и достъп до тази галерия ще имат потребителите с потребителски имена: "gkarnaudov" и "mkdzhambaz".

  # Какво научих

  ## Манол

  Тъй като се занимавах главно с бизнес логиката и имплементирането на REST Client-a се научих много добре да работя с езика PHP и да създавам REST Client от нулата, като това според мен е много ценно, тъй като благодарение на този клиент можем да направим, така че нашата система да се интегрира с външни системи, като предоставим функционалностите на нашата система да бъдат достъпни чрез заявки изпращани към клиента. Също така работих и върху базата данни и фронт енд частта на приложението, като по време на тази работа научих някои неща свързани с HTML, CSS и PHP.

  

  ## Бъдещо развитие

  Системата може да бъде интегрирана със системата на СУСИ и когато се създава профил във СУСИ да се създава профил в нашата система със същите креденшъли, като за целта нашия REST Client ще има възможност да му се праща заявка, с която да създава профил в системата. Също така системата може да бъде интегрирана с друга система, така че тази друга система да изпраща автоматично заявка за създаване на галерия със съоттветния конфигурационен файл и след това да изпраща заявки за вмъкване на снимки в галерията. Като това дори сега е възможно тъй като нашия REST Client предоставя тази функционалност.

  

  # Конфигуриране на приложението

  