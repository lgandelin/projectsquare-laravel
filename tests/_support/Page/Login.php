<?php

namespace Page;

class Login
{
    public static $URI = '/login';
    public static $title = 'Se connecter';
    public static $emailField = 'form input[name=email]';
    public static $passwordField = 'form input[name=password]';
    public static $submitButton = 'form button[type=submit]';
    public static $errorLogin = 'Identifiant ou mot de passe incorrect';
}