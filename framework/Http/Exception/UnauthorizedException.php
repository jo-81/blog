<?php

namespace Framework\Http\Exception;

class UnauthorizedException extends HttpException 
{
    protected $message = "Vous devez être connecté pour accéder à cette ressource.";
    
    public function getStatusCode(): int { return 401; }
}