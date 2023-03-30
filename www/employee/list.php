<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeListPage extends CRUDPage
{
    public string $title = "Seznam místností";

    protected function pageBody(): string
    {
        $html = $this->alert();

        //získám data o místnostech
        $employees = Employee::all();

        $html .= MustacheProvider::get()->render("employee_list", ["employees" => $employees]);

        //vyrenderuju

        return $html;
    }

    private function alert() : string
    {
        $action = filter_input(INPUT_GET, 'action');
        if (!$action)
            return "";

        $success = filter_input(INPUT_GET, 'success', FILTER_VALIDATE_INT);
        $data = [];

        switch ($action)
        {
            case self::ACTION_INSERT:
                if ($success === 1)
                {
                    $data['message'] = 'Pacovník byl založen';
                    $data['alertType'] = 'success';
                }
                else
                {
                    $data['message'] = 'Chyba při založení praconíka';
                    $data['alertType'] = 'danger';
                }
                break;

            case self::ACTION_DELETE:
                if ($success === 1)
                {
                    $data['message'] = 'Pracovníi byl smazán';
                    $data['alertType'] = 'success';
                }
                else
                {
                    $data['message'] = 'Chyba při mazání pracovnímka';
                    $data['alertType'] = 'danger';
                }
                break;
        }

        return MustacheProvider::get()->render("alert", $data);
    }
}

$page = new EmployeeListPage();
$page->render();