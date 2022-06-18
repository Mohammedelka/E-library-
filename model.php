<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
class model
{
    private $db;
    /****for connexion pdo*****/
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $this->db = new PDO("mysql:host=$servername;dbname=library", $username, $password);
    }
    public function get_books()
    {
        $query = $this->db->prepare('SELECT * FROM books');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_book($num_row)
    {
        $query = $this->db->prepare('SELECT * FROM books_view where num_row = ?');
        $query->execute($num_row);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function get_book_ISBN($ISBN)
    {
        $query = $this->db->prepare('SELECT * FROM books where ISBN = ?');
        $query->execute($ISBN);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function add_book($book)
    {
        $query = $this->db->prepare('INSERT INTO books VALUES (?,?, ?, ?, ?, ?, ?,?,?)');
        $query->execute($book);
    }
    public function delete_book($ISBN)
    {
        $query = $this->db->prepare('DELETE FROM books WHERE ISBN = ?');
        $query->execute($ISBN);
    }
    public function update_book($book)
    {
        if ($book[6] == "") { //case admin didn't upload new image,we didn't change old one
            unset($book[6]);
            $book = array_values($book);
            $query = $this->db->prepare('UPDATE books set title = ?, author_id = ?, price = ?,
            publisher=?,nbr_copies = ?, subject_id = ?,description=? WHERE ISBN = ?');
        } else { //case admin  upload new image
            $query = $this->db->prepare('UPDATE books set title = ?, author_id = ?, price = ?,
             publisher=?,nbr_copies = ?, subject_id = ?,description=?, image = ? WHERE ISBN = ?');
        }
        $query->execute($book);

    }
    public function countbooksrows()
    {
        $nRows = $this->db->query('select count(*) from books')->fetchColumn();
        return $nRows;
    }

    /*******************for users****************************/
    public function get_users()
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_user($num) /******view**** */
    {
        $query = $this->db->prepare('SELECT * FROM users_view where num_row=?');
        $query->execute($num);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function get_user_id($id)
    {
        $query = $this->db->prepare('SELECT * FROM users where user_id=?');
        $query->execute($id);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function get_user_login($email, $password)
    {
        $query = $this->db->prepare('SELECT * FROM users where email=? and pass=?');
        $query->execute(array($email, $password));
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function add_user($user)
    {
        $query = $this->db->prepare('INSERT INTO users(name,email,pass) VALUES (?,?,?)');
        $query->execute($user);
    }
    public function delete_user($id)
    {
        $query = $this->db->prepare('DELETE FROM users WHERE user_id = ?');
        $query->execute($id);

    }
    public function update_user($user)
    {
        if ($user[3] == "") { //case admin didn't upload new image,we didn't change old one
            unset($user[3]);
            $user = array_values($user);
            $query = $this->db->prepare('UPDATE users set name =?,email =?,pass =? WHERE user_id = ?');
        } else { //case admin  upload new image
            $query = $this->db->prepare('UPDATE users set name =?,email =?,pass =?,image=? WHERE user_id = ?');
        }
        $query->execute($user);

    }
    public function countusersrows()
    {
        $nRows = $this->db->query('select count(*) from users')->fetchColumn();
        return $nRows;
    }
/*****************************Authors**********/
    public function get_authors()
    {
        $query = $this->db->prepare('SELECT * FROM authors');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_author($num)
    {
        $query = $this->db->prepare('SELECT * FROM authors_view where num_row=?');
        $query->execute($num);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function get_author_id($id)
    {
        $query = $this->db->prepare('SELECT * FROM authors where author_id=?');
        $query->execute($id);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function get_authors_number($id)
    {
        $query = $this->db->prepare('SELECT count(*) FROM books where author_id=?');
        $query->execute($id);
        return $query->fetchColumn();
    }

    public function add_author($author)
    {
        $query = $this->db->prepare('INSERT INTO authors(name,phone,email,image) VALUES (?,?,?,?)');
        $query->execute($author);
    }
    public function delete_author($id)
    {
        $query = $this->db->prepare('DELETE FROM authors WHERE author_id = ?');
        $query->execute($id);

    }
    public function update_author($author)
    {
        if ($author[3] == "") {
            //case admin didn't upload new image,we didn't change old one
            unset($author[3]);
            $author = array_values($author);
            $query = $this->db->prepare('UPDATE authors set name =?,phone =?,email =? WHERE author_id = ?');
        } else {
            //case admin  upload new image
            $query = $this->db->prepare('UPDATE authors set name =?,phone=?,email =?,image=? WHERE author_id = ?');
        }
        $query->execute($author);

    }
    public function countauthorsrows()
    {
        $nRows = $this->db->query('select count(*) from authors')->fetchColumn();
        return $nRows;
    }
    /*****************subjects******************** */
    public function countsubjectssrows()
    {
        $nRows = $this->db->query('select count(*) from subjects')->fetchColumn();
        return $nRows;
    }
    public function get_subjects()
    {
        $query = $this->db->prepare('SELECT * FROM subjects');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_subjects_number($id)
    {
        $query = $this->db->prepare('SELECT count(*) FROM books where subject_id=?');
        $query->execute($id);
        return $query->fetchColumn();
    }
    public function get_subject($id)
    {
        $query = $this->db->prepare('SELECT * FROM subjects where subject_id=?');
        $query->execute($id);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function update_subject($subject)
    {
        if ($subject[1] == "") {
            //case admin didn't upload new image,we didn't change old one
            unset($subject[1]);
            $subject = array_values($subject);
            $query = $this->db->prepare('UPDATE subjects set name =? WHERE subject_id = ?');
        } else {
            //case admin  upload new image
            $query = $this->db->prepare('UPDATE subjects set name =?,image=? WHERE subject_id = ?');
        }
        $query->execute($subject);

    }
    public function delete_subject($id)
    {
        $query = $this->db->prepare('DELETE FROM subjects WHERE subject_id = ?');
        $query->execute($id);

    }
    public function add_subject($subject)
    {
        $query = $this->db->prepare('INSERT INTO subjects(name,image) VALUES (?,?)');
        $query->execute($subject);
    }
    /*****************Orders******************** */
    public function countordersrows()
    {
        $nRows = $this->db->query('select count(*) from orders')->fetchColumn();
        return $nRows;
    }
    public function get_orders()
    {
        $query = $this->db->prepare('SELECT * FROM orders');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*****************Borrow******************** */
    public function countborrowrows()
    {
        $nRows = $this->db->query('select count(*) from borrow')->fetchColumn();
        return $nRows;
    }
    public function countreturnedbooksrows()
    {
        $nRows = $this->db->query('select count(*) from borrow where date_returned IS NOT NULL')->fetchColumn();
        return $nRows;
    }
    public function countnonreturnedbooksrows()
    {
        $nRows = $this->db->query('select count(*) from borrow where date_returned IS  NULL')->fetchColumn();
        return $nRows;
    }
    public function get_borrow()
    {
        $query = $this->db->prepare('SELECT * FROM borrow');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*****************comments******************** */
    public function countcommentsrows()
    {
        $nRows = $this->db->query('select count(*) from comments')->fetchColumn();
        return $nRows;
    }
    public function get_comments()
    {
        $query = $this->db->prepare('SELECT * FROM comments');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /*****************rates******************** */
    public function countratesrows()
    {
        $nRows = $this->db->query('select count(*) from rates')->fetchColumn();
        return $nRows;
    }
    public function get_rates()
    {
        $query = $this->db->prepare('SELECT * FROM rates');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /***********************************/
    public function alreadyexistuser($email)
    {
        $query = $this->db->prepare('select count(*) from users where email=?');
        $query->execute(array($email));
        return $query->fetchColumn();
    }
    public function check_user($email, $pass)
    {
        $query = $this->db->prepare('select count(*) from users WHERE email=?  AND pass=?');
        $query->execute(array($email, $pass));
        return $query->fetchColumn();
    }
    public function check_admin($email, $pass)
    {
        $query = $this->db->prepare('select count(*) from adminlogin WHERE email=?  AND pass=?');
        $query->execute(array($email, $pass));
        return $query->fetchColumn();
    }
    public function get_admin($email, $pass)
    {
        $query = $this->db->prepare('select * from adminlogin WHERE email=?  AND pass=?');
        $query->execute(array($email, $pass));
        return $query->fetch(PDO::FETCH_ASSOC);    }
    public function getpassword($id)
    {
        $query = $this->db->prepare('select * from adminlogin WHERE admin_id=?');
        $query->execute(array($id));
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function setpassword($npass,$id)
    {
        $query = $this->db->prepare('update adminlogin set pass=? WHERE admin_id=?');
        $query->execute(array($npass,$id));
    }
    /***************search***********/
    public function searchauthor($name)
    {
        $query = $this->db->prepare("SELECT * FROM authors WHERE name LIKE ?");
        $query->execute(array("%".$name."%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchbook($title)
    {
        $query = $this->db->prepare("SELECT * FROM books WHERE title LIKE ?");
        $query->execute(array("%".$title."%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchsubject($name)
    {
        $query = $this->db->prepare("SELECT * FROM subjects WHERE name LIKE ?");
        $query->execute(array("%".$name."%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchuser($name)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE name LIKE ?");
        $query->execute(array("%".$name."%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    
    }
}