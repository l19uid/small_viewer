<?php
require_once "../../bootstrap/bootstrap.php";
include "../../models/Key.php";
include "../../models/Room.php";

class EmployeeInsertPage extends CRUDPage
{
    public string $title = "Upravit zaměstnance";
    protected int $state;
    private Employee $employee;
    private array $errors;
    private array $roomKeys = [];
    private array $rooms = [];

    protected function prepareData(): void
    {
        parent::prepareData();
        $this->state = $this->getState();

        switch ($this->state) {
            case self::STATE_FORM_REQUEST:
                $employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);
                if (!$employee_id)
                    throw new BadRequestException();

                $this->roomKeys = Room::all();
                $this->rooms = Room::all();
                $this->employee = Employee::findByID($employee_id);
                if (!$this->employee)
                    throw new NotFoundException();

                $this->errors = [];
                break;

            case self::STATE_DATA_SENT:
                //načíst data
                $this->employee = Employee::readPost();
                //zkontrolovat data
                $this->errors = [];
                if ($this->employee->validate($this->errors))
                {
                    //zpracovat
                    $result = $this->employee->update();
                    //přesměrovat
                    $this->redirect(self::ACTION_UPDATE, $result);
                }
                else
                {
                    //na formulář
                    $this->state = self::STATE_FORM_REQUEST;
                }
                break;
        }
    }


    protected function pageBody(): string
    {
        return MustacheProvider::get()->render("employee_form",
            [
                'employee' => $this->employee,
                'errors' => $this->errors,
                'keys' => $this->roomKeys,
                'rooms' => $this->rooms
            ]);
        //vyrenderuju
    }

    protected function getState() : int
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            return self::STATE_DATA_SENT;

        return self::STATE_FORM_REQUEST;
    }

    protected function getKeys($id) : array
    {
        $keys = Key::findByEmployeeId($id);
        $keys = array_map(function ($key) {
            $key['room_name'] = Key::getRoomNameById($key['room']);
            return $key;
        }, $keys);
        return $keys;
    }

}

$page = new EmployeeInsertPage();
$page->render();