<?php
class Key
{
    public ?int $key_id;
    public ?int $employee;
    public ?int $room;

    private static string $table = 'key';

    public function __construct(array $rawData = [])
    {
        $this->hydrate($rawData);
    }


    /**
     * @param $sort
     * @return Key[]
     */
    public static function all(array $sort = []) : array
    {
        $pdo = PDOProvider::get();

        $query = "SELECT * FROM `" . self::$table . "` " . self::sortSQL($sort);
        $stmt = $pdo->query($query);

        $result = [];
        while($key = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = new Key($key);

        return $result;
    }

//    public static function find($conditions, $sort){
//    }

    public static function findByEmployeeId(int $id) : array
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE `employee` = $id";
        $stmt = $pdo->query($query);

        if ($stmt->rowCount() < 1)
            return [];

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByRoomId(int $id) : array
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE `room` = $id";
        $stmt = $pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $rawData
     * @return void
     */
    private function hydrate(array $rawData): void
    {
        if (array_key_exists('key_id', $rawData)) {
            $this->key_id = $rawData['key_id'];
        }
        if (array_key_exists('room', $rawData)) {
            $this->room = $rawData['room'];
        }
        if (array_key_exists('employee', $rawData)) {
            $this->employee = $rawData['employee'];
        }
    }

    public static function getRoomNameById(int $id) : string
    {
        $pdo = PDOProvider::get();
        $query = "SELECT `name` FROM `room` WHERE `room_id` = $id";
        $stmt = $pdo->query($query);

        return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
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

    public static function readPost() : Key
    {
        $key = new Key();

        $key->key_id = filter_input(INPUT_POST, 'key_id', FILTER_VALIDATE_INT);
        $key->room = filter_input(INPUT_POST, 'room', FILTER_DEFAULT);
        $key->employee = filter_input(INPUT_POST, 'employee', FILTER_DEFAULT);

        return $key;
    }

    public function insert() : bool
    {
        $query = "INSERT INTO `".self::$table."` (`employee`, `room`) VALUES (:employee, :room);";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employee' => $this->employee,
            'room' => $this->room
        ]);
    }

    public static function deleteById(int $key_id) : bool
    {
        $query = "DELETE FROM `".self::$table."` WHERE `key_id` = :key_id";

        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'key_id' => $key_id,
        ]);
    }

    public function delete() : bool
    {
        return static::deleteById($this->key_id);
    }

}