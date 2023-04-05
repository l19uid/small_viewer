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
                $keys = array_map(function($item) { return $item['room']; }, Key::findByEmployeeId($employee_id));
                foreach ($this->roomKeys as $roomKey)
                {
                    $roomKey->checked = in_array($roomKey->room_id, $keys);
                }
                $this->rooms = Room::all();
                $this->employee = Employee::findByID($employee_id);
                if (!$this->employee)
                    throw new NotFoundException();

                $this->errors = [];
                break;

            case self::STATE_DATA_SENT:
                //načíst data
                $this->employee = Employee::readPost();
                $roomKeys = filter_input(INPUT_POST, 'keys', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                foreach ($roomKeys as $k) {
                    if (!Room::findByID($k))
                        throw new BadRequestException();
                }

                //zkontrolovat data
                $this->errors = [];
                if ($this->employee->validate($this->errors))
                {
                    //zpracovat
                    if($this->employee->password != "")
                        $this->employee->password = hash("sha256",$this->employee->password);
                    $result = $this->employee->update();

                    foreach(Key::all() as $key) {
                        if ($key->employee != $this->employee->employee_id)
                            continue;

                        $key->delete();
                    }

                    foreach($roomKeys as $roomKey) {
                        $key = new Key();
                        $key->employee = $this->employee->employee_id;
                        $key->room = $roomKey;
                        $key->insert();
                    }

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
        if($_SESSION["employee"]->admin || $_SESSION["employee"]->id == $this->employee->employee_id)
        {
            return MustacheProvider::get()->render("employee_form",
                [
                    'employee' => $this->employee,
                    'errors' => $this->errors,
                    'keys' => $this->roomKeys,
                    'rooms' => $this->rooms,
                    'admin' => $_SESSION["employee"]->admin
                ]);
        }
        else
        {
            $data['message'] = 'NEDOSTATEČNÁ PRÁVA';
            $data['alertType'] = 'danger';
            return MustacheProvider::get()->render("alert", $data);
        }

        //vyrenderuju
    }

    protected function getState() : int
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            return self::STATE_DATA_SENT;

        return self::STATE_FORM_REQUEST;
    }
}

$page = new EmployeeInsertPage();
$page->render();