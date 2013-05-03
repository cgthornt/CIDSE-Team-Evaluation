<?php

/*
 * class ReportsController
 */

require_once(dirname(__FILE__) . '/../../vendor/dompdf/dompdf_config.inc.php');

class ReportsController extends Controller {
  
  public $layout = '//layouts/column2';
  
  
  public function actionIndex() {
    $eval = $this->getEvaluation($_GET['evaluation_id']);
    $group = null;
    if(isset($_GET['group_select'])) {
      $group = CourseGroup::model()->where(
        array('id' => $_GET['group_select'], 'course_id' => $eval->course->id)
      )->find();
    }
    
    $this->render('index' , array('evaluation' => $eval, 'group' => $group, 'pdf' => false));
  }
  
  public function actionPdf($id) {
    $eval = $this->getEvaluation($id);
		$html = '';
    foreach($eval->course->groups as $group) {
			$html .= $this->renderPartial('index', array('evaluation' => $eval, 'group' => $group, 'pdf' => true), true);
		}
		
		$pdf = new DOMPDF();
		$pdf->load_html($html);
		$pdf->render();
		$pdf->stream('report.pdf');
		die();
  }
  
  
  
  protected function getEvaluation($evaluationID) {
    $evaluation = Evaluation::model()->with(
      array(
        'questions',
        'course.professors' => array(
          'condition' => 'professors.id = :professor_id',
          'params'    => array(':professor_id' => $this->user->id)
      ))
    )->where(array('t.id' => $evaluationID))->find();
    
    if($evaluation == null)
      throw new CHttpException(404, "Cannot find requested evaluation!");
    return $evaluation;
  }
  
  public function accessRules() {
    return array(
      array('allow',
            'actions' => array('index'),
            'roles' => array('faculty', 'admin'))
    );
  }
  
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete archive', // we only allow deletion via POST request
      'requireCourse + enroll students importstudent'
    );
	}
  
}
