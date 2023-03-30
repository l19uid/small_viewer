<?php
class Employee
{
    public ?int $employee_id;
    public ?string $name;
    public ?string $surname;
    public ?string $job;
    public ?int $wage;
    public ?int $room;
    public ?string $password;
    public ?string $username;
    public ?bool $admin;

    private static string $table = 'employee';

    public function __construct(array $rawData = [])
    {
        $this->hydrate($rawData);
    }

    /**
     * @param $sort
     * @return Employee[]
     */
    public static function all(array $sort = []) : array
    {
        $pdo = PDOProvider::get();

        $query = "SELECT * FROM `" . self::$table . "` " . self::sortSQL($sort);
        $stmt = $pdo->query($query);

        $result = [];
        while($employee = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = new Employee($employee);

        return $result;
    }

//    public static function find($conditions, $sort){
//    }

    public static function findByID(int $id) : Employee|null
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE `employee_id` = $id";
        $stmt = $pdo->query($query);

        if ($stmt->rowCount() < 1)
            return null;

        return new Employee($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * @param array $rawData
     * @return void
     */
    private function hydrate(array $rawData): void
    {
        if (array_key_exists('employee_id', $rawData)) {
            $this->employee_id = $rawData['employee_id'];
        }
        if (array_key_exists('name', $rawData)) {
            $this->name = $rawData['name'];
        }
        if (array_key_exists('surname', $rawData)) {
            $this->surname = $rawData['surname'];
        }
        if (array_key_exists('job', $rawData)) {
            $this->job = $rawData['job'];
        }
        if (array_key_exists('wage', $rawData)) {
            $this->wage = $rawData['wage'];
        }
        if (array_key_exists('password', $rawData)) {
            $this->password = $rawData['password'];
        }
        if (array_key_exists('username', $rawData)) {
            $this->username = $rawData['username'];
        }
    }

    private static function sortSQL(array $sort) : string
    {
        if (!$sort)
            return "";

        $sqlChunks = [];
        foreach ($sort as $column => $direction){
            $sqlChunks[] = "`$column` $direction";
        }
        return "ORDER BY " . implode(" ", $sqlChunks);
    }

    public static function readPost() : Employee
    {
        $employee = new Employee();

        $employee->employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
        $employee->name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $employee->surname = filter_input(INPUT_POST, 'surname', FILTER_DEFAULT);
        $employee->job = filter_input(INPUT_POST, 'job', FILTER_DEFAULT);
        $employee->wage = filter_input(INPUT_POST, 'wage', FILTER_VALIDATE_INT);
        $employee->room = filter_input(INPUT_POST, 'room', FILTER_VALIDATE_INT);
        $employee->password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $employee->username = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);

        return $employee;
    }

    public function validate(array &$errors = []) : bool
    {
        if (is_string($this->name))
            $this->name = trim($this->name);
        if (!$this->name)
            $errors['name'] = "Jméno nemůže být prázdné";

        if (is_string($this->surname))
            $this->surname = trim($this->surname);
        if (!$this->surname)
            $errors['surname'] = "Jméno nemůže být prázdné";

        if (is_string($this->job))
            $this->job = trim($this->job);
        if (!$this->job)
            $errors['job'] = "Práce nemůže být prázdná";

        return count($errors) === 0;
    }

    public function insert() : bool
    {
        $query = "INSERT INTO `".self::$table."` (`name`, `surname`, `job`, `wage`, `room`, `username`, `password`) VALUES (:name, :surname, :job, :wage, :room, :username, :password);";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'name' => $this->name,
            'surname' => $this->surname,
            'job' => $this->job,
            'wage' => $this->wage,
            'room' => $this->room,
            'username' => $this->username,
            'password' => $this->password
        ]);

    }

    public function update() : bool
    {
        $query = "UPDATE `".self::$table."` SET `name` = :name,`surname` = :surname, `job` = :job, `wage` = :wage, `room` = :room, `username` = :username, `password` = :password WHERE `employee_id`=:employee_id;";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'surname' => $this->surname,
            'job' => $this->job,
            'wage' => $this->wage,
            'room' => $this->room,
            'username' => $this->username,
            'password' => $this->password
        ]);

    }



    public static function deleteById(int $employee_id) : bool
    {
        $query = "DELETE FROM `".self::$table."` WHERE `employee_id` = :employee_id";

        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employee_id' => $employee_id,
        ]);
    }

    public function delete() : bool
    {
        return static::deleteById($this->employee_id);
    }
}