<!-- js placed at the end of the document so the pages load faster -->
<script src="libraries/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="libraries/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="libraries/js/jquery.scrollTo.min.js"></script>
<!--script src="libraries/js/jquery.nicescroll.js" type="text/javascript"></script-->
<script src="libraries/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="libraries/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="libraries/js/owl.carousel.js"></script>
<script src="libraries/js/jquery.customSelect.min.js"></script>
<script src="libraries/js/respond.min.js"></script>

<!--right slidebar-->
<script src="libraries/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="libraries/js/common-scripts.js"></script>

<!--script for this page-->
<script src="libraries/js/sparkline-chart.js"></script>
<script src="libraries/js/easy-pie-chart.js"></script>
<script src="libraries/js/count.js"></script>

<footer class="site-footer">
	<div class="text-center">
		&#169; <script>document.write(new Date().getFullYear());</script> | Lý Quỳnh
	</div>
</footer>

<script>
//owl carousel
$(document).ready(function () {
    $("#owl-demo").owlCarousel({
        navigation: true,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        autoPlay: true

    });
});

//custom select box
$(function () {
    $('select.styled').customSelect();
});
</script>