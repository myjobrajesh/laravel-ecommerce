<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{!! asset('/adm/images/slider/fav-icon.png') !!}" />

    <title>Store Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('/adm/css/bootstrap.min.css') }}">
    <!-- Bootstrap core mdb.css -->
    <link rel="stylesheet" href="{{ asset('/adm/css/mdb.css') }}">
    <!-- Include admin.less file -->
    <link rel="stylesheet" href="{{ asset('/adm/less/admin.less') }}">
    <link rel="stylesheet" href="{{ asset('/adm/less/app.less') }}">
    <!-- Include app.scss file -->
    <link rel="stylesheet" href="{{ asset('/adm/sass/app.scss') }}">
    <!-- Include sweet alert file -->
    <link rel="stylesheet" href="{{ asset('/adm/css/sweetalert.css') }}">
    <!-- Include lity light-tbox file -->
    <link rel="stylesheet" href="{{ asset('/adm/css/lity.css') }}">
    <!-- Include drop-zone file -->
    <link rel="stylesheet" href="{{ asset('/adm/css/dropzone.css') }}">
    <!-- Include Froala Editor style. -->
    <link href="{{ asset('/adm/css/froala_editor.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Material Design Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" >

</head>
<body>

@include('admin.pages.partials.nav')
@include('admin.pages.partials.side-nav')

@yield('content')

<!-- jQuery -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/jquery.js') }}"></script>
<!-- Bootstrap core JavaScript -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/bootstrap.min.js') }}"></script>
<!-- MDB core JavaScript -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/mdb.js') }}"></script>
<!-- Include sweet-alert.js file -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/sweetalert.js') }}"></script>
<!-- Include main app.js file -->
<script type="application/javascript" src="{{ asset('/adm/js/app.js') }}"></script>
<!-- Include lity light-box js file -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/lity.js') }}"></script>
<!-- Include moment.js for chart.js -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/moment.js') }}"></script>
<!-- Chart.js plugin -->
<script type="application/javascript" src="{{ asset('/adm/js/libs/Chart.js') }}"></script>

@yield('footer')

@include('admin.pages.partials.flash')

</body>
</html>
