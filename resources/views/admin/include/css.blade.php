   <!-- favicon -->
   <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">

   <!-- Custom fonts for this template-->
   <link href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">


   <style>
       .image-upload .thumb .profilePicPreview {
           width: 100%;
           height: 310px;
           display: block;
           border: 3px solid #f1f1f1;
           box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
           border-radius: 10px;
           background-size: cover !important;
           background-position: top;
           background-repeat: no-repeat;
           position: relative;
           overflow: hidden;
       }

       .image-upload .thumb .avatar-edit label {
           text-align: center;
           line-height: 45px;
           font-size: 18px;
           cursor: pointer;
           padding: 2px 25px;
           width: 100%;
           border-radius: 5px;
           box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.16);
           transition: all 0.3s;
       }
   </style>