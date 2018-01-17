<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= h($this->fetch('title')) ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<?= $this->Html->css('bootstrap.min') ?>
<?= $this->Html->css('font-awesome.min') ?>
<?= $this->Html->css('ionicons.min') ?>
<?= $this->Html->css('AdminLTE.min') ?>
<?= $this->Html->css('_all-skins.min') ?>
<?= $this->Html->css('bootstrap-datepicker.min') ?>
<?= $this->Html->css('bootstrap-timepicker.min') ?>
<?= $this->Html->css('jquery.qtip.min') ?>
<?= $this->Html->css('bootstrap-datetimepicker.min') ?>
<?= $this->Html->css('bootstrap-multiselect') ?>

<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
<style>

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .error{
        color: red;
    }
</style>
