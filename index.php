<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>


    <title>Hello LLTMS!</title>
  </head>
  <body class="bg-secondary">
    <div class="container">
    <h1 class="font-weight-bold text-light">Event scheduler</h1>
    <form id='event_form'>
    <div class="row" >
        <div class="col col-lg-6 bg-light border border-primary">

  <div class="form-group pt-4">
  <label for="event_name">Event name:</label>
  <input class="form-control" id="event_name" name="event_name" type="text" placeholder="Enter Name" form="event_form">
  </div>
    <div class="form-group">
    <label for="exampleInputEmail1">Your email:</label>
    <input type="email" class="form-control"  name="event_mail" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" form="event_form">
  </div>

  <div class="form-group">
    <label for="event_phone">Phone number:</label>
    <input type="tel" class="form-control" id="event_phone" aria-describedby="phone_help" name="event_phone">
    <small id="phone_help" class="form-text text-muted"># 6 or more digits.</small>

  </div>


  <div class="form-group">
  <label for="event_time">Event Time:</label>
  <input type="time" id="event_time" class="form-control" name="event_time">
    </div>
    <div class="form-group">
  <label for="event_day set-today">Event day:</label>
  <input type="date" id="event_day" class="form-control" name="event_day">
  </div>
       <div class="form-group pt-3">
       <div class="row">
       <div class="col col-lg-3">
  <button type="submit" class="btn btn-success">Submit</button>
       </div>
       <div class="col col-lg-9">

  <h4 class="d-none" id="status_msg">a</h4>
       </div>
       </div>
</div>
       </div>
        </div>
</div>
</form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- <script src="js/slimjquery.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" ></script>
    <script type="text/javascript">
    
    window.onload= function() {
        document.getElementById('event_day').value=(new Date()).toISOString().substr(0,10);
        }


$(document).ready(function() {

    $('#event_form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: 'quickstart.php',
            data: $(this).serialize(),
            success: function(res)
            {
              var response = JSON.parse(res);

              var status_field = document.getElementById('status_msg');

              status_field.classList.remove("d-none");

              status_field.classList.contains("text-danger") ? status_field.classList.remove("text-danger"): null;
              
              var colorClass = response.error_status==='0' ? "text-danger" : "text-success";

              status_field.classList.add(colorClass);

              status_field.innerHTML  = response.data;

           
          
           }
       });
     });
});
</script>
  </body>
</html>