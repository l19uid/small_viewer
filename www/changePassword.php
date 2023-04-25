<?php
require_once "../bootstrap/bootstrap.php";

class ChangePasswordPage extends Page
{
    const STATE_FORM_REQUEST = 0;
    const STATE_DATA_SENT = 1;

    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    protected function redirect(int $action, bool $success) : void
    {
        header("Location: list.php?".http_build_query([
                'success' => $success ? 1 : 0,
                'action' => $action
            ]));
        exit;
    }

    public string $title = "Změnit heslo";
    protected int $state;

    protected string $oldPassword;
    protected string $newPassword;
    protected string $newPasswordConfirm;

    protected string $newHash;
    protected string $oldHash;

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

        $this->oldHash = hash('sha256', filter_input(INPUT_POST, 'old_password', FILTER_DEFAULT) ?? "");
    }

    function validateInput(): bool
    {
        if ($this->oldHash !==  $_SESSION["employee"]->password)
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
        $employee->password = hash('sha256',$this->newPassword);
        return $employee->update();
    }
}

$page = new ChangePasswordPage();
$page->render();