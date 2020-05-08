<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


  </head>
  <body>
    <div class="container">
      <div class="accordion" id="accordionExample">

        <?php
        // print_r($faq);
        // die();
         for ($i=0; $i < count($faq); $i++) { ?>
          <div class="card">
            <div class="card-header" id="headingOne<?php print_r($i); ?>">
              <h2 class="mb-0">
                <button style="text-decoration: none;  color: black;   font-size: 1em;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne<?php print_r($i); ?>" aria-expanded="true" aria-controls="collapseOne">
                  <?php print_r($faq[$i]['pertanyaan']) ?>
                </button>
              </h2>
            </div>

            <div id="collapseOne<?php print_r($i); ?>" class="collapse " aria-labelledby="headingOne<?php print_r($i); ?>" data-parent="#accordionExample">
              <div class="card-body">
                <?php print_r($faq[$i]['jawaban']) ?>

              </div>
            </div>
          </div>

      <?php  } ?>



        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
