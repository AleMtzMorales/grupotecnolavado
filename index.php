<!DOCTYPE html>
<html>

<head>
  <title>Lavanderia tecnolavado</title>
  <meta charset="utf-8">
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="css/menu.css">

  <!--For sliders-->
  <link rel="stylesheet" href="css/flexslider.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script src="js/jquery.flexslider.js"></script>
  <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $('.flexslider').flexslider({
        touch: true,
        pauseOnAction: false,
        pauseOnHover: false,
      });
    });
  </script>






</head>

<body>

  <!-- Begin Navbar -->
  <div id="nav">
    <div class="navbar  navbar-default navbar-fixed-top " data-spy="affix" data-offset-top="100">
      <div class="container ">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="" href=""><img class="img-responsive logo img-thumbnail align-top" src="img/imagen23.jpeg" alt="" width="120px" height="50px"></a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            </li>
            <li class="dropdown">
            <li>
              <a href="login.php">
                <button class="btn btn-primary">
                  Iniciar sesión <span class="glyphicon glyphicon-log-in"></span>
                </button>
              </a>
            </li>
          </ul>
        </div>
        <!--/.nav-collapse -->
      </div>
      <!--/.contatiner -->
    </div>


      <div class="flexslider">
        <ul class="slides">
          <li>
            <img src="img/sliders/1.jpg" alt="">
            <section class="flex-caption">
              <p>LOREM IPSUM 1</p>
            </section>
          </li>
          <li>
            <img src="img/sliders/2.jpg" alt="">
            <section class="flex-caption">
              <p>LOREM IPSUM 2</p>
            </section>
          </li>
          <li>
            <img src="img/sliders/3.jpg" alt="">
            <section class="flex-caption">
              <p>LOREM IPSUM 3</p>
            </section>
          </li>
        </ul>
      </div>


      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi ipsum suscipit assumenda sunt, doloremque esse aperiam rerum quisquam quo expedita exercitationem maiores minus deleniti eum possimus voluptatem ipsa. Voluptas, autem.</p>

  </div>
  </div>
  </div>


</body>

</html>