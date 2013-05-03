<?php
$this->menu=array(
  array('label' => '&laquo; Evaluations', 'url' => array('evaluations/index')),
  array('label' => 'Questions', 'url' => array('evaluations/view', 'id' => $evaluation->id)),
  array('label' => 'Modify Evaluation', 'url' => array('evaluations/edit', 'id' => $evaluation->id)),
  array('label' => 'Publish', 'url' => array('evaluations/publish', 'id' => $evaluation->id), 'visible' => !$evaluation->published),
  array('label' => 'Report', 'url' => array('reports/index', 'evaluation_id' => $evaluation->id), 'visible' => $evaluation->published)
);
?>