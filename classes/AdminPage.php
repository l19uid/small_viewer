<?php

class AdminPage extends AuthenticatedPage {
    protected function prepareData(): void
    {
        parent::prepareData();

        if (!$this->user->admin)
        {
            throw new ForbiddenException();
        }
    }
}