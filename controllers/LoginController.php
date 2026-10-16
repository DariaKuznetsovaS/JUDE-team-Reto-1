<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/LoginModel.php';

class LoginController extends BaseController {
    
    public function index() {
        require "views/login.php";
    }
    
    public function show() {
        
    }
    
    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }
}