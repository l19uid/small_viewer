<?php

class AuthenticatedPage extends Page
{
    protected ?Employee $user;

    protected function prepareData(): void
    {
        parent::prepareData();

        if (!isset($_SESSION["employee"]))
        {
            throw new ForbiddenException();
        }

        $emp = $_SESSION['employee'];
        $this->user = Employee::findLoginHash($emp->username, $emp->password);
        if (!$this->user)
        {
            throw new ForbiddenException();
        }
    }
}