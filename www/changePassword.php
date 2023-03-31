<?php
require_once "../bootstrap/bootstrap.php";

class ChangePasswordPage extends CRUDPage
{
    public string $title = "Změnit heslo";
    protected int $state;

    protected string $oldPassword;
    protected string $newPassword;
    protected string $newPasswordConfirm;

    protected string $hash;

    protected Employee $employee;

    protected array $errors = [];

    protected function prepareData(): void
    {
        parent::prepareData();
        $this->state = $this->getState();

        switch ($this->state) {
            case self::STATE_FORM_REQUEST:
                $errors = [];
                break;

            case self::STATE_DATA_SENT:
                $this->getInput();

                $errors = [];
                if ($this->validateInput()) {
                    $result = $this->changePassword();

                    header("Location: ../index.php");
                    exit;
                } else {
                    $this->state = self::STATE_FORM_REQUEST;
                }
                break;
        }
    }


    protected function pageBody(): string
    {
        return MustacheProvider::get()->render("change_password", ['errors' => $this->errors]);
    }

    protected function getState(): int
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            return self::STATE_DATA_SENT;

        return self::STATE_FORM_REQUEST;
    }

    function getInput(): void
    {
        $this->oldPassword = filter_input(INPUT_POST, 'old_password', FILTER_DEFAULT) ?? "";
        $this->newPassword = filter_input(INPUT_POST, 'new_password', FILTER_DEFAULT) ?? "";
        $this->newPasswordConfirm = filter_input(INPUT_POST, 'new_password_again', FILTER_DEFAULT) ?? "";

        $this->hash = hash('sha256', filter_input(INPUT_POST, 'new_password', FILTER_DEFAULT) ?? "");
    }

    function validateInput(): bool
    {
        if ($this->oldPassword !==  $_SESSION["employee"]->password)
            $this->errors["old_password"] = "Staré heslo není správné";

        if ($this->oldPassword === $this->newPassword)
            $this->errors["new_password"] = "Nové heslo musí být jiné než staré heslo";

        if ($this->newPassword !== $this->newPasswordConfirm)
            $this->errors["new_password_again"] = "Nové heslo se neshoduje s kontrolním heslem";

        return count($this->errors) === 0;
    }

    function changePassword(): bool
    {
        $employee = $_SESSION["employee"];
        $employee->password = $this->hash;
        return $employee->update();
    }
}

$page = new ChangePasswordPage();
$page->render();