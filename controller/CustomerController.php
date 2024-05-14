<?php
namespace Controller;
use Model\Customer;

class CustomerController {

    public static function index(){
        return Customer::get();
    }
    
    public static function getOne($id){
        return Customer::get($id);
    }
    
    public static function store($nome, $cognome, $gender){
        return Customer::store($nome, $cognome, $gender);
    }
    
}