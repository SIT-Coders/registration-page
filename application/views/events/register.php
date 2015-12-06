<html>
<head>
	
</head>
<body>
	<form action="<?php echo site_url('events/enroll'); ?>" method="post">
	<fieldset>
	   <legend>Registration information:</legend>
	   Name:<br>
	   <input type="text" name="name">
	   <br>
	   Email:<br>
	   <input type="text" name="email">
	   <br>
	   Phone:<br>
	   <input type="text" name="phone">
	   <br>
	   Event:<br>
	   <select name="event">
	   <?php foreach($events->result() as $event): ?>
	     <option value="<?=$event->eventcode?>"><?=$event->eventname?></option>
	   <?php endforeach; ?>
	   </select>
	   <br><br>
	   <input type="submit" value="Submit">
	</fieldset>
	</form>
</body>
</html>