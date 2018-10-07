<div class="container mainContainer">

	<div class="row">
	  <div class="col-8">
	  	
	  		<h2>Recent Tweets</h2>
	  		
	  		<?php
		  
		  		displayTweets('public');
		  
		  	?>
	  	
	  </div>
	  <div class="col-4">
		
			<?php
		  
		  		displaySearch();
		  
		  	?>
		  	<hr>
		  	<?php
		  
		  		displayTweetBox();
		  
		  	?>
		
		</div>
	</div>

</div>