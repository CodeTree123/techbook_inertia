   <!-- favicon -->
   <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
       h1,
       h2,
       h6,
       h4,
       h5,
       h6 {
           color: black;
       }

       a,
       a:hover,
       a:focus,
       a:active {
           text-decoration: none;
           outline: none;
       }

       ul {
           margin: 0;
           padding: 0;
           list-style: none;
       }

       header {
           position: relative;
           width: 100%;
           background: white;
           z-index: 99;
           /* box-shadow: 0 4px 6px green; */
           /* border-bottom: 1px solid #AFE1AF; */
           ;
           /* border-top: 2px solid #7EC8E3; */
           ;
       }

       /*---------header close----------*/
       /*-----------header-top------------*/
       .header-top {
           padding: 2px 0px 5px 0px;
           background: skyblue;
       }

       .left_info ul li {
           display: inline-block;
           margin-right: 30px;
       }

       .left_info a {
           color: black;
           font-size: 14px;
           font-weight: 500;
       }

       .left_info i.fa {
           font-size: 14px;
           color: black;
           padding-right: 10px;
       }

       .right_info p {
           color: black;
           font-size: 14px;
           font-weight: 500;
           line-height: 20px;
           margin: 0;
       }

       .left_info {
           text-align: right;
       }

       .right_info i.fa {
           font-size: 14px;
           margin-right: 10px;
           position: relative;
           bottom: -4px;
       }

       header i.fa.fa-mobile {
           bottom: -4px;
           font-size: 26px;
           position: relative;
           border: 40px;
           font-weight: 500;
       }

       .left_info ul li:last-child {
           margin: 0px !IMPORTANT;
       }

       .header-main {
           padding: 10px;

       }

       nav#cssmenu ul {
           float: right;
           margin-top: 13px;
       }

       .logo {
           position: relative;
           z-index: 123;
           font: 18px verdana;
           float: left;
       }

       .logo img {
           max-width: 100px;

       }




       .logo a {
           color: #6DDB07;
       }

       #cssmenu,
       #cssmenu ul,
       #cssmenu ul li,
       #cssmenu ul li a,
       #cssmenu #head-mobile {
           border: 0;
           list-style: none;
           line-height: 1;
           display: block;
           position: relative;
           -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
           box-sizing: border-box
       }

       #cssmenu:after,
       #cssmenu>ul:after {
           content: ".";
           display: block;
           clear: both;
           visibility: hidden;
           line-height: 0;
           height: 0
       }

       #cssmenu #head-mobile {
           display: none
       }

       #cssmenu>ul>li {
           float: left
       }

       #cssmenu>ul>li>a {
           padding: 9px 17px;
           font-size: 15px;
           letter-spacing: 1px;
           text-decoration: none;
           color: black;
       }

       #cssmenu>ul>li:hover>a,
       #cssmenu ul li.active a {
           color: black;
       }

       #cssmenu>ul>li:hover,
       #cssmenu ul li.active:hover,
       #cssmenu ul li.active,
       #cssmenu ul li.has-sub.active:hover {
           background: white !important;
           -webkit-transition: background .3s ease;
           -ms-transition: background .3s ease;
           transition: background .3s ease;

       }

       #cssmenu>ul>li.has-sub>a {
           padding-right: 30px
       }

       #cssmenu>ul>li.has-sub>a:after {
           position: absolute;
           top: 17px;
           right: 11px;
           width: 8px;
           height: 2px;
           display: block;
           background: black;
           content: ''
       }

       #cssmenu>ul>li.has-sub>a:before {
           position: absolute;
           top: 14px;
           right: 14px;
           display: block;
           width: 2px;
           height: 8px;
           background: black;
           content: '';
           -webkit-transition: all .25s ease;
           -ms-transition: all .25s ease;
           transition: all .25s ease;
       }

       #cssmenu>ul>li.has-sub:hover>a:before {
           top: 23px;
           height: 0
       }

       #cssmenu ul ul {
           position: absolute;
           left: -9999px;
           margin-top: 0;

       }

       #cssmenu ul ul li {
           height: 0;
           -webkit-transition: all .25s ease;
           -ms-transition: all .25s ease;
           background: #7EC8E3;
           transition: all .25s ease;
           /* border: 1px solid #7EC8E3; */
           border: 1px solid green #CCC;


       }

       #cssmenu ul ul li:hover {
           background-color: #AFE1AF;
           /* border: 1px solid yellow; */
           /* border-bottom: 1px solid #7EC8E3; */

       }

       #cssmenu li:hover>ul {
           left: auto
       }

       #cssmenu li:hover>ul>li {
           height: 35px
       }

       #cssmenu ul ul ul {
           margin-left: 100%;
           top: 0
       }

       #cssmenu ul ul li a {
           border-bottom: 1px solid white;
           padding: 11px 15px;
           width: 190px;
           font-size: 18px;
           text-decoration: none;
           color: white;
           font-weight: 400;
       }

       #cssmenu ul ul li:last-child>a,
       #cssmenu ul ul li.last-item>a {
           border-bottom: 0
       }

       #cssmenu ul ul li:hover>a,
       #cssmenu ul ul li a:hover {
           color: black
       }

       #cssmenu ul ul li.has-sub>a:after {
           position: absolute;
           top: 16px;
           right: 11px;
           width: 8px;
           height: 2px;
           display: block;
           background: white;
           content: ''
       }

       #cssmenu ul ul li.has-sub>a:before {
           position: absolute;
           top: 13px;
           right: 14px;
           display: block;
           width: 2px;
           height: 8px;
           background: white;
           content: '';
           -webkit-transition: all .25s ease;
           -ms-transition: all .25s ease;
           transition: all .25s ease
       }

       #cssmenu ul ul>li.has-sub:hover>a:before {
           top: 17px;
           height: 0
       }



       @media screen and (max-width:991px) {
           .logo {
               position: absolute;
               top: 0;
               left: 0;
               width: 100%;
               height: 46px;
               text-align: center;
               padding: 10px 0 0 0;
               float: none
           }

           .logo2 {
               display: none
           }

           nav {
               width: 100%;
           }

           #cssmenu {
               width: 100%
           }

           #cssmenu ul {
               width: 100%;
               display: none
           }

           #cssmenu ul li {
               width: 100%;
               border-top: 1px solid #444;
               float: left;
           }

           #cssmenu ul li:hover {
               background: transparent !important;
           }

           #cssmenu ul ul li,
           #cssmenu li:hover>ul>li {
               height: auto
           }

           #cssmenu ul li a,
           #cssmenu ul ul li a {
               width: 100%;
               border-bottom: 0
           }

           #cssmenu ul ul li a {
               padding-left: 25px
           }

           #cssmenu ul ul li {
               background: #333 !important;
           }

           #cssmenu ul ul li:hover {
               background: #363636 !important
           }

           #cssmenu ul ul ul li a {
               padding-left: 35px
           }

           #cssmenu ul ul li a {
               color: black;
               background: none
           }

           #cssmenu ul ul li:hover>a,
           #cssmenu ul ul li.active>a {
               color: black
           }

           #cssmenu ul ul,
           #cssmenu ul ul ul {
               position: relative;
               left: 0;
               width: 100%;
               margin: 0;
               text-align: left
           }

           #cssmenu>ul>li.has-sub>a:after,
           #cssmenu>ul>li.has-sub>a:before,
           #cssmenu ul ul>li.has-sub>a:after,
           #cssmenu ul ul>li.has-sub>a:before {
               display: none
           }

           #cssmenu #head-mobile {
               display: block;
               padding: 23px;
               color: black;
               font-size: 12px;
               font-weight: 700
           }

           .button {
               width: 55px;
               height: 46px;
               position: absolute;
               right: 0;
               top: 0;
               cursor: pointer;
               z-index: 12399994;
           }

           .button:after {
               position: absolute;
               top: 22px;
               right: 20px;
               display: block;
               height: 8px;
               width: 20px;
               border-top: 2px solid black;
               border-bottom: 2px solid black;
               content: ''
           }

           .button:before {
               -webkit-transition: all .3s ease;
               -ms-transition: all .3s ease;
               transition: all .3s ease;
               position: absolute;
               top: 16px;
               right: 20px;
               display: block;
               height: 2px;
               width: 20px;
               background: black;
               content: ''
           }

           .button.menu-opened:after {
               -webkit-transition: all .3s ease;
               -ms-transition: all .3s ease;
               transition: all .3s ease;
               top: 23px;
               border: 0;
               height: 2px;
               width: 19px;
               background: black;
               -webkit-transform: rotate(45deg);
               -moz-transform: rotate(45deg);
               -ms-transform: rotate(45deg);
               -o-transform: rotate(45deg);
               transform: rotate(45deg)
           }

           .button.menu-opened:before {
               top: 23px;
               background: black;
               width: 19px;
               -webkit-transform: rotate(-45deg);
               -moz-transform: rotate(-45deg);
               -ms-transform: rotate(-45deg);
               -o-transform: rotate(-45deg);
               transform: rotate(-45deg)
           }

           #cssmenu .submenu-button {
               position: absolute;
               z-index: 99;
               right: 0;
               top: 0;
               display: block;
               border-left: 1px solid #444;
               height: 46px;
               width: 46px;
               cursor: pointer
           }

           #cssmenu .submenu-button.submenu-opened {
               background: black;
           }

           #cssmenu ul ul .submenu-button {
               height: 34px;
               width: 34px
           }

           #cssmenu .submenu-button:after {
               position: absolute;
               top: 22px;
               right: 19px;
               width: 8px;
               height: 2px;
               display: block;
               background: black;
               content: ''
           }

           #cssmenu ul ul .submenu-button:after {
               top: 15px;
               right: 13px
           }

           #cssmenu .submenu-button.submenu-opened:after {
               background: #fff
           }

           #cssmenu .submenu-button:before {
               position: absolute;
               top: 19px;
               right: 22px;
               display: block;
               width: 2px;
               height: 8px;
               background: black;
               content: ''
           }

           #cssmenu ul ul .submenu-button:before {
               top: 12px;
               right: 16px
           }

           #cssmenu .submenu-button.submenu-opened:before {
               display: none
           }

           #cssmenu ul ul ul li.active a {
               border-left: none
           }

           #cssmenu>ul>li.has-sub>ul>li.active>a,
           #cssmenu>ul ul>li.has-sub>ul>li.active>a {
               border-top: none
           }
       }

       .form-control {
           /* font-size: 12px; */
           padding: 1px;
           border-radius: 3px;
           border: 1px solid skyblue;
           /* background-color: rgb(241, 235, 235); */
           box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
           /* Adding a subtle shadow */
       }

       .form-select {
           padding: 3px;
           border-radius: 0px;
           border-radius: 3px;
           border: 1px solid skyblue;
           background-color: WHITE;
       }

       .whole-card {
           background: white;
       }



       /* #general-notes-table td,
       th {
           text-align: center;
       } */

       #dispatch-notes-table td,
       th {
           text-align: center;
       }

       #billing-notes-table td,
       th {
           text-align: center;
       }

       #techSupport-notes-table td,
       th {
           text-align: center;
       }

       #closeout-notes-table td,
       th {
           text-align: center;
       }

       .nav-link {
           transition: all 0.3s ease-in-out;
           color: black;
           padding: 10px;
           margin: 10px;
       }

       .nav-link:hover {
           background-color: white;
           color: black;
           transform: translateY(-3px);
       }

       @media screen and (max-width: 767px) {
           .nav-tabs {

               margin-top: -20px;
           }

           #my-table {
               width: 100%;
               border-collapse: collapse;
           }

           #my-table td {
               padding: 10px;
               border: 1px solid black;
           }

       }

       /* for recent work order worked order hover section */
       .filerPageOrderId {
           cursor: pointer;
       }

       .filerPageOrderId:hover {
           text-decoration: underline;
           color: blue;
       }


       table.dataTable>thead>tr>th,
       table.dataTable>thead>transliterator_create_inverse {
           padding: 10px;
           border-bottom: 1px solid rgba(0, 0, 0, 0.3);
           border-top: 1px solid rgba(0, 0, 0, 0.3);
       }



       .dataTables_wrapper .dataTables_length select {
           padding: 5px;

           margin-bottom: 20px;
       }

       table.dataTable th,
       table.dataTable td {
           box-sizing: content-box;
           border: 1px solid rgba(0, 0, 0, 0.3);
       }

       /* Add your custom CSS styles here */
       .table-bordered {
           width: 100%;
           border-collapse: collapse;
       }

       .table-bordered th,
       .table-bordered td {
           border: 1px solid #ddd;
           padding: 8px;
           text-align: left;
       }

       .table-bordered th {
           background-color: #f2f2f2;
       }

       .table-bordered tbody tr:nth-child(even) {
           background-color: #f2f2f2;
       }



       /* for summernote */

       .note-editor.fullscreen {
           background-color: white !important;
       }

       .stage {
            border: 1px solid #AFE1AF !important;
            font-weight: 700;
        }

        .stage-primary {
            background-color: #AFE1AF !important;
            border: #AFE1AF;
        }

        .tab {
            border: 1px solid #9BCFF5;
            font-weight: 700;
            transition: 0.2s;
        }

        .tab-primary {
            background-color: #9BCFF5 !important;
        }
   </style>
