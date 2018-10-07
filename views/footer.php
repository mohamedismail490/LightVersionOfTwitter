  <footer class="footer">
  
  	<div class="container">
  	
  		<p>&copy; My Website 2018</p>

        <?php

            if (isset($_SESSION['id'])){

                ?>

                <input type="hidden" id="session_user_id" value="<?php echo $_SESSION['id']; ?>">

        <?php
            }

        ?>
  	
  	</div>
  	
  </footer>
   
   <script type="text/javascript" src="jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    
    
    
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalTitle">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="alert alert-danger" id="loginAlert"></div>
		  <form>
		  	  <input type="hidden" id="loginActive" name="loginActive" value="1">
			  <fieldset class="form-group">
				<label for="email">Email address</label>
				<input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
				<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </fieldset>
			  <fieldset class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" id="password" placeholder="Password">
			  </fieldset>
		  </form>
		  
      </div>
      <div class="modal-footer">
       <a id="toggleLogin" >Sign up</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="loginSignupButton" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</div>
   
<script type="text/javascript">

	$("#toggleLogin").click(function(){
		
		if ($("#loginActive").val()== "1") {
		
			$("#loginActive").val("0");
			$("#loginModalTitle").html("Sign Up");
			$("#loginSignupButton").html("Sign Up");
			$("#toggleLogin").html("Login");
			
		}
		else {
			
			$("#loginActive").val("1");
			$("#loginModalTitle").html("Login");
			$("#loginSignupButton").html("Login");
			$("#toggleLogin").html("Sign Up");
			
		}
	});
	
	$("#loginSignupButton").click(function(){
		
		$.ajax({
            type: "POST",
            url: "actions.php?action=loginSignup",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val(),
            success: function(result) {
				
				if (result == 1) {
					window.location.assign("http://localhost:8080/c_w_d_c/14-twitter/");
					
				}
				else
				{
					$("#loginAlert").html(result).show();
				}
			
		}})
		
	});



		
		$(".toggleFollow").click(function(){

			var id = $(this).attr("data-userId");


		$.ajax({
            type: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + id,
            success: function(result) {

				//alert(result);

				if (result == 1) {

					$("a[data-userId='" + id + "']").html("Follow");

				} else if (result == 2) {

					$("a[data-userId='" + id + "']").html("Unfollow");

				}

		}})

	});
	
	$("#postTweetButton").click(function(){
		
		$.ajax({
            type: "POST",
            url: "actions.php?action=postTweet",
            data: "tweetContent=" + $("#tweetContent").val(),
            success: function(result) {
				
				//alert(result);
				
				if (result == 1) {
					
					$("#tweetSuccess").show();
					$("#tweetFail").hide();
					
				} else if (result != "") {
					
					$("#tweetFail").html(result).show();
					$("#tweetSuccess").hide();
					
				}
			
		}})
		
	});
		
	


</script>
    
    
    
  </body>
</html>