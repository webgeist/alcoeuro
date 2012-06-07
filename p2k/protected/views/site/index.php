<?php $this->pageTitle=Yii::app()->name; ?>

<br/>
<br/>
<br/>
<?php 
echo "Hi $sUserName";
?>

<h1>Таблица</h1>

<table class="table">
	<tr>
		<th>id</th>
		<th>Дата</th>
		<th>Хозяева</th>
		<th>Гости</th>
	</tr>
<?php 

$this->widget('bootstrap.widgets.BootThumbs', array(
    'dataProvider'=>$oMatchList,
    'template'=>'{items}<tr><td colspan="10">{pager}</td></tr>',
    'itemView'=>'_match',
    // Remove the existing tooltips and rebind the plugin after each ajax-call.
    'afterAjaxUpdate'=>"js:function() {
        jQuery('.tooltip').remove();
        jQuery('a[rel=tooltip]').tooltip();
    }",
)); 

?>
</table>