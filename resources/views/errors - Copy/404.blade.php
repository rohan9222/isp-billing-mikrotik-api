@extends('errors::minimal')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message',  $exception->getMessage() ?? 'The page you are looking for doesn’t exist or has been moved.')
