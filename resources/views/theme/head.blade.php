@php
    if (!empty($user)) {
        \App::setLocale($user->lang);
    }
    $routeName = \Request::route()->getName();
@endphp

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="author" content="{{ !empty($settings['app_name']) ? $settings['app_name'] : env('APP_NAME') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($settings['app_name']) ? $settings['app_name'] : env('APP_NAME') }} </title>

    <meta name="title" content="{{ $settings['meta_seo_title'] }}">
    <meta name="keywords" content="{{ $settings['meta_seo_keyword'] }}">
    <meta name="description" content="{{ $settings['meta_seo_description'] }}">


    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $settings['meta_seo_title'] }}">
    <meta property="og:description" content="{{ $settings['meta_seo_description'] }}">
    <meta property="og:image" content="{{ asset(Storage::url('upload/seo')) . '/' . $settings['meta_seo_image'] }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $settings['meta_seo_title'] }}">
    <meta property="twitter:description" content="{{ $settings['meta_seo_description'] }}">
    <meta property="twitter:image"
        content="{{ asset(Storage::url('upload/seo')) . '/' . $settings['meta_seo_image'] }}">

    <!-- css file -->
    <link rel="stylesheet" href="{{ asset('assets/web/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/jquery-ui.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/web/css/ace-responsive-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/slider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />


    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/web/css/ud-custom-spacing.css') }}">

    <!-- Responsive stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/web/css/responsive.css') }}">
    <!-- Title -->
    <!-- Favicon -->
    <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
    <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" />
    <!-- Apple Touch Icon -->
    <link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
    <link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
    <link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
    <link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">


    @stack('css-page')


    <style>
        /* header.nav-homepage-style.at-home3 .ace-responsive-menu a.list-item.active { */
        .custom-active-style {
            color: #2ca58d !important;
            border: 1px solid #000;
            padding: 6px 24px;
            border-radius: 4px;
            /* background-color: #fff; */
            font-weight: 500;
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
            transition: all 0.3s ease;
        }
    </style>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">



</head>
