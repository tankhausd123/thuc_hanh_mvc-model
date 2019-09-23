<?php

namespace controller;

use model\Customer;
use model\CustomerDB;
use model\DBConnection;

class CustomerController
{
    public $customerDB;


    private $username;
    private $password;

    public function __construct()
    {
        $file = parse_ini_file("user_and_password.ini");
        $this->username = $file['user'];
        $this->password = $file['pass'];
        $connection = new DBConnection("mysql:host=localhost;dbname=MVC_Model", "$this->username", "$this->password");
        $this->customerDB = new CustomerDB($connection->connect());
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include "view/add.php";
        } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $address = $_POST['address'];

            $customer = new customer($name, $email, $address);
            $this->customerDB->creat($customer);
            $message = 'Customer created!';
            header('location: http://localhost/PhpStorm/ThucHanh/MVC_Model/');
        }
    }

    public function index()
    {
        $customers = $this->customerDB->getAll();
        include "view/list.php";
    }
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'];
            $customer = $this->customerDB->get($id);
            include 'view/delete.php';
        } else {
            $id = $_POST['id'];
            $this->customerDB->delete($id);
            header('Location: index.php');
        }
    }
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'];
            $customer = $this->customerDB->get($id);
            include 'view/edit.php';
        } else {
            $id = $_POST['id'];
            $customer = new Customer($_POST['name'], $_POST['email'], $_POST['address']);
            $this->customerDB->update($id, $customer);
            header('Location: index.php');
        }
    }
}