<?php

class model2
{
    private $db;
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $this->db = new PDO("mysql:host=$servername;dbname=library", $username, $password);
    }

    public function get_allsubjects()
    {
        $query = $this->db->prepare('SELECT * FROM subjects');
        $query->execute();
        return $query->fetchAll();
    }

    public function get_books()
    {
        $query = $this->db->prepare('SELECT * FROM books');
        $query->execute();
        return $query->fetchAll();
    }

    public function get_booksbysubject($subject)
    {
        $query = $this->db->prepare(' SELECT
        b.ISBN,b.title,b.author_id,b.price,b.publisher,b.nbr_copies,b.subject_id,b.image,b.description
        FROM books as b
        INNER JOIN subjects as s ON b.subject_id=s.subject_id and s.name =?');
        $query->execute($subject);
        return $query->fetchAll();
    }
    public function get_bookbyid($ISBN)
    {
        $query = $this->db->prepare('SELECT * FROM books where ISBN=?');
        $query->execute($ISBN);
        return $query->fetch();
    }
    public function newborrow($borrow)
    {

        $query = $this->db->prepare('INSERT INTO borrow(ISBN,user_id,date_borrowed,date_returned) VALUES (?, ?, ?, ?)');
        $query->execute($borrow);
    }
    public function neworder($order)
    {

        $query = $this->db->prepare('INSERT INTO orders(ISBN,user_id,nbr_copies,total,orderdate,orderstatus) VALUES (?, ?, ?, ?,?,?)');
        $query->execute($order);
    }
    public function gettotal($ISBN)
    {

        $query = $this->db->prepare('SELECT price from books where ISBN=?  ');
        $query->execute($ISBN);
        return $query->fetchColumn();
    }
    public function insert_comment($comment)
    {
        $query = $this->db->prepare('INSERT INTO comments(ISBN,user_id,content) VALUES (?, ?, ?)');
        $query->execute($comment);
    }
    public function insert_rate($rate)
    {
        $query = $this->db->prepare('INSERT INTO rates(ISBN,user_id,ratelevel) VALUES (?, ?, ?)');
        $query->execute($rate);
    }
    public function get_rate($user_id)
    {
        $query = $this->db->prepare('select * from  rates where user_id = ?');
        $query->execute(array($user_id));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_comment($user_id)
    {
        $query = $this->db->prepare('select * from  comments where user_id = ?');
        $query->execute(array($user_id));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_order($user_id)
    {
        $query = $this->db->prepare('select * from  orders where user_id = ?');
        $query->execute(array($user_id));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_borrow($user_id)
    {
        $query = $this->db->prepare('select * from  borrow where user_id = ?');
        $query->execute(array($user_id));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getpassword($id)
    {
        $query = $this->db->prepare('SELECT * from users where user_id=?  ');
        $query->execute(array($id));
        return $query->fetch();
    }
    public function setpassword($npass, $id)
    {
        $query = $this->db->prepare('update users set pass=? where user_id=?');
        $query->execute(array($npass, $id));
    }
    public function searchbook($title)
    {
        $query = $this->db->prepare("SELECT * FROM books WHERE title LIKE ?");
        $query->execute(array("%" . $title . "%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchbookwithsubject($key, $subject)
    {
        $query = $this->db->prepare('SELECT
        b.ISBN,b.title,b.author_id,b.price,b.publisher,b.nbr_copies,b.subject_id,b.image,b.description
        FROM books as b
        INNER JOIN subjects as s ON b.subject_id=s.subject_id and s.name =? and b.title LIKE ?');
        $query->execute(array($subject, "%" . $key . "%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}