<?php
$tutorial_tid = 6;

$user_condition = array(
	"admin" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),	
	"user1" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),	
	"rob" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),	
	"kyle" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),			
	"P01" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),
	"P02" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 3),
		"cond3" => array("tid" => 3, "iid" => 2),
		"condA" => array("tid" => 4, "iid" => 2),
		"condB" => array("tid" => 5, "iid" => 1)
	),
	"P03" => array(
		"cond1" => array("tid" => 1, "iid" => 2),
		"cond2" => array("tid" => 2, "iid" => 1),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 5, "iid" => 1),
		"condB" => array("tid" => 4, "iid" => 2)
	),
	"P04" => array(
		"cond1" => array("tid" => 1, "iid" => 2),
		"cond2" => array("tid" => 2, "iid" => 3),
		"cond3" => array("tid" => 3, "iid" => 1),
		"condA" => array("tid" => 5, "iid" => 2),
		"condB" => array("tid" => 4, "iid" => 1)
	),
	"P05" => array(
		"cond1" => array("tid" => 1, "iid" => 3),
		"cond2" => array("tid" => 2, "iid" => 1),
		"cond3" => array("tid" => 3, "iid" => 2),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),
	"P06" => array(
		"cond1" => array("tid" => 1, "iid" => 3),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 1),
		"condA" => array("tid" => 4, "iid" => 2),
		"condB" => array("tid" => 5, "iid" => 1)
	),
	"P07" => array(
		"cond1" => array("tid" => 3, "iid" => 3),
		"cond2" => array("tid" => 1, "iid" => 1),
		"cond3" => array("tid" => 2, "iid" => 2),
		"condA" => array("tid" => 5, "iid" => 1),
		"condB" => array("tid" => 4, "iid" => 2)
	),
	"P08" => array(
		"cond1" => array("tid" => 3, "iid" => 2),
		"cond2" => array("tid" => 1, "iid" => 1),
		"cond3" => array("tid" => 2, "iid" => 3),
		"condA" => array("tid" => 5, "iid" => 2),
		"condB" => array("tid" => 4, "iid" => 1)
	),
	"P09" => array(
		"cond1" => array("tid" => 3, "iid" => 3),
		"cond2" => array("tid" => 1, "iid" => 2),
		"cond3" => array("tid" => 2, "iid" => 1),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),
	"P10" => array(
		"cond1" => array("tid" => 3, "iid" => 1),
		"cond2" => array("tid" => 1, "iid" => 2),
		"cond3" => array("tid" => 2, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 2),
		"condB" => array("tid" => 5, "iid" => 1)
	),
	"P11" => array(
		"cond1" => array("tid" => 3, "iid" => 2),
		"cond2" => array("tid" => 1, "iid" => 3),
		"cond3" => array("tid" => 2, "iid" => 1),
		"condA" => array("tid" => 5, "iid" => 1),
		"condB" => array("tid" => 4, "iid" => 2)
	),
	"P12" => array(
		"cond1" => array("tid" => 3, "iid" => 1),
		"cond2" => array("tid" => 1, "iid" => 3),
		"cond3" => array("tid" => 2, "iid" => 2),
		"condA" => array("tid" => 5, "iid" => 2),
		"condB" => array("tid" => 4, "iid" => 1)
	),
	// Redoing P06. Bu Yi
	"P13" => array(
		"cond1" => array("tid" => 1, "iid" => 3),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 1),
		"condA" => array("tid" => 4, "iid" => 2),
		"condB" => array("tid" => 5, "iid" => 1)
	),
	// Redoing P04. Sam
	"P14" => array(
		"cond1" => array("tid" => 1, "iid" => 2),
		"cond2" => array("tid" => 2, "iid" => 3),
		"cond3" => array("tid" => 3, "iid" => 1),
		"condA" => array("tid" => 5, "iid" => 2),
		"condB" => array("tid" => 4, "iid" => 1)
	),
	// Redoing P09. Yunjin
	"P15" => array(
		"cond1" => array("tid" => 3, "iid" => 3),
		"cond2" => array("tid" => 1, "iid" => 2),
		"cond3" => array("tid" => 2, "iid" => 1),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),
	// Redoing P07. Jonghyun
	"P16" => array(
		"cond1" => array("tid" => 3, "iid" => 3),
		"cond2" => array("tid" => 1, "iid" => 1),
		"cond3" => array("tid" => 2, "iid" => 2),
		"condA" => array("tid" => 5, "iid" => 1),
		"condB" => array("tid" => 4, "iid" => 2)
	),
	// Redoing P02. Jaehun
	"P17" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 3),
		"cond3" => array("tid" => 3, "iid" => 2),
		"condA" => array("tid" => 4, "iid" => 2),
		"condB" => array("tid" => 5, "iid" => 1)
	),
	// Redoing P03. Soubine
	"P18" => array(
		"cond1" => array("tid" => 1, "iid" => 2),
		"cond2" => array("tid" => 2, "iid" => 1),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 5, "iid" => 1),
		"condB" => array("tid" => 4, "iid" => 2)
	),
	// Redoing P05. Namwook
	"P19" => array(
		"cond1" => array("tid" => 1, "iid" => 3),
		"cond2" => array("tid" => 2, "iid" => 1),
		"cond3" => array("tid" => 3, "iid" => 2),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	),
	// Redoing P01. Sangwoo
	"P20" => array(
		"cond1" => array("tid" => 1, "iid" => 1),
		"cond2" => array("tid" => 2, "iid" => 2),
		"cond3" => array("tid" => 3, "iid" => 3),
		"condA" => array("tid" => 4, "iid" => 1),
		"condB" => array("tid" => 5, "iid" => 2)
	)
);

?>