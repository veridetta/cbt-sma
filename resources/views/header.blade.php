<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 
<meta charset="utf-8">
<meta name="csrf-token" content="{{csrf_token()}}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="Basecampto.com">
<title>@yield('title')</title>
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
  /* Make the image fully responsive */
  .carousel-inner img {
    width: 100%;
    height: 100%;
  }
  #carouselExampleIndicators .nav a small
  {
      display: block;
  }
  #carouselExampleIndicators .nav
  {
      background: ;
      padding:8px;
  }
  #carouselExampleIndicators .nav li
  {
      padding:8px;
  }
  .nav-justified > li > a
  {
      color:black;
      border-radius: 0px;
  }
  .nav-pills>li[data-slide-to="0"].active  { background-color: #16a085; }
  .nav-pills>li[data-slide-to="1"].active  { background-color: #e67e22; }
  .nav-pills>li[data-slide-to="2"].active  { background-color: #2980b9; }

  .nav-pills>li[data-slide-to="0"].active a { color:white; }
  .nav-pills>li[data-slide-to="1"].active a { color:white; }
  .nav-pills>li[data-slide-to="2"].active a { color:white; }
  .footer{
    background-color:#3f3f3f;
    padding:26px;
  
  }
  .footer p, h1, h2, h3, a{
    color:white;
    margin-bottom:10px;
  }
  .footer p{
    margin-bottom:4px !important;
  }
  .row{
    margin:0px;
  }
  .tfull{
      min-height:100vh;
  }
    .hidden{display:none;}


  </style>
</head>
 
<body>
<nav class="navbar navbar-expand-lg navbar-info bg-info">
  <div class="container" id="navbarTogglerDemo01">
    <a class="navbar-brand text-white" href="{{ url('/') }}">BaseCamp Tryout</a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
    </ul>
    <form class="form-inline my-2 my-lg-0">
      @auth
        <a id="logoutheader" class="text-white btn btn-outline-primary my-2 my-sm-0" style="margin-right:6px;" href="{{ url('/redirect') }}">Profile</a>
          <a id="logoutheader" class="text-white btn btn-outline-danger my-2 my-sm-0" style="margin-right:6px;" href="{{ url('/logout') }}">Logout</a>
        @else
        <a class="text-white btn btn-outline-danger my-2 my-sm-0" style="margin-right:6px;" href="{{ route('login') }}">Login</a>
        <a class="text-white btn btn-outline-warning my-2 my-sm-0" href="{{ route('register') }}">Daftar</a>
      @endauth
    </form>
  </div>
</nav>