<?php session_start();if (!isset($_SESSION['user_id'])) {header('location:login_signup.php');}?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dc2NSrAXbAkjrdm9IYrX10fQq9SDG6Vjz7nQVKdKcJl3pC+k37e7qJR5MVSCS+wR" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="books2.css">
    <link rel="stylesheet" href="dashboardcopy.css" />
    <script src="index.js" defer></script>
    <link rel="stylesheet" href="rates.css">
    <script src="rates.js" defer></script>
    <script src="search.js" defer></script>
    <title>user interface</title>
</head>

<body>

    <header>
        <a href="controller2.php" class="logo"> <img src="./images/home.png" width="30px"></a>
        <div class="searchzone">
            <form class="box" method="post"
                action="controller2.php?action=search<?php if (isset($_GET['subject'])) {echo "&subject=" . $_GET['subject'];}?>">
                <input class="text" type="text" placeholder="Search For A Book" name="search">
                <a class="btn" href="#">
                    <i class=" fas fa-search"></i>
                </a>
            </form> <span id="searchmiss"></span>


        </div>
        <nav class="navigation">
            <a href="controller2.php#subjects">
                <div class="button-box">
                    <button class="twenty-one"><span>subjects</span></button>
                </div>
            </a>
            <a href="">
                <div class="button-box">
                    <button class="twenty-one">
                        <span>Account</span></button>
                    <div class="accountmenu2">

                        <div class="accountmenuitem"><a href="controller2.php?action=myorders">My Orders</a> </div>
                        <div class="accountmenuitem"><a href="controller2.php?action=myborrows">My Borrows</a> </div>
                        <div class="accountmenuitem"><a href="controller2.php?action=mycomments">My Comments</a> </div>
                        <div class="accountmenuitem"><a href="controller2.php?action=myrates">My Rates</a> </div>

                        <div class="accountmenuitem"><a href="controller2.php?action=passwordchange">Change Your
                                Password</a>
                        </div>
                        <div class="accountmenuitem"><a href="controller2.php?action=logout">logout</a> </div>
                    </div>
                </div>


            </a>

            <a href="controller2.php#contact">
                <div class="button-box">
                    <button class="twenty-one"><span>Contact</span></button>
                </div>
            </a>


        </nav>
    </header>
    <?php
include "model2.php";
include "view2.php";
ini_set("display_errors", "1");
error_reporting(E_ALL);
function testinput($array, $value)
{
    if (isset($array[$value])) {
        return $array[$value];
    }
}
class controller
{
    private $action;
    private $model;
    private $view;
    public function __construct()
    {
        $this->model = new model2();
        $this->action = 'allsubjects';

    }
    /*******allsubjectsaction */
    public function allsubjectsAction()
    {

        $subjects = $this->model->get_allsubjects();

        $this->view = new view_allsubjects();
        $this->view->allsubjects($subjects);
        $this->view->show();
    }
    public function allbooksviewAction()
    {
        $books = $this->model->get_books();

        $this->view = new view_allbooksview();
        $this->view->allbooksview($books);
        $this->view->show();
    }
    public function allbooksAction()
    {
        if (isset($_GET['subject'])) {
            $subject = $_GET['subject'];
        }
        if (isset($_POST['subject'])) {
            $subject = $_POST['subject'];
        }
        $books = $this->model->get_booksbysubject(array($subject));
        $this->view = new view_allbooks();
        $this->view->all($books);
        $this->view->show();
    }
    public function onebookAction()
    {
        if (isset($_GET['ISBN'])) {
            $ISBN = $_GET['ISBN'];
        }

        if (isset($_POST['subject'])) {
            $ISBN = $_POST['ISBN'];
        }
        $book = $this->model->get_bookbyid(array($ISBN));
        $this->view = new view_onebook();
        $this->view->onebook($book);
        $this->view->rate();
        $this->view->comment();
        $this->view->show();

    }
    /*-----------------------borrow book action------------------------*/
    public function borrowbookAction()
    {

        $this->view = new view_borrowbook();
        $this->view->show();
    }

/*--------------------------buy book action--------------------------*/
    public function buybookAction()
    {

        $this->view = new view_buybook();
        $this->view->show();
    }
    /*-----------buy book action done---------*/
    public function orderdoneAction()
    {
        $date = date('Y-m-d H:i:s');
        $ISBN = $_POST['ISBN'];
        $price = $this->model->gettotal(array($ISBN));
        echo $price;
        $total = (int) $price * (int) $_POST['nbr_copies'];
        $order = array($_POST['ISBN'], testinput($_SESSION, 'user_id'), $_POST['nbr_copies'], $total, $date, 'in progress');
        $this->model->neworder($order);
        // header('location:controller2.php?action=doneaction');
    }

/*--------------borrow book action done---------*/
    public function borrowdoneAction()
    {
        $date = date('Y-m-d H:i:s');
        $borrow = array($_POST['ISBN'], testinput($_SESSION, 'user_id'), $date, $_POST['enddate']);
        $this->model->newborrow($borrow);
        header('location:controller2.php?action=doneaction');
    }
    public function doneAction()
    {
        $this->view = new view_done();
        $this->view->show();
    }
    public function commentAction()
    {
        $commentmessage = $_POST['comment'];
        $comment = array($_GET['ISBN'], testinput($_SESSION, 'user_id'), $commentmessage); //testinput($_SESSION,'user_id')
        $this->model->insert_comment($comment);
        header('location:controller2.php?action=doneaction');
    }
    public function rateAction()
    {
        $ratelevel = $_POST['ratelevel'];
        $rate = array($_GET['ISBN'], testinput($_SESSION, 'user_id'), $ratelevel); //testinput($_SESSION,'user_id')
        $this->model->insert_rate($rate);
        header('location:controller2.php?action=doneaction');
    }
    public function logout()
    {
        session_start();
        unset($_SESSION["user_id"]);
        unset($_SESSION["name"]);
        header("Location:controller2.php");
    }
    public function passwordchange()
    {
        if (isset($_GET['e'])) {if ($_GET['e'] == 't') {
            $error = "Current password incorrect";
        } else {
            $error = " password changed successfully";

        }
        } else {
            $error = "";
        }
        $this->view = new view_chpass();
        $this->view->one($error);
        $this->view->show();

    }
    public function changepasssubmit()
    {
        $id = $_SESSION['user_id'];
        $user = $this->model->getpassword($id);
        $cpass = $_POST['cpass'];
        $npass = $_POST['npass'];
        if ($user['pass'] != $cpass) {
            header("Location:controller2.php?action=passwordchange&e=t");
        } else {
            $this->model->setpassword($npass, $id);
            header("Location:controller2.php?action=passwordchange&e=c");
        }

    }
    public function searchaction()
    {
        if (isset($_POST['search'])) {
            $key = $_POST['search'];
            if (isset($_GET['subject'])) {
                $subject = $_GET['subject'];
                $results = $this->model->searchbookwithsubject($key, $subject);

            } else {
                $results = $this->model->searchbook($key);

            }
            $this->view = new view_allbooksview();
            $this->view->allbooksview($results);
            $this->view->show();

        }
    }
    public function myratesaction()
    {
        $rates = $this->model->get_rate(testinput($_SESSION, 'user_id'));
        $this->view = new my_view($rates);
        $this->view->all($rates);
        $this->view->show();
    }
    public function mycommentsaction()
    {
        $comments = $this->model->get_comment(testinput($_SESSION, 'user_id'));
        $this->view = new my_view($comments);
        $this->view->all($comments);
        $this->view->show();
    }
    public function myordersaction()
    {
        $orders = $this->model->get_order(testinput($_SESSION, 'user_id'));
        $this->view = new my_view($orders);
        $this->view->all($orders);
        $this->view->show();
    }
    public function myborrowsaction()
    {
        $borrows = $this->model->get_borrow(testinput($_SESSION, 'user_id'));
        $this->view = new my_view($borrows);
        $this->view->all($borrows);
        $this->view->show();
    }

    public function action()
    {
        $action = 'allsubjects';
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }

        if (isset($_POST['action'])) {
            $action = $_POST['action'];
        }

        switch ($action) {
            case 'allsubjects':
                $this->allsubjectsAction();
                break;
            case 'allbooksview':
                $this->allbooksviewAction();
                break;
            case 'allbooks':
                $this->allbooksAction();
                break;

            case 'onebook':
                $this->onebookAction();
                break;
            case 'borrowbook':
                $this->borrowbookAction();
                break;
            case 'buybook':
                $this->buybookAction();
                break;
            case 'orderdone':
                $this->orderdoneAction();
                break;
            case 'borrowdone':
                $this->borrowdoneAction();
                break;
            case 'doneaction':
                $this->doneAction();
                break;
            case 'comment':
                $this->commentAction();
                break;
            case 'rate':
                $this->rateAction();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'passwordchange':
                $this->passwordchange();
                break;
            case 'changepasssubmit':
                $this->changepasssubmit();
                break;
            case 'search':
                $this->searchaction();
                break;
            case 'myrates':
                $this->myratesaction();
                break;
            case 'mycomments':
                $this->mycommentsaction();
                break;
            case 'myorders':
                $this->myordersaction();
                break;
            case 'myborrows':
                $this->myborrowsaction();
                break;

        }

    }

}
$c = new controller();
$c->action();