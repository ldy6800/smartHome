<link href="/_include/FlipClock/compiled/flipclock.css" rel="stylesheet">
        <!-- Our Work Section -->
        <div id="work" class="page">
                        <div class="container">
                                <!-- Title Page -->
                                <div class="row">
                                        <div class="span12">
                                                <div class="title-page">
                                                        <h2 class="title">Electric Charges</h2>
                                                </div>
                                        </div>
                                </div>
                                <!-- End Title Page -->
								<div class="row" style="text-align: center;">
											<div class="fee-div" style="display: inline-block; width: auto;"></div>
								</div>
                        </div>
                </div>
                <!-- End Our Work Section -->

            </div>
        </div>
        <!-- End Our Work Section -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/_include/FlipClock/compiled/flipclock.js"></script>
<script type="text/javascript">

	var ind;
        function getCharge(){
            $.ajax ({
                url: "/index.php/system/jsonGetCurrentCharge",
                dataType: "json",
                cache: false,
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                success: function(data){
                    console.log(data);
                    ind.setValue(Math.floor(data) + "₩");
                }
            });

        }

	$(document).ready(function(){
		
		timer = setInterval(getCharge, 1000);
		ind = new FlipClock($('.fee-div'), Math.floor(getCharge()) + "₩", {
			clockFace: 'Counter',
			minimumDigits: 6
		});

		won = $('.flip')
		won[won.length-1].setAttribute('style', 'width: 65px'); 
	});
</script>
