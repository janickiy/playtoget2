<?php

$tpl->assign('NUMBERPLAYGROUNDS', SportBlocks::getNumberSportBlocks('playground'));
$tpl->assign('NUMBERFITNESS', SportBlocks::getNumberSportBlocks('fitness'));
$tpl->assign('NUMBERSHOPS', SportBlocks::getNumberSportBlocks('shop'));

foreach(SportBlocks::lastSportBlock('fitness', 3) as $row){
	$rowBlock = $tpl->fetch('row_right_feetness');
	$rowBlock->assign('ID', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$rowBlock->assign('ADDRESS', $row['place']);
	$rowBlock->assign('LAST_PIC', core::documentparser()->sportblockAvatar($row['avatar']));
	$tpl->assign('row_right_feetness', $rowBlock);	
}

foreach(SportBlocks::lastSportBlock('shop', 3) as $row){
	$rowBlock = $tpl->fetch('row_right_shop');
	$rowBlock->assign('ID', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$rowBlock->assign('ADDRESS', $row['place']);
	$rowBlock->assign('LAST_PIC', core::documentparser()->sportblockAvatar($row['avatar']));
	$tpl->assign('row_right_shop', $rowBlock);	
}

foreach(SportBlocks::lastSportBlock('playground', 3) as $row){
	$rowBlock = $tpl->fetch('row_right_playground');
	$rowBlock->assign('ID', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$rowBlock->assign('ADDRESS', $row['place']);	
	$rowBlock->assign('LAST_PIC', core::documentparser()->sportblockAvatar($row['avatar']));
	$tpl->assign('row_right_playground', $rowBlock);	
}