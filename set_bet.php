   <?php include("header.php") ?>
   <?php require_once("config.php") ?>

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
  </head>

  <body class="bg-light">
      <?php require_once("top_nav.php"); ?>

    <div class="container">
      <img src="http://placehold.it/837x250" class="img-fluid" alt="Responsive image">

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your bets</span>
            <span class="badge badge-secondary badge-pill">3</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
             <?php previous_bets(); ?>
            </li>
           
            <li class="list-group-item d-flex justify-content-between">
              <span>Total (USD)</span>
              <strong>$20</strong>
            </li>
          </ul>

          
        </div>
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Billing address</h4>
          <form class="needs-validation" method="post" novalidate>
            <?php place_bet(); ?>

            

            


            <div class="row">
              <div class="col-md-5 mb-3">
                <label for="country">Country</label>
                <div class="form-group">
    <label for="exampleFormControlSelect1">Example select</label>
    <select class="form-control" id="exampleFormControlSelect1" name="option">
      <?php bet_options($_GET['id']); ?>
  </div>
                <div class="invalid-feedback">
                  Please select a valid outcome
                </div>
              </div>
              
              <div class="col-md-3 mb-3">
                <label for="amt">Bet Amount</label>
                <input type="text" class="form-control" id="amt" name="amt" placeholder="Enter Bet Amount..." required>
                <div class="invalid-feedback">
                  Bet Amount required.
                </div>
              </div>
            </div>
            </div>


           

            <div class="d-block my-3">
              
            </div>
            <div class="row">
              
              
            </div>
            <div class="row">
              
             
            </div>
            <hr class="mb-4">
            
            <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Place Bet</button>
          </form>
        </div>
      </div>

     
    </div>


    <div class="container">
      <?php include 'footer.php'; ?>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
    <script src="../../../../assets/js/vendor/holder.min.js"></script>
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>
