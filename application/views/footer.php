<div class="container">
    <footer>
            <p>&copy; 2015 Company, Inc.</p>
    </footer>
</div>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="<?php echo base_url(); ?>lib/js/bootstrap.min.js"></script>


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo base_url(); ?>lib/assets/js/ie10-viewport-bug-workaround.js"></script>

<!-- Login JS -->
<script src="<?php echo base_url(); ?>lib/js/baseJs.js"></script>

<!-- Full Calendar plugin -->

<script src="<?php echo base_url(); ?>lib/fullCalendar/moment.js"></script>
<script src="<?php echo base_url(); ?>lib/fullCalendar/fullcalendar.min.js"></script>

<!--  Table Sorter plugin -->
<script src="<?php echo base_url(); ?>lib/tableSorter/jquery.tablesorter.js"></script> 

<!-- Bootstrap DatTimepicker plugin (Moment.js required) -->
<script src="<?php echo base_url(); ?>lib/js/bootstrap-datetimepicker.js"></script>

<!-- Boostrap Toogle plugin -->
<script src="<?php echo base_url(); ?>lib/js/bootstrap-toggle.js"></script>
<!-- SummerNote plugin -->
<script src="<?php echo base_url(); ?>lib/summernote/summernote.js"></script>
<!-- Select2 plugin -->
<script src="<?php echo base_url(); ?>lib/select2/dist/js/select2.min.js"></script>
	
<!-- Box Message  -->
<?php if (isset($messageData) && count($messageData) > 0) { ?>
<script>
$(document).ready(function() {
<?php for ($i=0;$i<count($messageData);$i++) { ?>
	testBox('<?php echo $messageData[$i]['type']; ?>','<?php echo $messageData[$i]['content']; ?>',<?php echo $messageData[$i]['remainTime']; ?>);
<?php } ?>	
	// Generated from session 'boxMsg'
});
</script>
<?php } ?>
</body>
</html>