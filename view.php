<?php
/********************************************************/
ini_set("display_errors", "1");
error_reporting(E_ALL);
function selectsubjectoptions($subjects, $value)
{
    $out = "<div class=col><div class=form-group><label for=subjects>Subject</label>
    <select name=subject_id id=subjects value=" . $value . ">";
    foreach ($subjects as $subject) {
        $out .= "<option value=" . $subject['subject_id'] . ">" . $subject['name'] . "</option>";
    }
    $out .= "</select></div></div>";
    return $out;
}
function selectauthoroptions($authors, $value)
{
    $out = "<div class=col><div class=form-group><label for=authors>author</label>
    <select name=author_id id=authors value=" . $value . ">";
    foreach ($authors as $author) {
        $out .= "<option value=" . $author['author_id'] . ">" . $author['name'] . "</option>";
    }
    $out .= "</select></div></div>";
    return $out;
}
/*******************************************************/
class view
{
    protected $content;
    public function __construct()
    {
        $this->content = "<div class=maincontainer >";
    }
    public function getContent()
    {return $this->content;}
    public function finishContent()
    {$this->content .= "</div></body></html>";}
    public function show()
    {
        $this->finishContent();
        echo $this->getContent();
    }
}
class view_all extends view
{
    public function __construct($book, $addupdatedelete)
    {
        parent::__construct();
        $this->content .= "<table class=table-fill><thead><tr>";
        if (array_key_exists('image', $book)) {
            $this->content .= "<th class=text-left>image</th>";

        }
        foreach ($book as $key => $value) {
            if (array_key_exists('image', $book) && $key == "image") {continue;}
            $this->content .= "<th class=text-left>" . $key . "</th>";

        }
        if ($addupdatedelete) {
            $this->content .= "<th>Update</th><th>Delete</th></tr></thead><tbody class=table-hover>";
        }
    }
    public function updatehtml($action, $getparam, $book)
    {
        return "<td><a href=controller.php?action=" . $action . "&" . $getparam . "=" . $book[$getparam] . "><img
        src=./images/edit.png width=40px></a></td>";
    }
    public function deletehtml($action, $getparam, $book)
    {
        return "<td><a href=controller.php?action=" . $action . "&" . $getparam . "=" . $book[$getparam] . "><img src=./images/delete.png
            width=40px></a></td></tr>";
    }
    public function all($books, $upaction, $delaction, $getparam)
    {
        foreach ($books as $book) {
            $this->content .= "<tr>";
            if (isset($book['image'])) {
                $this->content .= "<td class=text-left><img src=./covers/" . $book['image'] . " width=250px ></td>";
            }
            elseif($getparam=='iser_id'){
                $this->content .='<td>No image</td>';
            }
            foreach ($book as $key => $value) {
                if ($key !== "image" && $key !== "description") {
                    $this->content .= "<td class=text-left>" . $value . "</td>";
                } else if ($key == "description") {
                    $this->content .= "<td class=text-left>" . substr($value, 0, 10) . "...</td>";
                }
            }
            if ($getparam != "") {
                $this->content .= $this->updatehtml($upaction, $getparam, $book);
                $this->content .= $this->deletehtml($delaction, $getparam, $book);
            }
        }
        $this->content .= "</tbody></table>";
    }
}
/***********************************************************************/
class view_update extends view
{
    public function __construct($title, $submitaction)
    {
        $this->content = "<script src=imageinput.js defer></script><style>.header{position:absolute;top:0;left:0;} .form-group{padding-top:30px;} </style>
        <form action=controller.php?action=" . $submitaction . " method=post class=formcontainer>
        <div class=card> <h2>Update " . $title . " Info</h2><div class=row>";
    }
    public function one($book, $cancelaction, $subjects, $authors)
    {
        $this->content .= "<div class=col><div class=file-upload>
        <div class=file-upload-select>
            <div class=file-select-button >Choose image</div>
        <div class=file-select-name>No image chosen...</div>
        <input type=file name=image id=file-upload-input value=" . $book['image'] . ">
        </div>
        </div></div>";

        foreach ($book as $key => $value) {
            if ($key == "ISBN" || $key == "user_id" || (($key == "author_id" || $key == "subject_id") && $cancelaction != "allbooks")) {
                $dis = "readonly";
            } else {
                $dis = "";
            }
            if ($key == "image") {
                continue;
            }
            if ($key == "author_id" && $cancelaction == "allbooks") {
                $this->content .= selectauthoroptions($authors, $value);
            } elseif ($key == "subject_id" && $cancelaction == "allbooks") {
                $this->content .= selectsubjectoptions($subjects, $value);

            } else {
                $this->content .= "<div class=col><div class=form-group><label>" . $key . "</label><input type=text name=" . $key . " value=" . $value . " " . $dis . "></div></div>";
            }

        }
        $this->content .= "<div class=col>
                <input type=submit value=Submit>
                </div>
                <div class=col>
                <a href=controller.php?action=" . $cancelaction . "><input type=button value=Cancel></a>
            </div>";
    }
    public function finishContent()
    {$this->content .= "</form></body></html>";}
}
/******************************************************/
function inputcode($type, $name)
{
    return "<div class=col><div class=form-group><label>" . $name . "</label><input type=" . $type . " name=" . $name . " required></div></div>";

}
/**********************************************************/
class view_add extends view
{
    public function __construct($title, $formaction)
    {
        parent::__construct();
        $this->content = "<style>.header{position:absolute;top:0;left:0;} .form-group{padding-top:30px;} </style><script src=imageinput_add.js defer></script><form action=controller.php?action=" . $formaction . " method=post class=formcontainer>
        <div class=card> <h2>Add" . $title . " </h2><div class=row>";
    }
    public function one($cancelaction, $keys, $subjects, $authors)
    {
        $this->content .= "<div class=col><div class=file-upload>
        <div class=file-upload-select>
            <div class=file-select-button >Choose image</div>
        <div class=file-select-name>No image chosen...</div>
        <input type=file name=image id=file-upload-input>
        </div>
        </div></div>";
        foreach ($keys as $key) {
            if ($key == "user_id" || $key == "image" || ($key == "author_id" && $cancelaction != "allbooks") || ($key == "subject_id" && $cancelaction != "allbooks")) {continue;}
            if (($key == "subject_id" || $key == "author_id") && $cancelaction == "allbooks") {
                if ($key == "subject_id") {
                    $this->content .= selectsubjectoptions($subjects, null);
                } else {
                    $this->content .= selectauthoroptions($authors, null);

                }

            } else {
                $this->content .= inputcode("text", $key);

            }

        }
        $this->content .= "<div class=col>
        <input type=submit value=Submit></div>
        <div class=col>
        <a href=controller.php?action=" . $cancelaction . "><input type=button value=Cancel></a></div>";
    }
    public function finishContent()
    {$this->content .= "</form></div>";}
}

/**********************************************************************/

class view_onebook extends view
{
    public function __construct($nRows, $book, $action)
    {
        parent::__construct();
        $this->content .= "<table class=table-fill><thead><tr>";
        if (array_key_exists('image', $book)) {
            $this->content .= "<th class=text-left>image</th>";

        }
        foreach ($book as $key => $value) {
            if ((array_key_exists('image', $book) && $key == "image") || $key == 'num_row') {continue;}
            $this->content .= "<th class=text-left>" . $key . "</th>";
        }

        $this->content .= "<th><a href=controller.php?action=" . $action . "&num=" . $this->outofrange_min($nRows) . " id=previous>&lt;</a></th>";
        $this->content .= "<th><a href=controller.php?action=" . $action . "&num=" . $this->outofrange_max($nRows) . " id=next>&gt;</a></th>";
        $this->content .= "</tr></thead><tbody class=table-hover>";
    }
    private function outofrange_min($nRows) //when 1 we cannot decrement to page 0

    {
        if (isset($_GET['num'])) {
            if (--$_GET['num'] == 0) {
                return $nRows;
            } else {
                return $_GET['num'];
            }
        } else {
            return 1;
        }

    }
    private function outofrange_max($nRows) //when  we get to last page we return to page 1

    {
        if (isset($_GET['num'])) {

            if (++$_GET['num'] == $nRows) {
                return 1;
            } else {
                return $_GET['num'] + 1;
            }
        } else {
            return 2;
        }

    }
    public function one($book, $updateaction, $deleteaction, $getparam)
    {
        $this->content .= "<tr>";
        $this->content .= "<td class=text-left><img src=./covers/" . $book['image'] . " width=250px ></td>";

        foreach ($book as $key => $value) {
            if ($key != "image" && $key != "num_row" && $key != "description") {
                $this->content .= "<td class=text-left>" . $value . "</td>";
            } else if ($key == "description") {
                $this->content .= "<td class=text-left>" . substr($value, 0, 10) . "...</td>";

            }

        }
        $this->content .= "<td><a href=controller.php?action=" . $updateaction . "&" . $getparam . "=" . $book[$getparam] . "><img
                    src=./images/edit.png width=40px></a></td>";
        $this->content .= "<td><a href=controller.php?action=" . $deleteaction . "&" . $getparam . "=" . $book[$getparam] . "><img
                    src=./images/delete.png width=40px></a></td></tr>";
        $this->content .= "</tbody></table>";
    }}
/**********************************************************************/
class view_admindashboard extends view
{
    public function __construct()
    {
        $this->content = "<link rel=stylesheet href=dashboard.css />
        <div class=dashboardcontainer>
        <div class=welcomingbox>
         <div class=dashboardtext>
          <div class=dashboardheader>
           <div class=dashboardheader1>Welcome Back!</div>
           <div class=dashboardheader2>" . $_SESSION['name'] . "</div>
          </div>
          <div class=welcomingtext>
          Online Library Management System provides power and productivity to small, medium and huge library centers. With this feature-rich Online LMS, there is never a need to worry on valuable data. All of your data, including archival data, remains instantly accessible all the time—with no system slowdown. Up-to-date information on books, users,authors is just a click away.
          </div>
         </div>
         <div class=dashboardlogo>
          <img src=images/admin-dashboard.png height=70% />
         </div>
        </div>
        <div class=cardBox>";
    }
    public function createcard($info, $infoname, $imagename, $action)
    {
        return "<a href=controller.php?action=" . $action . "><div class=card>
        <div>
         <div class=numbers>" . $info . "</div>
         <div class=cardName>" . $infoname . "</div>
        </div>
        <div class=iconBx>
         <ion-icon name=eye-outline>
          <img src=images/" . $imagename . ".png width=60
         /></ion-icon>
        </div>
       </div></a>";
    }
    public function createtableorders($orders)
    {
        $string = "<div class=details>
        <div class=recentOrders>
         <div class=cardHeader>
          <h2>Recent Orders</h2>
          <a href=# class=btn>View All</a>
         </div>
         <table>
          <thead>
           <tr>
            <td>orderID</td>
            <td>ISBN</td>
            <td>user_id</td>
            <td>nbr_copies</td>
            <td>total</td>
            <td>orderdate</td>
            <td>orderstatus</td>
           </tr>
          </thead>
          <tbody>";
        foreach ($orders as $order) {
            $string .= "<tr>";
            foreach ($order as $key => $value) {
                if ($key == "orderstatus") {
                    $string .= "<td><span class=\"status $value\">" . $value . "</span></td>";
                } else {
                    $string .= "<td>" . $value . "</td>";
                }
            }
            $string .= "</tr>";

        }
        $string .= "</tbody></table></div></div>";
        return $string;

    }
    public function createtablerecent($customers)
    {
        $string = "<table>";
        foreach ($customers as $customer) {
            $string .= "<tr>";
            $string .= "<td width=60px> <div class=imgBx><img src=images/useradmin.png /></div></td>
            <td><h4>" . $customer['name'] . "<br/></h4></td>";
            $string .= "</tr>";

        }
        $string .= "</table>";
        return $string;

    }
    public function one($maininfo, $orders, $customers)
    {
        $this->content .= $this->createcard($maininfo[0], "Total Books", "book-stack", "allbooks");
        $this->content .= $this->createcard($maininfo[1], "Users", "useradmin", "allusers");
        $this->content .= $this->createcard($maininfo[2], "Authors", "author", "allauthors");
        $this->content .= $this->createcard($maininfo[3], "Borrowed", "issued", "allborrow");
        $this->content .= $this->createcard($maininfo[4], "Returned", "returned", "allborrow");
        $this->content .= $this->createcard($maininfo[5], "Non Returned", "nonreturned", "allborrow");
        $this->content .= $this->createcard($maininfo[6], "Orders", "order", "allorders");
        $this->content .= $this->createcard($maininfo[7], "Comments", "comments", 'allcomments');
        $this->content .= $this->createcard($maininfo[8], "Rates", "rate", 'allrates');
        $this->content .= "</div><div class=otherdetailsinfo>"; //beginning of section of details
        $this->content .= $this->createtableorders($orders);
        $this->content .= "<div class=recentCustomers> <div class=cardHeader> <h2>Recent Customers</h2> </div>";
        $this->content .= $this->createtablerecent($customers);
    }
    public function finishContent()
    {$this->content .= "</div></div></div></body></html>";}
}
class view_chpass extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content = "";
    }
    public function one($error)
    {
        $this->content = "
    <style>.login-form
    {display:flex;align-items:center;justify-content:center;flex-direction:column;}body{overflow:hidden;}.form{top:80px;}</style>
    <div class=login-page>
  <div class=form>
    <form class=login-form method=post action=controller.php?action=changepasssubmit>
      <input type=password placeholder=CurrentPassword name=cpass id='cpass'required>
      <label for=cpass>".$error."</label>
      <input type=password placeholder=NewPassword name=npass required>
      <input type=submit value=Submit>
  </div>
  </div>";
    }

}