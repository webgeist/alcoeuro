<?php
$aMyMatches = array();
$aMyMatchesIds = array();
if( !Yii::app()->user->isGuest ){
	$iUserId = UserIdentity::getUserId( Yii::app()->user->name );
	// получим мои матчи
	$aMyMatches = UserMatch::model()->findAllByAttributes(
		array( 'user_id' => $iUserId ),
		array( 'index'	=> 'match_id' )
	);
	$aMyMatchesIds = array_keys( $aMyMatches );
}

if( in_array( $data->id, $aMyMatchesIds ) &&  !$aMyMatches[$data->id]->is_done ){
	$sClass = 'bred';
} else {
	$sClass = 'bgreen';
}

$aBet = array(
	0 => '',
	1 => ''
);

$oUserMatch = UserMatch::model()->findByAttributes(array('match_id' => $data->id));

if( $oUserMatch ){
	if( $oUserMatch->is_done ){
		
		$aBet[ $oUserMatch->bet ] = UserIdentity::getUserName( $oUserMatch->user_id);
	}
}

?>


<tr class="<?= $sClass?>">

	<td class="ar"><?php echo $data->id ?></td>
	<td class="ar"><?php echo $data->date ?></td>
	<td class="ar"><?php echo $aBet[0].' '. $data->home ?></td>
	<td class="ar"><?php echo $aBet[1].' '. $data->away ?></td>
</tr>
