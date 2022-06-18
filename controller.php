<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:login_signup.php');
}
include "model.php";
include "view.php";
class controller
{
    private $action;
    private $model;
    private $view;
    public function __construct()
    {
        $this->model = new model();
        $this->action = "allbooks";
    }
    public function allbooksAction()
    {
        if ($this->model->countbooksrows() > 0) {
            $books = $this->model->get_books();
            $this->view = new view_all($books[0], true);
            $this->view->all($books, 'updatebook', 'delbook', 'ISBN');
            $this->view->show();
        }
    }
    public function onebookAction()
    {
        if (isset($_GET['num'])) {
            $num_row = $_GET['num'];
        } else {
            $num_row = 1;
        }
        $nRows = $this->model->countbooksrows();
        if ($num_row < 1 || $num_row > $nRows) {
            $num_row = 1;
        }
        $book = $this->model->get_book(array($num_row));
        unset($book['num_row']);
        $this->view = new view_onebook($nRows, $book, "onebook");
        $this->view->one($book, "updatebook", "delbook", 'ISBN');
        $this->view->show();
    }
    public function addbookAction()
    {
        $subjects = $this->model->get_subjects();
        $authors = $this->model->get_authors();

        $this->view = new view_add("Book", "addbookinfo");
        $this->view->one("allbooks", array('ISBN', 'title', 'author_id', 'price', 'publisher', 'nbr_copies',
            'subject_id', 'image', 'description'), $subjects, $authors);
        $this->view->show();
    }
    public function addbookinfoAction()
    {
        $book = $_POST;
        $this->model->add_book(array($book['ISBN'], $book['title'], $book['author_id'],
            $book['price'], $book['publisher'], $book['nbr_copies'], $book['subject_id'], $book['image'],
            $book['description']));
        header('location:controller.php');
    }
    public function updatebookAction()
    {
        $ISBN = $_GET['ISBN'];
        $book = $this->model->get_book_ISBN(array($ISBN));
        $this->view = new view_update("book", "upvalbook");
        $authors = $this->model->get_authors();
        $subjects = $this->model->get_subjects();

        $this->view->one($book, "allbooks", $subjects, $authors);
        $this->view->show();
    }
    public function updatebookinfoAction()
    {
        $book = $_POST;
        $this->model->update_book(array($book['title'], $book['author_id'],
            $book['price'], $book['publisher'], $book['nbr_copies'], $book['subject_id'], $book['image'],$book['description'], $book['ISBN']));
        header('location:controller.php');
    }
    public function delbookAction()
    {
        $ISBN = $_GET['ISBN'];
        $this->model->delete_book(array($ISBN));
        header('location:controller.php');
    }
    public function admindashboard()
    {
        $maininfo[0] = $this->model->countbooksrows();
        $maininfo[1] = $this->model->countusersrows();
        $maininfo[2] = $this->model->countauthorsrows();
        $maininfo[3] = $this->model->countborrowrows();
        $maininfo[4] = $this->model->countreturnedbooksrows();
        $maininfo[5] = $this->model->countnonreturnedbooksrows();
        $maininfo[6] = $this->model->countordersrows();
        $maininfo[7] = $this->model->countcommentsrows();
        $maininfo[8] = $this->model->countratesrows();
        $orders = $this->model->get_orders();
        $customers = $this->model->get_users();
        $this->view = new view_admindashboard();
        $this->view->one($maininfo, $orders, $customers);
        $this->view->show();
    }
    /***********users actions*********/
    public function allusersAction()
    {
        if ($this->model->countusersrows() > 0) {
            $users = $this->model->get_users();
            $this->view = new view_all($users[0], true);
            $this->view->all($users, 'updateuser', 'deluser', "user_id");
            $this->view->show();
        }
    }
    public function deleteuserAction()
    {
        $user_id = $_GET['user_id'];
        $book = $this->model->delete_user(array($user_id));
        header('location:controller.php?action=allusers');
    }
    public function updateuserAction()
    {
        $user_id = $_GET['user_id'];
        $user = $this->model->get_user_id(array($user_id));
        $this->view = new view_update("user", "upvaluser");
        $this->view->one($user, "allusers", null, null);
        $this->view->show();
    }
    public function updateuserinfoAction()
    {
        $user = $_POST;
        $this->model->update_user(array($user['name'], $user['email'],
            $user['pass'], $user['image'], $user['user_id']));
        header('location:controller.php?action=allusers');
    }
    public function oneuserAction()
    {
        if (isset($_GET['num'])) {
            $num = $_GET['num'];
        } else {
            $num = 1;
        }
        $nRows = $this->model->countusersrows();
        if ($num < 1 || $num > $nRows) {
            // $num = 1;
            header('location:controller.php?action=oneuser&num=1');

        }

        $user = $this->model->get_user(array($num));
        $this->view = new view_onebook($nRows, $user, "oneuser");
        $this->view->one($user, "updateuser", "deluser", 'user_id');
        $this->view->show();
    }
    public function adduserAction()
    {
        $this->view = new view_add(" User", "adduserinfo");
        $this->view->one("allusers", array('user_id', 'name', 'email', 'pass'), null,
            null);
        $this->view->show();
    }
    public function adduserinfoAction()
    {
        $user = $_POST;
        $this->model->add_user(array($user['name'], $user['email'],
            $user['pass'], $user['image']));
        header('location:controller.php?action=allusers');
    }
    /**************Author action******************/

    public function deleteauthorAction()
    {
        $author_id = $_GET['author_id'];
        if ($this->model->get_authors_number(array($author_id)) == 0) {
            $this->model->delete_author(array($author_id));
        }
        header('location:controller.php?action=allauthors');
    }
    public function allauthorsAction()
    {
        if ($this->model->countauthorsrows() > 0) {
            $users = $this->model->get_authors();
            $this->view = new view_all($users[0], true);
            $this->view->all($users, 'updateauthor', 'delauthor', "author_id");
            $this->view->show();
        }
    }
    public function updateauthorAction()
    {
        $author_id = $_GET['author_id'];
        $author = $this->model->get_author_id(array($author_id));
        $this->view = new view_update("author", "upvalauthor");
        $this->view->one($author, "allauthors", null, null);
        $this->view->show();
    }
    public function updateauthorinfoAction()
    {
        $author = $_POST;
        $this->model->update_author(array($author['name'], $author['phone'], $author['email'], $author['image'],
            $author['author_id']));
        header('location:controller.php?action=allauthors');
    }
    public function oneauthorAction()
    {
        if (isset($_GET['num'])) {
            $num = $_GET['num'];
        } else {
            $num = 1;
        }
        $nRows = $this->model->countauthorsrows();
        if ($num < 1 || $num > $nRows) {
            $num = 1;
        }
        $author = $this->model->get_author(array($num));
        $this->view = new view_onebook($nRows, $author, "oneauthor");
        $this->view->one($author, "updateauthor", "delauthor", 'author_id');
        $this->view->show();
    }
    public function addauthorAction()
    {
        $this->view = new view_add(" author", "addauthorinfo");
        $this->view->one("allauthors", array('name', 'phone', 'email', 'image'), null, null);
        $this->view->show();
    }
    public function addauthorinfoAction()
    {
        $author = $_POST;
        $this->model->add_author(array($author['name'], $author['phone'], $author['email'], $author['image']));
        header('location:controller.php?action=allauthors');
    }
    /**************subject action******************/

    public function allsubjectsAction()
    {
        if ($this->model->countsubjectssrows() > 0) {
            $subjects = $this->model->get_subjects();
            $this->view = new view_all($subjects[0], true);
            $this->view->all($subjects, 'updatesubject', 'delsubject', "subject_id");
            $this->view->show();
        }
    }
    public function updatesubjectAction()
    {
        $subject_id = $_GET['subject_id'];
        $subject = $this->model->get_subject(array($subject_id));
        $this->view = new view_update("subject", "upvalsubject");
        $this->view->one($subject, "allsubjects", null, null);
        $this->view->show();
    }
    public function updatesubjectinfoAction()
    {
        $subject = $_POST;
        $this->model->update_subject(array($subject['name'], $subject['image'], $subject['subject_id']));
        header('location:controller.php?action=allsubjects');
    }
    public function deletesubjectAction()
    {
        $subject_id = $_GET['subject_id'];
        if ($this->model->get_subjects_number(array($subject_id)) == 0) {
            $this->model->delete_subject(array($subject_id));
        }
        header('location:controller.php?action=allsubjects');

    }
    public function addsubjectAction()
    {
        $this->view = new view_add(" subject", "addsubjectinfo");
        $this->view->one("allsubjects", array('name', 'image'), null, null);
        $this->view->show();
    }
    public function addsubjectinfoAction()
    {
        $subject = $_POST;
        $this->model->add_subject(array($subject['name'], $subject['image']));
        header('location:controller.php?action=allsubjects');
    }
    /************Orders actions************/
    public function allordersAction()
    {
        if ($this->model->countordersrows() > 0) {
            $orders = $this->model->get_orders();
            $this->view = new view_all($orders[0], false);
            $this->view->all($orders, '', '', "");
            $this->view->show();
        }
    }
    /****************Borrow***************/
    public function allborrowAction()
    {
        if ($this->model->countborrowrows() > 0) {
            $borrow = $this->model->get_borrow();
            $this->view = new view_all($borrow[0], false);
            $this->view->all($borrow, '', '', "");
            $this->view->show();
        }
    }
    /************comments**************/
    public function allcommentsAction()
    {
        if ($this->model->countcommentsrows() > 0) {
            $comments = $this->model->get_comments();
            $this->view = new view_all($comments[0], false);
            $this->view->all($comments, '', '', "");
            $this->view->show();
        }
    }
    /********************rates**********/
    public function allratesAction()
    {
        if ($this->model->countratesrows() > 0) {
            $rates = $this->model->get_rates();
            $this->view = new view_all($rates[0], false);
            $this->view->all($rates, '', '', "");
            $this->view->show();
        }
    }
    public function logout()
    {
        session_start();
        unset($_SESSION["admin_id"]);
        unset($_SESSION["name"]);
        header("Location:controller.php");
    }
    public function passwordchange()
    {
        if(isset($_GET['e']))
        {if($_GET['e']=='t')
            {
            $error="Current password incorrect";
            }
            else
            {
                $error=" password changed successfully";

            }
        }
        else
        {
            $error="";
        }
        $this->view = new view_chpass();
        $this->view->one($error);
        $this->view->show();
    }
    public function changepasssubmit()
    {
        $id = $_SESSION['admin_id'];
        $admin = $this->model->getpassword($id);
        $cpass = $_POST['cpass'];
        $npass = $_POST['npass'];
        if ($admin['pass'] != $cpass) {
            header("Location:controller.php?action=passwordchange&e=t");
        } 
            else {
            $pass = $this->model->setpassword($npass, $id);
            header("Location:controller.php?action=passwordchange&e=c");
        }

    }
    /*****************search function*******/
    public function searchaction()
    {
    if(isset($_POST['search'])&&isset($_POST['searchtype'])){
       $key=$_POST['search'];
       $type=$_POST['searchtype'];
       echo $type;
       if($type=="author")
       {
       $results=$this->model->searchauthor($key);
       $up='updateauthor';
       $del='delauthor';
       $param="author_id";

       }
       elseif($type=="book")
       {
        $results=$this->model->searchbook($key);
        $up='updatebook';
        $del='delbook';
        $param="ISBN";


       }
       else if($type=="user")
       {
        $results=$this->model->searchuser($key);
        $up='updateuser';
        $del='deluser';
        $param="user_id";

       }
       elseif($type=="subject")
       {
        $results=$this->model->searchsubject($key);
        $up='updatesubject';
        $del='delsubject';
        $param="subject_id";  
       }
       if(isset($results[0])){
       $this->view = new view_all($results[0], true);
       $this->view->all($results, $up, $del, $param);
       $this->view->show();  
       }
    }
    }

    /**********action Class********/
    public function action()
    {
        $action = "allbooks";
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }

        if (isset($_POST['action'])) {
            $action = $_POST['action'];
        }

        switch ($action) {
            case 'allbooks':
                $this->allbooksAction();
                break;
            case 'new':
                $this->newAction();
                break;
            case 'addbook':
                $this->addbookAction();
                break;
            case 'addbookinfo':
                $this->addbookinfoAction();
                break;
            case 'delbook':
                $this->delbookAction();
                break;
            case 'onebook':
                $this->onebookAction();
                break;
            case 'updatebook':
                $this->updatebookAction();
                break;
            case 'upvalbook':
                $this->updatebookinfoAction();
                break;
            case 'dashboard': /*show admin dashboard*/
                $this->admindashboard();
                break;
            /***********Users model************/
            case 'allusers':
                $this->allusersAction();
                break;
            case 'deluser':
                $this->deleteuserAction();
                break;
            case 'updateuser':
                $this->updateuserAction();
                break;
            case 'upvaluser':
                $this->updateuserinfoAction();
                break;
            case 'oneuser':
                $this->oneuserAction();
                break;
            case 'adduser':
                $this->adduserAction();
                break;
            case 'adduserinfo':
                $this->adduserinfoAction();
                break;
            /*****************authors action************/
            case 'allauthors':
                $this->allauthorsAction();
                break;
            case 'delauthor':
                $this->deleteauthorAction();
                break;
            case 'updateauthor':
                $this->updateauthorAction();
                break;
            case 'upvalauthor':
                $this->updateauthorinfoAction();
                break;
            case 'oneauthor':
                $this->oneauthorAction();
                break;
            case 'addauthor':
                $this->addauthorAction();
                break;
            case 'addauthorinfo':
                $this->addauthorinfoAction();
                break;
            /***************subjects****************/
            case 'allsubjects':
                $this->allsubjectsAction();
                break;
            case 'updatesubject':
                $this->updatesubjectAction();
                break;
            case 'upvalsubject':
                $this->updatesubjectinfoAction();
                break;
            case 'delsubject':
                $this->deletesubjectAction();
                break;
            case 'addsubject':
                $this->addsubjectAction();
                break;
            case 'addsubjectinfo':
                $this->addsubjectinfoAction();
                break;

            /*************Orders**************** */
            case 'allorders':
                $this->allordersAction();
                break;
            /***************Borrow************** */
            case 'allborrow':
                $this->allborrowAction();
                break;
            /****************comments*********** */
            case 'allcomments':
                $this->allcommentsAction();
                break;
            /**********rates************* */
            case 'allrates':
                $this->allratesAction();
                break;

            /*****************Logout********/
            case 'logout':
                $this->logout();
                break;
            case 'passwordchange':
                $this->passwordchange();
                break;
            case 'changepasssubmit':
                $this->changepasssubmit();
                break;
            /************search*******/
            case 'search':
                $this->searchaction();
                break;

        }
    }
}
$c = new controller();
$c->action();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>E-library</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="books.css">
    <script src="index.js" defer></script>

</head>

<body>
    <div class="header">
        <div class="sides">
            <a id="logo" class="item"><img src="images/lib.jpg" width="40px" /></a>
            <a href="controller.php?action=addbook" class="item">Add Book</a>
            <a href="controller.php?action=adduser" class="item">Add User</a>
            <a href="controller.php?action=addauthor" class="item">Add Author</a>
            <a href="controller.php?action=addsubject" class="item">Add Subject</a>
            <div class="item lastitem">
                <p>Account</p>
                <div class="accountmenu">
                    <a href="controller.php?action=passwordchange" controller.php?action=passwordchange"
                        class="accountmenuitem">Change Your Password</a>
                    <a href="controller.php?action=logout" class="accountmenuitem">Logout</a>
                </div>
            </div>
            <form method=post action="controller.php?action=search" class="item searchbar">
                <div class="searchbartext"><input type="submit" value="search"></div>
                <input type="search" name="search" id="search">
                <select name="searchtype" id="searchtype">
                    <option selected value="book">book</option>
                    <option value="author">author</option>
                    <option value="user">user</option>
                    <option value="subject">subject</option>
                </select>
            </form>



        </div>

    </div>
    <div class="sidebar">
        <div class="side-item">
            <div class="icon"><img src="images/users.png" width="30px" /></div>
            <div class="text">Name: <?php echo $_SESSION['name']; ?> </div>
        </div>
        <a href="controller.php?action=dashboard" class="side-item">
            <div class="icon"><img src="images/dashboard.png" width="30px" /></div>
            <div class="text">Admin Dashoard</div>
        </a>
        <a href="controller.php?action=allsubjects" class="side-item">
            <div class="icon"><img src="images/category.png" width="30px" /></div>
            <div class="text">Subjects</div>
        </a>
        <div class="side-item booksmenu">
            <div class="icon"><img src="images/onebook.png" width="30px" /></div>
            <div class="text">Books</div>
            <div class="dropdown">
                <a href="controller.php?action=allbooks" class="side-item dropdown-item">
                    <div class="icon"><img src="images/allbooks.png" width="30px" /></div>
                    <div class="text">All books</div>
                </a>
                <a href="controller.php?action=onebook" class="side-item dropdown-item">
                    <div class="icon"><img src="images/book-alt.png" width="30px" /></div>
                    <div class="text">Book 1/1</div>
                </a>
            </div>
        </div>

        <div class="side-item booksmenu">
            <div class="icon"><img src="images/users.png" width="30px" /></div>
            <div class="text">Users</div>
            <div class="dropdown">
                <a href="controller.php?action=allusers" class="side-item dropdown-item">
                    <div class="icon"><img src="images/allbooks.png" width="30px" /></div>
                    <div class="text">All Users</div>
                </a>
                <a href="controller.php?action=oneuser" class="side-item dropdown-item">
                    <div class="icon"><img src="images/subscribers.png" width="30px" /></div>
                    <div class="text">User 1/1</div>
                </a>
            </div>
        </div>
        <div class="side-item booksmenu">
            <div class="icon"><img src="images/Authors.png" width="30px" /></div>
            <div class="text">Authors</div>
            <div class="dropdown">
                <a href="controller.php?action=allauthors" class="side-item dropdown-item">
                    <div class="icon"><img src="images/allbooks.png" width="30px" /></div>
                    <div class="text">All Authors</div>
                </a>
                <a href="controller.php?action=oneauthor" class="side-item dropdown-item">
                    <div class="icon"><img src="images/authors.png" width="30px" /></div>
                    <div class="text">Author 1/1</div>
                </a>
            </div>
        </div>
        <a href="controller.php?action=allorders" class="side-item">
            <div class="icon"><img src="images/orders.jpeg" width="30px" /></div>
            <div class="text">Orders</div>
        </a>
        <a href="controller.php?action=allrates" class="side-item">
            <div class="icon"><img src="images/rates.png" width="30px" /></div>
            <div class="text">Rates</div>
        </a>
        <a href="controller.php?action=allcomments" class="side-item">
            <div class="icon"><img src="images/comment.png" width="30px" /></div>
            <div class="text">Comments</div>
        </a>
    </div>
</body>

</html>