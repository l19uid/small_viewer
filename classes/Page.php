<?php
session_start();

abstract class Page
{
    public string $title;

    protected function prepareData() : void {

    }

    protected function HTTPHeaders() : void {

    }

    protected function HTMLHead() : string {
        return MustacheProvider::get()->render("html_head", ["title" => $this->title]);
    }

    protected function pageHeader() : string {

        $isLogged = $_SESSION["employee"] ?? null;
        return MustacheProvider::get()->render("page_header", ["isLogged" => $isLogged]);
    }

    protected function pageBody() : string{
        return "";
    }

    public function render() : void {
        try {
            $this->prepareData();

            //pošle http hlavičky
            $this->HTTPHeaders();

            $pageData = [];

            $pageData["htmlHead"] = $this->HTMLHead();

            if (isset($_SESSION["employee"]))
            {
                $pageData["pageHeader"] = $this->pageHeader();
                $pageData["pageBody"] = $this->pageBody();
            }
            else
            {
                $loginError = $_SESSION["loginError"] ?? null;
                $pageData["pageBody"] = MustacheProvider::get()->render("login", ["loginError" => $loginError]);
            }

            //předá šabloně stránky data pro vykreslení
            echo MustacheProvider::get()->render("page", $pageData);
        }

        catch (BaseException $e) {
            $exceptionPage = new ExceptionPage($e);
            $exceptionPage->render();
            exit;
        }

        catch (Exception $e) {
            if (AppConfig::get('debug'))
                throw $e;

            $e = new BaseException();
            $exceptionPage = new ExceptionPage($e);
            $exceptionPage->render();
            exit;
        }
    }
}