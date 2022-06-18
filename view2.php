<?php
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
//------------------------------------//
class view_allbooks extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<table class=table-fill>
   <thead>
<tr>
        <th class=text-left>Book Cover</th>
        <th class=text-left>ISBN</th>
        <th class=text-left>title</th>
        <th class=text-left>author_id</th>
        <th class=text-left>price</th>
        <th class=text-left>copy available</th>
        <th class=text-left>subject_id</th>
        <th>view</th>

    </tr>
</thead>
<tbody class=table-hover> ";}
    public function all($books)
    {

        foreach ($books as $book) {
            $this->content .= "<tr>";
            if ((isset($book['image'])) and (!empty($book['image']))) {
                $this->content .= "<td class=text-left><img src=./covers/" . $book['image'] . " width=250px ></td>";
            }
            if (empty($book['image'])) {
                $this->content .= "<td class=text-left><img src=./images/book-stack.png width=230px ></td>";
            }
            $this->content .= "<td class=text-left>" . $book['ISBN'] . "</td>";
            $this->content .= "<td class=text-left>" . $book['title'] . "</td>";
            $this->content .= "<td class=text-left>" . $book['author_id'] . "</td>";
            $this->content .= "<td class=text-left>" . $book['price'] . "</td>";
            $this->content .= "<td class=text-left>" . $book['nbr_copies'] . "</td>";
            $this->content .= "<td class=text-left>" . $book['subject_id'] . "</td>";
            $this->content .= " <td><a href=controller2.php?action=onebook&ISBN=" . $book['ISBN'] . "><img
            src=./images/eye.png width=40px></a></td></tr>";

        }

        $this->content .= "</tbody></table>";
    }}
//--------------------one book---------------------------//
class view_onebook extends view
{
    public function __construct()
    {
        parent::__construct();

    }
    public function onebook($book)
    {

        $this->content .= "<div class=grid-container>";
        if ((isset($book['image'])) and (!empty($book['image']))) {
            $this->content .= "<div id=item1 class=grid-item><img src=./covers/" . $book['image'] . " width=250px ></div>";
        }
        if (empty($book['image'])) {

            $this->content .= "<div id=item1 class=grid-item><img src=./covers/book-stack.png  height=500px width=400px /></div>";
        }

        $this->content .= "<div id=item2 class=grid-item>ISBN:" . $book['ISBN'] . "</div>";
        $this->content .= "<div id=item3 class=grid-item>Title:" . $book['title'] . "</div>";
        $this->content .= "<div id=item4  class=grid-item>Author_id:" . $book['author_id'] . "</div>";
        $this->content .= "<div id=item5  class=grid-item>Price:" . $book['price'] . "</div>";
        $this->content .= "<div id=item6  class=grid-item>Number of copies:" . $book['nbr_copies'] . "</div>";
        $this->content .= "<div id=item7  class=grid-item>Subject_id:" . $book['subject_id'] . "</div>";
        $this->content .= "<div id=item9  class=grid-item>description:     " . $book['description'] . "</div>";

        $this->content .= "<div id=item8  class=grid-item ><section class=cards2 id=services2>
            <h2 class=title2>Services</h2>
            <div class=content2>
                <div class=card2>
                    <div class=icon2>

                    </div>
                    <div class=info2>
                        <h3><a href=controller2.php?action=buybook&ISBN=" . $_GET['ISBN'] . ">Buy Book</a></h3>

                    </div>
                </div>
                <div class=card2>
                    <div class=icon2>

                    </div>
                    <div class=info2>
                        <h3><a href=controller2.php?action=borrowbook&ISBN=" . $_GET['ISBN'] . ">Borrow Book</a></h3>

                    </div>
                </div>

            </div>
        </section>


      </div></div>";
    }
    public function comment()
    {
        $this->content .= "



            <section class=cards contact id=contact>
            <h2 class=title>comment section</h2>
            <div class=content>
            <form action=controller2.php?action=comment&ISBN=" . $_GET['ISBN'] . "     method=post>
                <div  id=cardcomment class=card>
                    <div class=icon>

                    </div>
                    <div class=info>
                        <h3>comment here:</h3>

                        <p><textarea id=comment name=comment ></textarea></p>
                        <button type=submit>submit</button>
</div>
</div>
</form>
</div>
</section>

";
    }
    public function rate()
    {
        $this->content .= "<div class=ratebox>
    <h1>Rate the book</h1>
    <div class=star-rating>
        <div class=back-stars>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>


            <div class=front-stars>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>
            <i class=\"fa fa-star\" aria-hidden=true></i>

            </div>
        </div>
    </div>

    <form class=rate-board action=controller2.php?action=rate&ISBN=" . $_GET['ISBN'] . "     method=post>
      <label>Your rate is:</label>
      <input id=rate-number name=ratelevel readonly required></input>
      <input type=submit class=submitrate value=Rate>
      </form>
      </div>";
    }

}
ini_set("display_errors", "1");
error_reporting(E_ALL);
/*-----------------------------borrow book-----------------------------------*/
class view_borrowbook extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<style>
.login-box .user-box label {
    top: -30px;
}
</style>
<div id=tsparticles></div>
<div class=login-box>
    <h2>borrow chart</h2>
    <form action=controller2.php?action=borrowdone method=post>
        <div class=user-box>
            <input type=text name=ISBN value=" . $_GET['ISBN'] . " readonly>
            <label>ISBN</label>
        </div>
        <div class=user-box>
            <input type=date name=enddate required>
            <label>end date</label>
        </div>
        <a href=#>
            <span></span>
            <span></span>
            <span></span>
            <span></span>

            <button class=order type=submit>submit</button></a>
    </form>
</div>


";
    }

}
/**************Buy book******************** */
class view_buybook extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<style>
.login-box .user-box label {
    top: -30px;
}
</style>
<div id=tsparticles></div>
<div class=login-box>
    <h2>buy chart</h2>
    <form action=controller2.php?action=orderdone method=post>
        <div class=user-box>
            <input type=text name=ISBN value=" . $_GET['ISBN'] . " readonly>
            <label>ISBN</label>
        </div>
        <div class=user-box>
            <input type=text name=nbr_copies required>
            <label>nbr_copies</label>
        </div>

        <a href=#>


            <button class=order type=submit>submit</button></a>
    </form>
</div>


";
    }

}

/*********************All subjects***************** */
class view_allsubjects extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<section class=main>
    <div>
        <h2><br><span>Welcome To Your Library</span></h2>
        <div class=main-btndiv>
            <a href=#subjects class=main-btn>View book categories</a>
            <a href=controller2.php?action=allbooksview class=main-btn> View all books </a>
        </div>
    </div>
</section>
<section class=subjects id=subjects>
    <h2 class=title>Subjects</h2>
    <div class=content>

        ";
    }
    public function allsubjects($subjects)
    {
        foreach ($subjects as $subject) {
            $this->content .= "<div class=project-card>
            <div class=project-image>
                <img src=";
            if (isset($subject['image']) and (!empty($subject['image']))) {
                $this->content .= " ./images/" . $subject['image'] . ">";
            }
            if (empty($subject['image'])) {
                $this->content .= "./images/work1.png />";
            }

            $this->content .= "
            </div>
            <div class=project-info>
                <p class=project-category>" . $subject['name'] . "</p>
                <strong class=project-title>
                    <span> Books</span>
                    <a href=controller2.php?action=allbooks&subject=" . $subject['name'] . " class=more-details>Go</a>
                </strong>
            </div>
        </div>";

        }
        $this->content .= "
    </div>
</section>

<section class=cards contact id=contact>
    <h2 class=title>contact us</h2>
    <div class=content>
        <div class=card>
            <div class=icon>

            </div>
            <div class=info>
                <h3>Phone</h3>
                <p>+20122222222</p>
            </div>
        </div>
        <div class=card>
            <div class=icon>


            </div>
            <div class=info>
                <h3>Email</h3>
                <p>business@library.com</p>
            </div>
        </div>
    </div>
</section>

<footer class=footer>
    <p class=footer-title>Copyrights @ <span>2022</span></p>

</footer>



";
    }

}
/*************all books******************** */
class view_allbooksview extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<style>
.maincontainer {
    display: flex;
    justify-content: center;
}
</style>
<div class=cardBox>";
    }
    public function allbooksview($books)
    {
        foreach ($books as $book) {
            $this->content .= "<a href=controller2.php?action=onebook&ISBN=" . $book['ISBN'] . ">";
            if ((isset($book['image'])) and (!empty($book['image']))) {
                $this->content .= "<div class=card><img src=./covers/" . $book['image'] . "  height=100% width=100% ></div>";}
            if (empty($book['image'])) {$this->content .= "<div class=card><img src=./covers/book-stack.png
                    height=200px width=200px /></div>";
            }
        }
        $this->content .= "</a></div>";
    }

}
/*------------view done--------------------------*/
class view_done extends view
{
    public function __construct()
    {
        parent::__construct();
        $this->content .= "<div id=tsparticles></div>
     <div class=login-box>


     <div class=user-box>
        <h2>Your operation is done successfully!</h2>
     </div>
     </div>";
    }

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
    <style>.form{margin:0 -180px 96.4px};</style>
    <div class=login-page>
  <div class=form>
    <form class=login-form method=post action=controller2.php?action=changepasssubmit>
      <input type=password placeholder=CurrentPassword name=cpass id='cpass'required>
      <label for=cpass>" . $error . "</label>
      <input type=password placeholder=NewPassword name=npass required>
      <input type=submit value=Submit>
  </div>
  </div>";
    }

}
class my_view extends view
{
    public function __construct($elements)
    {
        parent::__construct();
        $this->content .= "<table class=table-fill><thead><tr>";
        foreach ($elements[0] as $key => $value) {
            if ($key == 'user_id' || $key == 'comment_id' || $key == 'rate_id' || $key == 'orderid' || $key == 'borrow_id') {
                continue;}
            $this->content .= "<th class=text-left>" . $key . "</th>";
        }

        $this->content .= "</tr></thead><tbody class=table-hover>";
    }
    public function all($elements)
    {
        foreach ($elements as $element) {
            $this->content .= "<tr>";
            foreach ($element as $key => $value) {
                if ($key == 'user_id' || $key == 'comment_id' || $key == 'rate_id' || $key == 'orderid' || $key == 'borrow_id') {
                    continue;}
                $this->content .= "<td class=text-left>" . $value . "</td>";
            }
            $this->content .= "</tr>";

        }

        $this->content .= "</tbody></table>";
    }}